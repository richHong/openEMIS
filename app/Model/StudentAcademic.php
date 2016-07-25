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

class StudentAcademic extends AppModel {
	public $belongsTo = array(
		'SecurityUser',
		'Student',
		'SchoolYear',
		'ClassStudent',
		'ClassSubject',
		'ClassGrade',
		'SClass'
	);
	
	public $actsAs = array('ControllerAction');
		
	public function academic_index($controller, $params) {
		$controller->Navigation->addCrumb($this->Message->getLabel('general.academic'));
		$studentId = $controller->Session->read('Student.id');
		
		$classSubjectOptions = ClassRegistry::init('ClassSubject')->getSubjectByStudentId($studentId);
		$controller->set('classSubjectOptions', $classSubjectOptions);

		$classStudentOptions = ClassRegistry::init('ClassStudent')->getClassByStudentId($studentId);
		$controller->set('classStudentOptions', $classStudentOptions);

		$classGradeOptions = ClassRegistry::init('ClassGrade')->getGradeListByStudentId($studentId);
		$controller->set('classGradeOptions', $classGradeOptions);

		$controller->set('studentId', $studentId);
	}
	
	public function academic_edit($controller, $params) {
		$id = isset($params['pass'][0]) ? $params['pass'][0] : null;
		$studentId = $controller->Session->read('Student.id');

		if(!empty($id) && !$this->exists($id)) {
			$controller->Message->alert('general.view.notExists', array('type' => 'warn'));
			return $controller->redirect(array('action' => 'guardian'));
		}

		if ($controller->request->is(array('post', 'put'))) {
			if ($this->saveAll($controller->request->data)) {
				if(!empty($id)){
					$controller->Message->alert('general.edit.success');
					return $controller->redirect(array('action' => 'academic_view', $id));
				}else{
					$controller->Message->alert('general.add.success');
					return $controller->redirect(array('action' => 'academic'));
				}
			} else {
				if(!empty($id)){
					$controller->Message->alert('general.edit.failed', array('type' => 'error'));
				}else{
					$controller->Message->alert('general.add.failed', array('type' => 'error'));
				}
			}
		} else {
			if(!empty($id)){
				$this->recursive = 0;
				$controller->request->data = $this->findById($id);
			}
		}

		$classSubjectOptions = ClassRegistry::init('ClassSubject')->getSubjectByStudentId($studentId);
		$controller->set('classSubjectOptions', $classSubjectOptions);

		$classStudentOptions = ClassRegistry::init('ClassStudent')->getClassByStudentId($studentId);
		$controller->set('classStudentOptions', $classStudentOptions);

		$classGradeOptions = ClassRegistry::init('ClassGrade')->getGradeListByStudentId($studentId);
		$controller->set('classGradeOptions', $classGradeOptions);

		$controller->set('studentId', $studentId);
		$controller->set('id', $id);
	}
	
}
