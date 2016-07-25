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

class StudentGuardian extends AppModel {
	public $belongsTo = array(
		'SecurityUser',
		'Student',
		'RelationshipCategory',
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
		'Export' => array('module' => 'Student'),
		'AutoGenerateId' => array('module' => 'Guardian')
	);

	public function __construct() {
        parent::__construct();

        $this->validate = array(
        	'security_user_id' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('noResult')
                )
            )
        );
    }

    public function getFields($options=array()) {
    	$currentCustomField = $this->alias.'CustomField';
		$currentCustomValue = $this->alias.'CustomValue';

		parent::getFields();
		$user = $this->SecurityUser->getFields();
		$order = 1;

		$this->setField('first_name', $user, $order++);
		$this->setField('middle_name', $user, $order++);
		$this->setField('last_name', $user, $order++);
		// $this->setField('identification_no', $user, $order++);

		// $this->fields[$currentCustomField] = $this->$currentCustomField->getCustomFields();
		
		return $this->fields;
	}

	public function beforeAction() {
		parent::beforeAction();
		$this->setVar('tabHeader', $this->Message->getLabel('guardian.title'));
		$this->Navigation->addCrumb($this->Message->getLabel('general.guardians'));

		$this->fields['student_id']['type'] = 'hidden';
		$this->fields['student_id']['value'] = $this->Session->read('Student.id');
		$this->fields['security_user_id']['type'] = 'hidden';

		$this->fields['relationship_category_id']['type'] = 'select';
		$this->fields['relationship_category_id']['options'] = $this->RelationshipCategory->getOptions();
	}
	public function index() {
		$data = $this->getListData();
		$this->setVar('data', $data);
	}

	public function paginateJoins($joins, $params) {
		$joins = array();
		array_push($joins, 
			array(
					'table' => 'security_user_types',
					'alias' => 'SecurityUserType',
					'conditions' => array(
						'SecurityUserType.security_user_id = SecurityUser.id',
						'SecurityUserType.type = 4'
					)
				)
		);
		return $joins;
	}
	
	public function paginateConditions($params) {
		$conditions = array('OR' => array());
		foreach($params as $model => $values) {
			foreach($values as $name => $val) {
				if(!empty($val)) {
					$key = $model.'.'.$name;
					if($this->endsWith($name, '_id')) {
						$conditions[$key] = $val;
					} else {
						$key .= ' LIKE';
						$conditions['OR'][$key] = '%' . $val . '%';
					}
				}
			}
		}
		return $conditions;
	}

	public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		$model = $this->name;
		$fields = array(
			'SecurityUser.id',
			'SecurityUser.first_name',
			'SecurityUser.middle_name',
			'SecurityUser.last_name',
			'SecurityUser.openemisid'
		);
		$joins = array();
		// pr($this->paginateConditions($conditions));
		// pr($this->paginateJoins($joins, $conditions));
		$data = $this->SecurityUser->find('all', array(
			'recursive' => 0,
			'fields' => $fields,
			'joins' => $this->paginateJoins($joins, $conditions),
			'conditions' => $this->paginateConditions($conditions),
			'limit' => $limit,
			'offset' => (($page-1)*$limit),
			'group' => null,
			'order' => $order
		));
		// pr($data);

		foreach ($data as $key => $val) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($val);
		}
		
		return $data;
	}

	public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$joins = array();
		$count = $this->SecurityUser->find('count', array(
			'recursive' => 0,
			'joins' => $this->paginateJoins($joins, $conditions),
			'conditions' => $this->paginateConditions($conditions)
		));
		return $count;
	}

	public function reportGetFieldNames() {
		return $this->getFieldNamesFromFields($this->fields);
	}

	public function reportGetData() {
		return $this->handleOptionsInData($this->fields,$this->getListData());
	}

	public function getListData($options=array()) {
		$studentId = $this->Session->read('Student.id');
		$this->recursive = 0;

		$data = $this->find(
			'all',
			array(
				'recursive' => 0,
				'fields' => array(
					'SecurityUser.first_name', 'SecurityUser.middle_name', 'SecurityUser.last_name', 'SecurityUser.openemisid', 'SecurityUser.id', 'RelationshipCategory.name'
				),
				'conditions' => array(
					'StudentGuardian.student_id' => $studentId
				)
			)
		);
		$GuardianContact = ClassRegistry::init('GuardianContact');
		foreach ($data as $key => $val) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($val);	
			$data[$key]['Contacts'] = $GuardianContact->getMainContacts($val['SecurityUser']['id']);
		}

		return $data;
	}
}
?>
