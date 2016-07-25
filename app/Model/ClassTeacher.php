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

class ClassTeacher extends AppModel {
	public $belongsTo = array(
		'SClass' => array(
			'className' => 'SClass',
			'foreignKey' => 'class_id',
		),
		'Staff'
	);
	
	public $actsAs = array('ControllerAction','Export' => array('module' => 'SClass'));

	public $accessMapping = array(
		'autocomplete' => 'read'
	);
	
	public function beforeAction() {
		parent::beforeAction();
		
		$this->setVar('header', $this->Message->getLabel(get_class($this).'.title'));
		$this->Navigation->addCrumb($this->Message->getLabel(get_class($this).'.title'));
	}
	
	public function sort($conditions, $by, $direction) {
		$options = array(
			'recursive' => -1,
			'fields' => array(
				'ClassTeacher.id', 'Staff.id', 'SecurityUser.openemisid', 'SecurityUser.first_name', 'SecurityUser.middle_name', 'SecurityUser.last_name'
			),
			'joins' => array(
				array(
					'table' => 'staff',
					'alias' => 'Staff',
					'conditions' => array('Staff.id = ClassTeacher.staff_id')
				),
				array(
					'table' => 'security_users',
					'alias' => 'SecurityUser',
					'conditions' => array('SecurityUser.id = Staff.security_user_id')
				),
			),
			'conditions' => $conditions
		);
		
		if (!empty($by)) {
			$order = array();
			$order_pieces = explode(",", $by);
			if (sizeof($order_pieces) > 1) {
				foreach ($order_pieces as $key => $value) {
					$order[$value] = $direction;
				}
			} else {
				$order = array($by => $direction);
			}
		} else {
			$order = array('SecurityUser.openemisid' => 'asc');
		}
		$options['order'] = $order;
		
		$data = $this->find('all', $options);
		foreach($data as $key => $row) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($row);
		}
		return $data;
	}
	
	public function index() {
		$data = $this->getListData();
		if(empty($data)) {
			$this->Message->alert('general.view.noRecords');
		}
		$this->setVar('data', $data);
	}
	
	public function add() {
		$classId = $this->Session->read('SClass.id');
		
		if ($this->request->is(array('post', 'put'))) {
			if (!empty($this->request->data[$this->alias]['staff_id'])) {
				$staffId = $this->request->data[$this->alias]['staff_id'];
				
				$count = $this->find('count', array('conditions' => array('staff_id' => $staffId, 'class_id' => $classId)));
				if ($count > 0) {
					$this->Message->alert($this->alias.'.isExists');
				} else {
					$this->request->data[$this->alias]['class_id'] = $classId;
					if ($this->save($this->request->data[$this->alias])) {
						$this->Message->alert('general.add.success');
						return $this->controller->redirect(array('action' => $this->alias));
					} else {
						$this->Message->alert('general.add.failed');
					}
				}
			}
		}
		$this->setVar(compact('classId'));
	}

	public function reportGetFieldNames() {
		$fieldNames = $this->getFieldNamesFromData($this->reportData);
		unset($fieldNames['ClassTeacher.id']);
		return $fieldNames;
	}

	public function reportGetData() {
		$this->reportData = $this->getListData();
		return $this->reportData;
	}



	public function getListData($options=array()) {
		$classId = $this->Session->read('SClass.id');
		$conditions[$this->alias.'.class_id'] = $classId;
		$data = $this->controller->Sortable->sort($this, array('conditions' => $conditions));
		return $data;
	}

	public function autocomplete() {
		$this->render = false;
		if($this->request->is('ajax')) {
			$this->controller->autoRender = false;
			$search = $this->controller->params->query['term'];
			
			$search = sprintf('%%%s%%', $search);
			$list = $this->Staff->find('all', array(
				'recursive' => -1,
				'fields' => array('Staff.id', 'SecurityUser.first_name', 'SecurityUser.last_name', 'SecurityUser.openemisid'),
				'joins' => array(
					array(
						'table' => 'security_users',
						'alias' => 'SecurityUser',
						'conditions' => array('SecurityUser.id = Staff.security_user_id')
					)
				),
				'conditions' => array(
					'OR' => array(
						'SecurityUser.first_name LIKE' => $search,
						'SecurityUser.last_name LIKE' => $search,
						'SecurityUser.openemisid LIKE' => $search
					)
				),
				'order' => array('SecurityUser.first_name', 'SecurityUser.last_name')
			));
	
			$data = array();
			
			foreach($list as $obj) {
				$staffId = $obj['Staff']['id'];
				$firstName = $obj['SecurityUser']['first_name'];
				$lastName = $obj['SecurityUser']['last_name'];
				$openemisid = $obj['SecurityUser']['openemisid'];
				
				$data[] = array(
					'label' => trim(sprintf('%s - %s %s', $openemisid, $firstName, $lastName)),
					'value' => array('staff-id' => $staffId, 'first-name' => $firstName, 'last-name' => $lastName)
				);
			}
			
			return json_encode($data);
		}
	}

	public function getClassesByStaffId($staffId) {
		return $this->find(
			'list',
			array(
				'fields' => array('ClassTeacher.class_id'),
				'conditions' => array(
					'ClassTeacher.staff_id' => $staffId
				)
			)
		);
	}

	public function getTeacherByClass($classId, $searchMode = 'all') {
		
		//if($searchMode == 'all'){
			$options['fields'] = array(
				'ClassTeacher.id', 
				'ClassTeacher.staff_id', 
				'Staff.id', 
				'SecurityUser.openemisid',  
				'SecurityUser.first_name', 
				'SecurityUser.middle_name', 
				'SecurityUser.last_name'
			);
		/*}
		else{
			$this->virtualFields['full_name'] = 'concat (SecurityUser.first_name, " ",SecurityUser.last_name)';
			
			$options['fields'] = array(
				'ClassTeacher.staff_id', 
				'full_name'
			);
		}*/
		
		$options['recursive'] = -1;
		$options['joins'] = array(
			array(
				'table' => 'staff',
				'alias' => 'Staff',
				'conditions' => array('Staff.id = ClassTeacher.staff_id')
			),
			array(
				'table' => 'security_users',
				'alias' => 'SecurityUser',
				'conditions' => array('SecurityUser.id = Staff.security_user_id')
			)
		);
		
		$options['conditions'] = array('ClassTeacher.class_id' => $classId);
		$options['orders'] = array('SecurityUser.first_name');
		
		$data = $this->find('all', $options);
		
		if($searchMode == 'list'){
			$list = array();
			foreach($data as $obj) {
				$id = $obj['Staff']['id'];
				$openemisid = $obj['SecurityUser']['openemisid'];
				$first_name = $obj['SecurityUser']['first_name'];
				$last_name = $obj['SecurityUser']['last_name'];
				$list[$id] = sprintf('%s - %s %s', $openemisid, $first_name, $last_name);
			}
			return $list;
		}
		else{
			return $data;	
		}
		//return $data;
	}

	public function getTeachersById($id) {
		$data = $this->find('all', array(
			'fields' => array('Staff.id', 'SecurityUser.openemisid', 'ClassTeacher.id', 'ClassTeacher.staff_id',  'SecurityUser.first_name', 'SecurityUser.last_name',
				'CreatedUser.id', 'ClassTeacher.created', 'CreatedUser.first_name', 'CreatedUser.last_name', 'ModifiedUser.id', 'ClassTeacher.modified', 'ModifiedUser.first_name', 'ModifiedUser.last_name'
				),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'staff',
					'alias' => 'Staff',
					'conditions' => array('Staff.id = ClassTeacher.staff_id')
				),
				array(
					'table' => 'security_users',
					'alias' => 'SecurityUser',
					'conditions' => array('SecurityUser.id = Staff.security_user_id')
				),
				array(
					'table' => 'security_users',
					'type' => 'LEFT',
					'alias' => 'CreatedUser',
					'conditions' => array('CreatedUser.id = ClassTeacher.created_user_id')
				),
				array(
					'table' => 'security_users',
					'type' => 'LEFT',
					'alias' => 'ModifiedUser',
					'conditions' => array('ModifiedUser.id = ClassTeacher.modified_user_id')
				)
			),
			'conditions' => array('ClassTeacher.id' => $id),
			'order' => array('SecurityUser.first_name')
		));

		return $data;
	}
}
