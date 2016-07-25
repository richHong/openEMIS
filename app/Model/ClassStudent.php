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
class ClassStudent extends AppModel {
	public $belongsTo = array(
		'SClass' => array(
			'className' => 'SClass',
			'foreignKey' => 'class_id',
		),
		'EducationGrade',
		'Student',
		'StudentCategory'
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
				'ClassStudent.id', 'ClassStudent.education_grade_id', 'Student.id', 'SecurityUser.openemisid', 'StudentCategory.name', 'SecurityUser.first_name', 'SecurityUser.middle_name', 'SecurityUser.last_name', 'EducationGrade.name'
			),
			'joins' => array(
				array(
					'table' => 'students',
					'alias' => 'Student',
					'conditions' => array('Student.id = ClassStudent.student_id')
				),
				array(
					'table' => 'security_users',
					'alias' => 'SecurityUser',
					'conditions' => array('SecurityUser.id = Student.security_user_id')
				),
				array(
					'table' => 'student_categories',
					'alias' => 'StudentCategory',
					'conditions' => array('StudentCategory.id = ClassStudent.student_category_id')
				),
				array(
					'table' => 'education_grades',
					'alias' => 'EducationGrade',
					'conditions' => array('EducationGrade.id = ClassStudent.education_grade_id')
				)
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
	
	public function index($gradeId=0) {
		$classId = $this->Session->read('SClass.id');
		$gradeOptions = $this->SClass->ClassGrade->getGradeListByClassId($classId);

		if (empty($gradeOptions)) {
			$this->Message->alert('EducationGradesSubject.noGrades');
			$this->setVar('data', array());
			$this->setVar('gradeOptions', array());
			$this->setVar('gradeId', $gradeId);
		} else {
			if($gradeId==0) {
				$gradeId = key($gradeOptions);
			}

			$this->Session->write('ClassStudent.gradeId', $gradeId);
			$data = $this->getListData(array('classId'=>$classId,'gradeId'=>$gradeId));

			$this->setVar('data', $data);
			$this->setVar('gradeOptions', $gradeOptions);
			$this->setVar('gradeId', $gradeId);
		}
	}
	
	public function add() {
		$classId = $this->Session->read('SClass.id');
		
		if ($this->request->is(array('post', 'put'))) {
			if (!empty($this->request->data[$this->alias]['student_id'])) {
				$studentId = $this->request->data[$this->alias]['student_id'];
				
				$count = $this->find('count', array('conditions' => array('student_id' => $studentId, 'class_id' => $classId)));
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
		$studentCategoryOptions = $this->StudentCategory->find('list', array('conditions' => array('visible' => 1), 'order' => 'order'));
		$gradeOptions = $this->SClass->ClassGrade->getGradeListByClassId($classId);
		if (empty($gradeOptions)) return $this->controller->redirect(array('action' => $this->alias));
		
		$this->setVar(compact('classId', 'gradeOptions', 'studentCategoryOptions'));
	}

	public function reportGetFieldNames() {
		$fieldNames = $this->getFieldNamesFromData($this->reportData);
		unset($fieldNames['ClassStudent.id']);
		unset($fieldNames['ClassStudent.education_grade_id']);
		unset($fieldNames['StudentCategory.name']);
		return $fieldNames;
	}

	public function reportGetData() {
		$options = array(
			'gradeId' => $this->Session->read('ClassStudent.gradeId'),
			'classId' => $this->Session->read('SClass.id')
		);
		$this->reportData = $this->getListData($options);
		return $this->reportData;
	}

	public function getListData($options=array()) {
		if (array_key_exists('classId', $options)) {
			$classId = $options['classId'];
		}
		if (array_key_exists('gradeId', $options)) {
			$gradeId = $options['gradeId'];
		}

		$conditions[$this->alias.'.class_id'] = $classId;
		$conditions[$this->alias.'.education_grade_id'] = $gradeId;
		
		$data = $this->controller->Sortable->sort($this, array('conditions' => $conditions));
		
		if(empty($data)) {
			$this->Message->alert('general.view.noRecords');
		}

		$classData = ($this->Session->read('SClass.data'));
		$schoolYear = $classData['school_year_id'];

		$EducationFee = ClassRegistry::init('EducationFee');
		$currentEducationFee = $EducationFee->getEducationFeesTotalAmount($schoolYear,$gradeId);
		
		$StudentFee = ClassRegistry::init('StudentFee');
		foreach($data as $key => $row) {
			$data[$key]['StudentFee'] = array();
			$data[$key]['StudentFee']['amount_paid'] = $StudentFee->getStudentFeePaidTotalAmount($schoolYear,$gradeId,$row['Student']['id']);
			$data[$key]['StudentFee']['fee'] = $currentEducationFee;
			$data[$key]['StudentFee']['outstanding'] = $currentEducationFee-$data[$key]['StudentFee']['amount_paid'];

		}
		return $data;
	}

	public function student_delete($controller, $params){
		$this->controller->autoRender = false;
		$id = $this->Session->read('ClassStudent.id');
		$this->id = $id;

		if(!$id || !$this->exists()) {
			$this->Message->alert('general.view.notExists', array('type' => 'warn'));
			$this->controller->redirect(array('action' => 'student'));
		}
		
		if ($this->delete()) {
			$this->Message->alert('general.delete.success');
		} else {
			$this->Message->alert('general.delete.failed', array('type' => 'error'));
		}
		return $this->controller->redirect(array('action' => 'student'));
	}
	
	/*
	
	public function student_select($controller, $params){
		$id = $this->Session->read('Class.id');

		$gradeId = empty($params['pass'][0])?NULL:$params['pass'][0];
		
		$newClassId = empty($params['pass'][1])?NULL:$params['pass'][1];

		$this->Navigation->addCrumb($this->Message->getLabel('student.title'), array('controller' => $this->controller->params['controller'], 'action' => 'students', $gradeId));
		$this->Navigation->addCrumb($this->Message->getLabel('general.edit'));
		$this->setVar('classId', $id);
		$this->setVar('gradeId', $gradeId);
		
		$classOptions = $this->controller->SClass->getClassListSchoolYear();
		
		unset($classOptions[$id]);
		if(empty($newClassId)){
			if(!empty($classOptions)){
				$newClassId = key($classOptions);
			}
		}
		
		$students = $this->getStudentsByClass($newClassId);
		
		if ($this->request->is('post')) {
			$data = $this->request->data['ClassStudent'];
			
			foreach($data as $key=>$value){
				$data[$key]['class_id'] = $id;
				if($value['checked']==0){
					unset($data[$key]);
				}
			}
			
			unset($data['master_check']);
			
			//check if the student exist in the class or not
			foreach($students as $student){
				foreach($data as $key => $item){
					if($student['Student']['id'] == $item['student_id']){
						unset($data[$key]);
					}
				}
			}
			
			if ($this->saveAll($data)) {
				$this->Message->alert('general.add.success');
				return $this->controller->redirect(array('action' => 'student', $gradeId));
			} else {
				$this->Message->alert('general.add.failed', array('type' => 'error'));
			}
		}

		$this->setVar('students', $students);

		$EducationGrade = ClassRegistry::init('EducationGrade');
		$grades = $EducationGrade->find('first', array('conditions'=>array('EducationGrade.id'=>$gradeId)));
		$this->setVar('grades', $grades);
		$this->setVar('newClassId', $newClassId);
		$this->setVar('classOptions', $this->controller->Utility->getSetupOptionsData($classOptions));
	}
	*/
	
	public function autocomplete() {
		$this->render = false;
		if($this->request->is('ajax')) {
			$this->controller->autoRender = false;
			$search = $this->controller->params->query['term'];
			
			$search = sprintf('%%%s%%', $search);
			$list = $this->Student->find('all', array(
				'recursive' => -1,
				'fields' => array('Student.id', 'SecurityUser.first_name', 'SecurityUser.last_name', 'SecurityUser.openemisid'),
				'joins' => array(
					array(
						'table' => 'security_users',
						'alias' => 'SecurityUser',
						'conditions' => array('SecurityUser.id = Student.security_user_id')
					)
				),
				'conditions' => array(
					'OR' => array(
						'SecurityUser.first_name LIKE' => $search,
						'SecurityUser.last_name LIKE' => $search,
						'SecurityUser.openemisid LIKE' => $search
					)
				),
				'order' => array('SecurityUser.openemisid', 'SecurityUser.first_name', 'SecurityUser.last_name')
			));
	
			$data = array();
			
			foreach($list as $obj) {
				$studentId = $obj['Student']['id'];
				$firstName = $obj['SecurityUser']['first_name'];
				$lastName = $obj['SecurityUser']['last_name'];
				$openemisid = $obj['SecurityUser']['openemisid'];
				
				$data[] = array(
					'label' => trim(sprintf('%s - %s %s', $openemisid, $firstName, $lastName)),
					'value' => array('student-id' => $studentId, 'first-name' => $firstName, 'last-name' => $lastName)
				);
			}
			
			return json_encode($data);
		}
	}

	public function getClassBySecurityId($securityId){
		$data = $this->find('all', array(
			'fields' => array('ClassStudent.class_id'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'students',
					'alias' => 'Student',
					'conditions' => array('Student.id = ClassStudent.student_id')
				),
				array(
					'table' => 'security_users',
					'alias' => 'SecurityUser',
					'conditions' => array('SecurityUser.id = Student.security_user_id')
				)
			),
			'conditions' => array('SecurityUser.id' => $securityId),
			'order' => array('ClassStudent.class_id')
		));
		$list = array();
		foreach($data as $obj){
			$list[] = $obj['ClassStudent']['class_id'];
		}

		return $list;
	
	}

	public function getClassByStudentId($studentId){
		$data = $this->find('list', array(
			'fields' => array('ClassStudent.class_id', 'SClass.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'students',
					'alias' => 'Student',
					'conditions' => array('Student.id = ClassStudent.student_id')
				),
				array(
					'table' => 'classes',
					'alias' => 'SClass',
					'conditions' => array('SClass.id = ClassStudent.class_id')
				),
				array(
					'table' => 'security_users',
					'alias' => 'SecurityUser',
					'conditions' => array('SecurityUser.id = Student.security_user_id')
				)
			),
			'conditions' => array('Student.id' => $studentId),
			'order' => array('ClassStudent.class_id')
		));

		/*$list = array();
		foreach($data as $obj){
			$list[$obj['ClassStudent']['class_id']] = $obj['SClass']['name'];
		}*/

		return $data;
		
		
	}
	
	public function getStudentYearByStudentId($studentId){
		$data = $this->find('list', array(
			'fields' => array('SClass.school_year_id', 'SchoolYear.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'classes',
					'alias' => 'SClass',
					'conditions' => array('SClass.id = ClassStudent.class_id')
				),
				array(
					'table' => 'school_years',
					'alias' => 'SchoolYear',
					'conditions' => array('SchoolYear.id = SClass.school_year_id')
				)
			),
			'conditions' => array('ClassStudent.student_id' => $studentId),
			'order' => array('SchoolYear.end_date desc')
		));

		/*$list = array();
		foreach($data as $obj){
			$list[$obj['SClass']['school_year_id']] = $obj['SchoolYear']['name'];
		}*/

		return $data;
	}

	public function getStudentsByClassGrade($classId, $gradeId) {
		$data = $this->find('all', array(
			'fields' => array('Student.id', 'ClassStudent.id', 'ClassStudent.student_category_id', 'StudentCategory.name', 'SecurityUser.openemisid', 'SecurityUser.first_name', 'SecurityUser.last_name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'students',
					'alias' => 'Student',
					'conditions' => array('Student.id = ClassStudent.student_id')
				),
				array(
					'table' => 'security_users',
					'alias' => 'SecurityUser',
					'conditions' => array('SecurityUser.id = Student.security_user_id')
				),
				array(
					'table' => 'student_categories',
					'alias' => 'StudentCategory',
					'conditions' => array('StudentCategory.id = ClassStudent.student_category_id')
				)
			),
			'conditions' => array('ClassStudent.class_id' => $classId, 'ClassStudent.education_grade_id' => $gradeId),
			'order' => array('SecurityUser.first_name')
		));

		return $data;
	}

	public function getStudentsById($id) {
		$data = $this->find('first', array(
			'fields' => array('Student.id', 'ClassStudent.id', 'ClassStudent.education_grade_id','ClassStudent.student_id', 'ClassStudent.student_category_id', 'ClassStudent.student_category_id', 'StudentCategory.name',  'SecurityUser.openemisid', 'SecurityUser.first_name', 'SecurityUser.last_name',
				'CreatedUser.id', 'ClassStudent.created', 'CreatedUser.first_name', 'CreatedUser.last_name', 'ModifiedUser.id', 'ClassStudent.modified', 'ModifiedUser.first_name', 'ModifiedUser.last_name'
				),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'students',
					'alias' => 'Student',
					'conditions' => array('Student.id = ClassStudent.student_id')
				),
				array(
					'table' => 'security_users',
					'alias' => 'SecurityUser',
					'conditions' => array('SecurityUser.id = Student.security_user_id')
				),
				array(
					'table' => 'student_categories',
					'alias' => 'StudentCategory',
					'conditions' => array('StudentCategory.id = ClassStudent.student_category_id')
				),
				array(
					'table' => 'security_users',
					'type' => 'LEFT',
					'alias' => 'CreatedUser',
					'conditions' => array('CreatedUser.id = ClassStudent.created_user_id')
				),
				array(
					'table' => 'security_users',
					'type' => 'LEFT',
					'alias' => 'ModifiedUser',
					'conditions' => array('ModifiedUser.id = ClassStudent.modified_user_id')
				)
			),
			'conditions' => array('ClassStudent.id' => $id),
			'order' => array('SecurityUser.first_name')
		));

		return $data;
	}

	public function getStudentsByClass($classId) {
		$data = $this->find('all', array(
			'fields' => array('Student.id', 'ClassStudent.id', 'ClassStudent.student_category_id', 'StudentCategory.name', 'SecurityUser.openemisid', 'StudentStatus.name', 'SecurityUser.first_name', 'SecurityUser.middle_name', 'SecurityUser.last_name', 'SecurityUser.gender'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'students',
					'alias' => 'Student',
					'conditions' => array('Student.id = ClassStudent.student_id')
				),
				array(
					'table' => 'security_users',
					'alias' => 'SecurityUser',
					'conditions' => array('SecurityUser.id = Student.security_user_id')
				),
				array(
					'table' => 'student_categories',
					'alias' => 'StudentCategory',
					'conditions' => array('StudentCategory.id = ClassStudent.student_category_id')
				),
				array(
					'table' => 'student_statuses',
					'alias' => 'StudentStatus',
					'conditions' => array('StudentStatus.id = Student.student_status_id')
				)
			),
			'conditions' => array('ClassStudent.class_id' => $classId),
			'order' => array('SecurityUser.first_name')
		));

		return $data;
	}

	public function getStudentTermsByStudentId($id){
		$yearsData = $this->find('all', array(
			'fields' => array('SchoolYear.id', 'SchoolYear.name', 'SClass.id', 'SClass.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'classes',
					'alias' => 'SClass',
					'conditions' => array('SClass.id = ClassStudent.class_id')
				),
				array(
					'table' => 'school_years',
					'alias' => 'SchoolYear',
					'conditions' => array('SchoolYear.id = SClass.school_year_id')
				)
			),
			'conditions' => array('ClassStudent.student_id' => $id),
			'order' => array('SchoolYear.name')
		));
		
		
		$data = array();
		if(!empty($yearsData)){
			//Retrive each semester from timetable
			App::import('Model','Timetable');
			$TimetableModel = new Timetable();
				
			foreach($yearsData as $key => $singleYear){
				//pr($singleYear['SchoolYear']['name']);
				$timetablesData = $TimetableModel->getAllTimetableByClass($singleYear['SClass']['id']);
				
				if(!empty($timetablesData)){
					$data = $this->setupSchoolTermsArrayData($singleYear['SchoolYear']['name'], $timetablesData);
				}
			}
		}
		
		return $data;
	}
	
	function setupSchoolTermsArrayData($yearName, $terms){
		$tempArr = array();
		foreach($terms as $key => $sTerm){
			$tempArr[$yearName][$key] = $sTerm;
		}
		
		return $tempArr;
	}


	public function getTotalStudiedStudentByClassId($id){
		$options['conditions'] = array('class_id' => $id);
		$options['recursive'] = -1;
		$options['fields'] = array('Count(student_id) as total_students');
		
		$data = $this->find('first', $options);
		
		return $data;
	}
}
?>
