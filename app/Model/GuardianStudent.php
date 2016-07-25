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

class GuardianStudent extends AppModel {
	public $useTable = 'student_guardians';
	public $belongsTo = array(
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
		'ControllerAction'
	);

    public function __construct() {
        parent::__construct();

        $this->validate = array(
        );
    }

    public $accessMapping = array(
		'student_search' => 'create'
	);
	
	public function beforeAction() {
		parent::beforeAction();
		
		$this->Navigation->addCrumb($this->Message->getLabel('GuardianStudent.name'));

		$this->setVar('tabHeader', $this->Message->getLabel('Student.title'));
	}

	public function index() {
		$id = $this->Session->read('StudentGuardian.data.SecurityUser.id');
		$data = $this->find('all', array(
			'recursive' => 0, 
			'fields' => array('GuardianStudent.student_id','RelationshipCategory.name'),
			'conditions' => array($this->alias.'.security_user_id' => $id),
			'order' => array()
		));

		$Student = ClassRegistry::init('Student');
		foreach ($data as $key => $value) {
			$data[$key]['StudentData'] = $Student->find(
				'first',
				array(
					'recursive' => 0,
					'fields' => array('SecurityUser.id', 'SecurityUser.first_name', 'SecurityUser.middle_name', 'SecurityUser.last_name', 'SecurityUser.openemisid', 'SecurityUser.date_of_birth', 'SecurityUser.gender'),
					'conditions' => array(
						'Student.id' => $value['GuardianStudent']['student_id']
					)
				)
			);
			$data[$key]['StudentData']['SecurityUser']['full_name'] = $this->Message->getFullName($data[$key]['StudentData']);
		}
		$this->setVar(compact('data'));
	}
	
	public function add() {
		$this->render = 'add';
		$guardianId = $this->Session->read('StudentGuardian.id');
		if ($this->controller->request->is(array('post', 'put'))) {
			unset($this->controller->request->data['GuardianStudent']['search']);
			$studentSecurityUserId = $this->controller->request->data['GuardianStudent']['student_security_user_id'];
			// pr($studentSecurityUserId);
			if (is_numeric($studentSecurityUserId)) {
				$Student = ClassRegistry::init('Student');
				$studentData = $Student->find(
					'first',
					array(
						'recursive' => -1,
						'fields' => 'Student.id',
						'conditions' => array(
							'Student.security_user_id' => $studentSecurityUserId
						)
					)
				);
				// pr($studentData);
				if (!empty($studentData)) {
					$this->controller->request->data['GuardianStudent']['student_id'] = $studentData['Student']['id'];
					unset($this->controller->request->data['GuardianStudent']['student_security_user_id']);

					$this->create();
					if ($this->save($this->controller->request->data)) {
						$this->controller->Message->alert('general.add.success');
						return $this->controller->redirect(array('action' => $this->indexPage));
					} else {
						// pr('1');
						$this->controller->Message->alert('general.add.failed', array('type' => 'error'));
					}
				} else {
					// pr('2');
					$this->controller->Message->alert('general.add.failed', array('type' => 'error'));
				}
			} else {
				// pr('3');
				$this->controller->Message->alert('general.add.failed', array('type' => 'error'));
			}
		}
		$relationshipOptions = ClassRegistry::init('RelationshipCategory')->getOptions('name', 'order', 'asc', array('visible'=>1));
		$this->controller->set('relationshipOptions', $this->controller->Utility->getSetupOptionsData($relationshipOptions));
		$this->setVar('guardianId',$guardianId);
	}

	public function student_search() {
		$this->render = false;
		if($this->controller->request->is('get')) {
			if($this->controller->request->is('ajax')) {
				$search = $this->controller->params->query['term'];
				$SecurityUser = ClassRegistry::init('SecurityUser');
				$result = $SecurityUser->autocomplete($search, 4);
				echo json_encode($result);
			}
		}
	}
}
