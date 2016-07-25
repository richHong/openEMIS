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

App::uses('AppModel', 'Model');

class GuardianIdentity extends AppModel {
	public $belongsTo = array(
		'StudentGuardian' => array(
			'foreignKey' => 'guardian_id',
			'type' => 'LEFT'
		),
		'IdentityType',
		'Country' => array(
			'fields' => array('name'),
			'foreignKey' => 'country_id',
			'type' => 'LEFT'
		),
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
	
	public $actsAs = array(
		'ControllerAction',
		'DatePicker' => array('issue_date', 'expiry_date'),
		'Export' => array('module' => 'Guardian')
	);

    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'number' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('value')
                )
            ),
            'issue_date' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('date')
                )
            ),
            'expiry_date' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('date')
                )
            ),
            'issue_date' => array(
                'ruleNotLater' => array(
                    'rule' => array('compareDate', 'expiry_date'),
                    'message' => $this->getErrorMessage('issueDateLater')
                ),
            ),
            'country_id' => array(
		        'notEmpty' => array(
		            'rule' => array('notEmpty'),
		            'message' => $this->getErrorMessage('country'),
		            'allowEmpty' => false
		        ),
		    ),
            'identity_type_id' => array(
		        'notEmpty' => array(
		            'rule' => array('notEmpty'),
		            'message' => $this->getErrorMessage('identity_type_id'),
		            'allowEmpty' => false
		        ),
		    )
        );
    }
	
	public function beforeAction() {
		parent::beforeAction();
		$this->setVar('tabHeader', $this->Message->getLabel($this->alias.'.name'));
		$this->Navigation->addCrumb($this->Message->getLabel(
			$this->alias.'.name'));
		$this->fields['guardian_id']['type'] = 'hidden';
		$this->fields['guardian_id']['value'] = $this->Session->read('StudentGuardian.id');
		$this->fields['identity_type_id']['type'] = 'select';
		$this->fields['identity_type_id']['options'] = $this->IdentityType->getOptions();
		$this->fields['country_id']['type'] = 'select';
		$this->fields['country_id']['options'] = $this->Country->getOptions();
	}

	public function index() {
		$data = $this->getListData();
		if(empty($data)) {$this->Message->alert('general.view.noRecords', array('type' => 'info'));}
		$this->setVar('data', $data);
	}
	
	public function view($id=0) {
		parent::view($id);
		$this->render = 'view';
	}

	public function edit($id=0) {
		parent::edit($id);
		$this->render = 'edit';
	}
	
	public function add() {
		parent::add();
		if (empty($this->request->data)) {
			$default = $this->Country->getDefaultValue();
			$this->fields['country_id']['default'] = $default;
		}
		$this->render = 'edit';
	}

	public function reportGetFieldNames() {
		return $this->getFieldNamesFromFields($this->fields);
	}

	public function reportGetData() {
		$fields = $this->fields;
		unset($fields['guardian_id']);
		return $this->handleOptionsInData($fields,$this->getListData());
	}

	public function getListData($options=array()) {
		$guardianId = $this->Session->read('StudentGuardian.id');
		$this->recursive = 0;
		$data = $this->findAllByGuardianId($guardianId);
		return $data;
	}
}
