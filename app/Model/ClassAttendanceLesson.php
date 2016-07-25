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

class ClassAttendanceLesson extends AppModel {
	public $useTable = 'student_attendance_lessons';

	public $belongsTo = array(
		'Student',
		'StudentAttendanceType',
		'StudentAttendanceDay',
		'StudentAttendanceLesson',
		'EducationSubject'
	);
	
	public $actsAs = array('ControllerAction');

	public function beforeAction() {
		parent::beforeAction();

		$this->setVar('header', $this->Message->getLabel('Attendance.title'));
	}

	public function index($param1='', $param2='') {
		$className = $this->Session->read('SClass.data.name');
		$classId = $this->Session->read('SClass.id');

		$ClassSubject = ClassRegistry::init('ClassSubject');
		$subjectsOptions = $ClassSubject->getSubjectByClass($classId,'list');
		
		if(!empty($subjectsOptions)){
			$selectedSubject = empty($subjectsOptions)? 0: key($subjectsOptions);
			if ($param1 != '' && $param2 != '') {
				$selectedSubject = ($param1=='')? $selectedSubject: $param1;
				$selectedDate = !empty($param2)?$param2:"";
			} else {
				$selectedDate = !empty($param1)?$param1:"";
			}
		}
		else{
			$selectedSubject = 0;
			$selectedDate = !empty($param1)?$param1:"";
		}
		$datepickerData = $this->controller->Utility->datepickerStartEndDate($selectedDate);
		$startDate = $datepickerData['startDate'];
		$endDate = $datepickerData['endDate'];
		$dateDiff = $datepickerData['dateDiff']; 
				
		if(!empty($subjectsOptions) && $param2=''){
			$this->redirect(array('action' => 'attendance_class', key($subjectsOptions), $startDate));
		}
		
		$this->setVar('subjectsOptions', $this->controller->Utility->getSetupOptionsData($subjectsOptions));
		$this->setVar('className', $className);
		$this->setVar('classId', $classId);
		
		$this->setVar('dateDiff', $dateDiff);
		$this->setVar('startDate', $startDate);
		$this->setVar('endDate', $endDate);
		$this->setVar('selectedSubject', $selectedSubject);
		
		$attendanceType = ClassRegistry::init('StudentAttendanceType')->getAttendanceList('all', true);
		$this->setVar('attendanceType', $attendanceType);
		
		$timetableSource = ClassRegistry::init('TimetableEntry')->getClassTimetable($classId, $startDate, $endDate, array('education_subject_id'=> $selectedSubject));
		
		$ClassLesson = ClassRegistry::init('ClassLesson');
		$period = $ClassLesson->getLessonPeriod($classId,$timetableSource, $startDate, $endDate, array('education_subject_id'=> $selectedSubject));
		//pr($period);
		$this->setVar('period', $period);
		$this->setVar('tableHeaderData', $this->arrayValueCounter($period, 'date'));
		
		$data = ClassRegistry::init('ClassStudent')->getStudentsByClass($classId);
		foreach ($data as $key => $val) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($val);
		}
		$this->setVar('data', $data);
		
		$teachersData = $ClassLesson->getTeachersList($classId, $selectedSubject);
		$this->setVar('teachersData', $teachersData);
		

		$attendanceData = $this->StudentAttendanceLesson->getAttendanceByClass($classId, $selectedSubject, $startDate, $endDate);
		$this->setVar('attendanceData', $attendanceData);
		//pr($selectedSubject);
		
		if(empty($period) || empty($data) ){
			$this->Message->alert('general.view.notExists', array('type' => 'info'));	
		}

		$header = $className;
		if(isset($subjectsOptions[$selectedSubject])){
			$header .= ' / '.$subjectsOptions[$selectedSubject];
		}
				
		$this->setVar('tabHeader', $this->Message->getLabel('Attendance.title'));
		
		$this->request->data['ClassAttendanceLesson']['startDate'] = $datepickerData['startDate'];
		$this->request->data['ClassAttendanceLesson']['endDate'] =  $datepickerData['endDate'];
		$this->request->data['SubjectList'] =  $selectedSubject;

		$this->Navigation->addCrumb($this->Message->getLabel('Attendance.title'));
	}

	public function edit($param1='',$param2='') {
		$classId = $this->Session->read('SClass.id');
		$className = $this->Session->read('SClass.name');
		
		$selectedSubject = $param1;
		$this->setVar('selectedSubject', $selectedSubject);
		
		$date = empty($param2)? date('Y-m-d'):date('Y-m-d', $param2);
		$this->setVar('selectedDate', $date);
		
		if($this->request->is('post')){
			$postData = $this->request->data;
			if($this->StudentAttendanceLesson->saveAll($postData)){
				$this->Message->alert('general.add.success');
				return $this->redirect(array('action' => $this->alias,'index',$selectedSubject,$date));
			}
			else{
				$this->Message->alert('general.edit.failed', array('type' => 'error'));	
			}
		}
		
		$this->EducationSubject->recursive = -1;
		$subjectData = $this->EducationSubject->findById($selectedSubject, array('name', 'code'));
		
		
		$this->setVar('className', $className);
		$this->setVar('classId', $classId);
		$this->setVar('subjectName', $subjectData['EducationSubject']['name']);
		
		//Get list of attendance option and filter the list
		$attendanceType = ClassRegistry::init('StudentAttendanceType')->getAttendanceList();
		$this->setVar('attendanceType', $attendanceType);
		
		$attendanceTypeOptions = array();
		foreach($attendanceType as $item){
			$attendanceTypeOptions[$item['StudentAttendanceType']['id']] = $item['StudentAttendanceType']['short_form'];
		}
		$this->setVar('attendanceTypeOptions', $attendanceTypeOptions);
		
		//Get list of stundents
		$data = ClassRegistry::init('ClassStudent')->getStudentsByClass($classId);
		foreach ($data as $key => $val) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($val);
		}
		$this->setVar('data', $data);
		
		$ClassLesson = ClassRegistry::init('ClassLesson');
		$teachersData = $ClassLesson->getTeachersList($classId, $selectedSubject);
		$this->setVar('teachersData', $teachersData);
		
		//Getting lesson info
		$selectedLesson =  $ClassLesson->getSelectedLessonPeriod($classId, $selectedSubject, date('Y-m-d H:i:s', $param2));
		//pr($selectedLesson);
		
		$classLessonId = empty($selectedLesson['ClassLesson']['id'])? 0:$selectedLesson['ClassLesson']['id'];
		$this->setVar('classLessonId', $classLessonId);
		
		$startTime = date('H:i a', strtotime($selectedLesson['ClassLesson']['start_time']));
		$endTime = date('H:i a', strtotime($selectedLesson['ClassLesson']['end_time']));
		$this->setVar('attendanceTimeSlot', $startTime. " - ".$endTime);
		
		$attendanceData = $this->StudentAttendanceLesson->getAttendanceByClassLessonId($classLessonId);
		// pr($attendanceData);
		$this->setVar('attendanceData', $attendanceData);
		
		
		//Breadcrumb
		// $this->Navigation->addCrumb($className, array('controller'=>'Attendance', 'action' => 'index', 2, $classId, $selectedSubject));
		$this->Navigation->addCrumb($this->Message->getLabel('Attendance.title'));
		$this->Navigation->addCrumb($subjectData['EducationSubject']['name']);//,array('controller'=>'Attendance', 'action' => 'lesson', $classId, $selectedSubject,$date)
		$this->Navigation->addCrumb($this->Message->getLabel('general.edit'));
		$this->setVar('header', $className.'/'.$subjectData['EducationSubject']['name']);
	}

	// public function getAttendance($classId, $startDate, $endDate = NULL, $attendanceSession = NULL){
	// 	$options = array();
	// 	$options['joins'] = array(
	// 		array(
	// 			'table' => 'class_students',
	// 			'alias' => 'ClassStudent',
	// 			'type' => 'LEFT',
	// 			'conditions' => array(
	// 				'ClassStudent.class_id = '.$classId,
	// 				'ClassStudent.student_id = ClassAttendanceDay.student_id',
	// 			)
	// 		),
	// 		array(
	// 			'table' => 'student_attendance_types',
	// 			'alias' => 'StudentAttendanceType',
	// 			'type' => 'LEFT',
	// 			'conditions' => array('StudentAttendanceType.id = ClassAttendanceDay.student_attendance_type_id')
	// 		)
	// 	);
	// 	$options['recursive'] = -1;
	// 	$options['order'] = array('ClassAttendanceDay.student_id', 'ClassAttendanceDay.attendance_date ASC');
	// 	$options['fields'] = array('ClassAttendanceDay.*', 'StudentAttendanceType.short_form');
		
	// 	if(!empty($startDate) && !empty($endDate)){
	// 		$options['conditions'] = array('ClassAttendanceDay.attendance_date BETWEEN  ? AND ?' => array($startDate, $endDate));
	// 	} else {
	// 		$options['conditions'] = array('ClassAttendanceDay.attendance_date' => $startDate);
	// 	}
		
	// 	if(!empty($attendanceSession)) {
	// 		$options['conditions']['ClassAttendanceDay.session'] = $attendanceSession;
	// 	}
		
	// 	$data = $this->find('all', $options);
		
	// 	$newData = array();
	// 	for($i = 0; $i < count($data); $i ++){
	// 		$student_id = $data[$i]['ClassAttendanceDay']['student_id'];
	// 		$tempArr = $data[$i]['ClassAttendanceDay'];
	// 		$tempArr['short_form'] = $data[$i]['StudentAttendanceType']['short_form'];
	// 		$newData['ClassAttendanceDay'][$student_id][] = $tempArr;
	// 	}
	// 	return $newData;
	// }

	function arrayValueCounter($arr, $assocKey){
		$arr2=array(); 
		if(is_array(current($arr))){
			foreach($arr as $sArr){
				foreach($sArr as $key => $item){
					if($key == $assocKey){
						if(!isset($arr2[$item])){
							$arr2[$item]=1;
						}else{
							$arr2[$item]++;
						} 
					}
				}
			}
		}
		
		return $arr2;
	}
}
