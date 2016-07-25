<?php
/*
OpenEMIS School
Open School Management Information System

This program is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 3 of the License, or any later version. This program is distributed in the hope 
that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should 
have received a copy of the GNU General Public License along with this program.  If not, see 
<http://www.gnu.org/licenses/>.  For more information please email contact@openemis.org.
*/

App::uses('Contact', 'Model');

abstract class SecurityUserContact extends AppModel {
    public $belongsTo = array(
        'SecurityUser',
        'ContactType',
        'ModifiedUser' => array(
            'className' => 'SecurityUser',
            'fields' => array('first_name', 'last_name'),
            'foreignKey' => 'modified_user_id',
            'type' => 'LEFT'
        ),
        'CreatedUser' => array(
            'className' => 'SecurityUser',
            'fields' => array('first_name', 'last_name'),
            'foreignKey' => 'created_user_id',
            'type' => 'LEFT'
        )
    );
    
    public $useTable = 'contacts';
    public $actsAs = array('ControllerAction');
    public $indexPage = 'contact';
    public $module = 'Contacts';
    
    /* -------------------------------------------------------------------------
     * | This property is specially created for the "beforeValidate" function. |
     * ------------------------------------------------------------------------- */
    public $type = '';
    
    public function beforeAction($controller, $params) {
        $controller->Navigation->addCrumb($this->module);
        $controller->set('model', get_class($this));
        $controller->set('page', $this->indexPage);
    }

    public function beforeValidate($options = array()) {
        switch ($this->type) {
            case 1:
            case 2:
            case 3:
                $this->validate['value'] = array(
                    'customVal' => array(
                        'rule' => 'numeric',
                        'required' => true,
                        'message' => 'Please enter a valid Numeric value'
                    )
                );
                 
                break;
                    
            case 4:
                $this->validate['value'] = array(
                    'customVal' => array(
                        'rule' => 'email',
                        'required' => true,
                        'message' => 'Please enter a valid Email'
                    )
                );
                     
                break;
                    
            case 5:
                $this->validate['value'] = array(
                    'customVal' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'message' => 'Please enter a valid Value'
                    )
                );
                     
                break;
                    
            default:
                break;
        }

        return true; 
    }

    public function getDisplayFields() {
        $model = get_class($this);
        $fields = array(
            array('field' => 'type', 'model' => 'ContactType', 'label' => 'Type', 'options' => ClassRegistry::init('ContactType')->getTypeArray()),
            array('field' => 'name', 'model' => 'ContactType', 'label' => 'Description'),
            array('field' => 'value', 'model' => get_class($this), 'label' => 'Value'),
            //array('field' => 'main', 'model' => get_class($this), 'label' => 'Preferred'),
            array('field' => 'modified_by', 'model' => 'ModifiedUser', 'edit' => false),
            array('field' => 'modified', 'model' => 'SecurityUser', 'label' => 'Modified On', 'edit' => false),
            array('field' => 'created_by', 'model' => 'CreatedUser', 'edit' => false),
            array('field' => 'created', 'model' => 'SecurityUser', 'label' => 'Created On', 'edit' => false)
            
        );
        return $fields;
    }

    public function contact($controller, $params) {
        $relatedModelId = $controller->Session->read($this->getSessionKeyForUser());
        //$securityUserId = $this->getStudentSecurityUserId($studentId);
        $securityUserId = $this->getSecurityUserId($relatedModelId);
        
        /* ---------------------
         * | Get all contacts. |
         * --------------------- */
        $m = ClassRegistry::init('Contact');
        $data = $m->findAllBySecurityUserId($securityUserId);
        
        /* ---------------------------------------------------------------------------------
         * | Replace ENUM values with full description (e.g. m => Mobile, e => Email etc). |
         * --------------------------------------------------------------------------------- */
        $ctm = ClassRegistry::init('ContactType');
        $mapping = $ctm->getTypeArray();
        
        foreach ($data as &$r) {
            $r['ContactType']['type'] = $mapping[$r['ContactType']['type']];
        }
        
        //  Setup view variables
        $controller->set('data', $data);
        $controller->set('relatedModelId', $relatedModelId);
    }

    public function contact_add($controller, $params) {
        if ($controller->request->is(array('post', 'put'))) {
            $this->type = $controller->request->data[$this->alias]['type'];

            if ($this->saveAll($controller->request->data)) {
                $controller->Message->alert('general.add.success');
                return $controller->redirect(array('action' => $this->indexPage));
            } else {
                $controller->Message->alert('general.add.failed', array('type' => 'error'));
            }
        }
        
        $relatedModelId = $controller->Session->read($this->getSessionKeyForUser());
        
        $ctm = ClassRegistry::init('ContactType');
        $typeOptions = $ctm->getTypeArray();
        
        $contactTypeId = null;
        
        if ($controller->request->is('get')) {
            $contactTypeId = isset($params['pass'][0]) ? $params['pass'][0] : key($typeOptions);
        } else {
            $contactTypeId = $controller->request->data[$this->alias]['type'];
        }

        $descOptions = $ctm->find(
            'list',
            array(
                'conditions' => array(
                    'type' => $contactTypeId,
                    'visible' => 1
                ),
            'recursive' => -1)
        );
        
        $controller->set('typeOptions', $typeOptions);
        $controller->set('descOptions', $descOptions);
        $controller->set('mainOptions', array('1' => 'Yes', '0' => 'No'));
        $controller->set('relatedModelId', $relatedModelId);        
        $controller->set('selectedTypeOption', $contactTypeId);
        $controller->set('securityUserId', $this->getSecurityUserId($relatedModelId));
    }
    
    public function contact_view($controller, $params) {
        $id = isset($params['pass'][0]) ? $params['pass'][0] : null;
        
        if (!empty($id) && !$this->exists($id)) {
            $controller->Message->alert('general.view.notExists', array('type' => 'warn'));
            return $controller->redirect(array('action' => $this->indexPage));
        }
        
        $this->recursive = 0;
        $data = $this->findById($id);
        $controller->set('data', $data);
        $controller->set('fields', $this->getDisplayFields());
        $controller->set('id', $id);
    }
    
    public function contact_edit($controller, $params) {
        /* ---------------------------------
         * | Process the form if POST/PUT. |
         * --------------------------------- */
        if ($controller->request->is(array('post', 'put'))) {
            $record = $this->findById($controller->request->data[$this->alias]['id']);
            $this->type = $record['ContactType']['type'];

            if ($this->saveAll($controller->request->data)) {
                $controller->Message->alert('general.edit.success');
                return $controller->redirect(array('action' => $this->indexPage));
            } else {
                $controller->Message->alert('general.edit.failed', array('type' => 'error'));
            }
        }
        
        /* -----------------------------------------------------------------------------------------------
         * | Everything below executes only upon form validation failure or loading form for first time. |
         * ----------------------------------------------------------------------------------------------- */
        $id = isset($params['pass'][0]) ? $params['pass'][0] : null;
        $data = $this->find('first', array('conditions' => array(get_class($this) . '.id' => $id)));
        
        /* ------------------------------------------------------------
         * | If id not specified, or it's invalid, redirect to index. |
         * ------------------------------------------------------------ */
        if ($id == null || !$this->exists($id)) {
            return $controller->redirect(array('action' => $this->indexPage));
        }
        
        $relatedModelId = $controller->Session->read($this->getSessionKeyForUser());

        $ctm = ClassRegistry::init('ContactType');
        $typeOptions = $ctm->getTypeArray();
        $contactTypeId = $data['ContactType']['type'];
        $contactTypeName = $typeOptions[$contactTypeId];
        $descOptions = $ctm->find(
            'list',
            array(
                'conditions' => array(
                    'type' => $contactTypeId,
                    'visible' => 1
                ),
            'recursive' => -1)
        );
        
        $controller->set('typeOptions', $typeOptions);
        $controller->set('descOptions', $descOptions);
        $controller->set('mainOptions', array('1' => 'Yes', '0' => 'No'));
        $controller->set('relatedModelId', $relatedModelId);        
        $controller->set('selectedTypeOption', $contactTypeId);
        $controller->set('typeName', $contactTypeName);
        $controller->set('securityUserId', $this->getSecurityUserId($controller->Session->read($this->getSessionKeyForUser())));
        $controller->set('id', $id);
        $controller->set('data', $data);
    }

    public function contact_delete($controller, $params) {
        $id = !empty($params['pass'][0]) ? $params['pass'][0] : null;

        if (!empty($id)) {
            $conditions = array(
                'id' => $id
            );
            
            if ($this->hasAny($conditions)) {
                if ($controller->request->is('post')) {
                    $this->delete($id);
                    $controller->Message->alert('general.delete.success');
                    return $controller->redirect(array('action' => 'contact'));
                }
                
                $controller->set('id', $id);
            } else {
                $controller->Message->alert('general.view.notExists', array('type' => 'warn'));
                return $controller->redirect(array('action' => 'contact'));
            }
        } else{
            $controller->Message->alert('general.view.notExists', array('type' => 'warn'));
            return $controller->redirect(array('action' => 'contact'));
        }
    }
    
    protected function getSecurityUserID($id) {
        $m = ClassRegistry::init($this->getRelatedModelName());
        $record = $m->findById($id);
        
        return $record['SecurityUser']['id'];
    }
    
    abstract protected function getSessionKeyForUser();
    abstract protected function getRelatedModelName();
    
    /*public $validate = array(
        'contact_no' => array(
            'ruleNotEmpty' => array(
                'rule' => 'notEmpty',
                'required' => false,
                'message' => 'Please enter a valid Contact No'
            )
        )
    );*/
    
    /*function validatePreferred($check1, $field2) {
        $flag = false;
        
        foreach ($check1 as $key => $value1) {
            $preferred = $this->data[$this->alias][$field2];
            $contactOption = $this->data[$this->alias]['contact_option_id'];
            
            if ($preferred == "0" && $contactOption != "5") {
                if (isset($this->data[$this->alias]['id'])) {
                    $contactId = $this->data[$this->alias]['id'];
                    $count = $this->find(
                        'count',
                        array(
                            'conditions' => array(
                                'ContactType.contact_option_id' => $contactOption,
                                array(
                                    'NOT' => array(
                                        'StudentContact.id' => array($contactId)
                                    )
                                )
                            )
                        )
                    );
                    
                    if ($count != 0) {
                        $flag = true;
                    }
                } else {
                    $count = $this->find('count', array('conditions' => array('ContactType.contact_option_id' => $contactOption)));
                    
                    if ($count != 0) {
                        $flag = true;
                    }
                }
            } else {
                $flag = true;
            }

        }

        return $flag;
    }*/
}
?>