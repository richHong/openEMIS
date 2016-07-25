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
/**
 * Attendance Model
 *
 * @property Class $Class
 */
class StudentAttendanceLesson extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

	//Page
	public $actsAs = array('ControllerAction');
	
	// was lesson
	public function lesson(){
		$className = $this->Session->read('Class.name');
		$classId = $this->Session->read('Class.id');
		
		$ClassSubject = ClassRegistry::init('ClassSubject');
		$subjectsOptions = $ClassSubject->getSubjectByClass($classId,'list');
		
		if(!empty($subjectsOptions)){//pr('No Subject');die;
			//$controller->redirect(array('action' => 'index'));
                    
                    $selectedSubject = empty($subjectsOptions)? 0: key($subjectsOptions);
                    $selectedSubject = empty($params['pass'][0])? $selectedSubject: $params['pass'][0];
                    
                    $selectedDate = !empty($params['pass'][1])?$params['pass'][1]:"";
		}
		else{
                    $selectedSubject = 0;
                    $selectedDate = !empty($params['pass'][0])?$params['pass'][0]:"";
                }
		
		$datepickerData = $this->controller->Utility->datepickerStartEndDate($selectedDate);
		$startDate = $datepickerData['startDate'];
		$endDate = $datepickerData['endDate'];
		$dateDiff = $datepickerData['dateDiff']; 
                
                if(!empty($subjectsOptions) && count($params['pass']) != 2){
                    $controller->redirect(array('action' => 'attendance_class', key($subjectsOptions), $startDate));
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
		$this->setVar('data', $data);
		
		$teachersData = $ClassLesson->getTeachersList($classId, $selectedSubject);
		$this->setVar('teachersData', $teachersData);
		
		$attendanceData = $this->getAttendanceByClass($classId, $selectedSubject, $startDate, $endDate);
		$this->setVar('attendanceData', $attendanceData);
		//pr($selectedSubject);
		
		if(empty($period) || empty($data) ){
			$controller->Message->alert('general.view.notExists', array('type' => 'info'));	
		}
	
		//Breadcrumb
		//$this->Navigation->addCrumb($subjectsOptions[$selectedSubject]);
                $header = $className;
                if(isset($subjectsOptions[$selectedSubject])){
                    $header .= ' / '.$subjectsOptions[$selectedSubject];
                }
                
		$this->setVar('header', $header);
		
		$controller->request->data['SClass']['startDate'] = $datepickerData['startDate'];
		$controller->request->data['SClass']['endDate'] =  $datepickerData['endDate'];
		$controller->request->data['SubjectList'] =  $selectedSubject;
	}
	
	public function lesson_edit($controller, $params){
		$classId = $params['pass'][0];
		$className = $this->Session->read('Class.name');
		
		$selectedSubject = $params['pass'][1];
		$this->setVar('selectedSubject', $selectedSubject);
		
		$date = empty($params['pass'][2])? date('Y-m-d'):date('Y-m-d', $params['pass'][2]);
		$this->setVar('selectedDate', $date);
		
		if($controller->request->is('post')){
			$postData = $controller->request->data;
			//pr(	$postData);
			/*foreach($postData as $key =>$attendance){
				if(empty($attendance['StudentAttendanceLesson']['class_lesson_id'])){
					unset($postData[$key]['StudentAttendanceLesson']['class_lesson_id']);
				}
			}*/
			//$firstObj = $postData;
			
			if($this->saveAll($postData)){
				return $controller->redirect(array('action' => 'attendance_class',  $selectedSubject, $date));
			}
			else{
				$controller->Message->alert('general.edit.failed', array('type' => 'error'));	
			}
		}
		
		$controller->EducationSubject->recursive = -1;
		$subjectData = $controller->EducationSubject->findById($selectedSubject, array('name', 'code'));
		
		
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
		$this->setVar('data', $data);
		
		$ClassLesson = ClassRegistry::init('ClassLesson');
		$teachersData = $ClassLesson->getTeachersList($classId, $selectedSubject);
		$this->setVar('teachersData', $teachersData);
		
		//Getting lesson info
		$selectedLesson =  $ClassLesson->getSelectedLessonPeriod($classId, $selectedSubject, date('Y-m-d H:i:s', $params['pass'][2]));
		//pr($selectedLesson);
		
		$classLessonId = empty($selectedLesson['ClassLesson']['id'])? 0:$selectedLesson['ClassLesson']['id'];
		$this->setVar('classLessonId', $classLessonId);
		
		$startTime = date('H:i a', strtotime($selectedLesson['ClassLesson']['start_time']));
		$endTime = date('H:i a', strtotime($selectedLesson['ClassLesson']['end_time']));
		$this->setVar('attendanceTimeSlot', $startTime. " - ".$endTime);
		
		$attendanceData = $this->getAttendanceByClassLessonId($classLessonId);
		$this->setVar('attendanceData', $attendanceData);
		
		
		//Breadcrumb
		$this->Navigation->addCrumb($className, array('controller'=>'Attendance', 'action' => 'index', 2, $classId, $selectedSubject));
		$this->Navigation->addCrumb($subjectData['EducationSubject']['name'],array('controller'=>'Attendance', 'action' => 'lesson', $classId, $selectedSubject,$date));
		$this->Navigation->addCrumb($this->Message->getLabel('general.edit'));
		$this->setVar('header', $className.'/'.$subjectData['EducationSubject']['name']);
		
	}
	
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
	
	// lesson_view - yes this is correct
	public function index($param1='', $param2='', $param3=''){
		$this->Navigation->addCrumb($this->Message->getLabel('general.attendance'));

		$studentId = $this->Session->read('Student.id');
		// $classId = $this->Session->read('Class.id');
		
		if(empty($studentId)){
			return $this->redirect(array('controller'=>'Students', 'action' => 'index'));
		}
		
		$filterBySubject = "";
		$filterByType = "";
		$selectedDate ="";
		$header ='';

		if($param3==''){
			$selectedDate = $param1;
			//$selectedDate = $params['pass'][1];
		}
		else if($param1!='' && $param2!='' && $param3!=''){
			$filterBySubject = $param1;
			$filterByType = $param2;
			$selectedDate = $param3;
		}

		$datepickerData = $this->controller->Utility->datepickerStartEndDate($selectedDate, 14);
		
		$StudentAttendanceType = ClassRegistry::init('StudentAttendanceType');
		$attendanceType = $StudentAttendanceType->getAttendanceList();
		$attendanceTypeOptions = array();
		foreach($attendanceType as $item){
			$attendanceTypeOptions[$item['StudentAttendanceType']['id']] = $item['StudentAttendanceType']['name'];
		}
		
		$filterByType = (empty($filterByType))? key($attendanceTypeOptions):$filterByType ;
		
		$ClassSubject = ClassRegistry::init('ClassSubject');
		$classSubjectsList = $ClassSubject->getSubjectByStudentId($studentId);
		$subjectsOptions = array();
		foreach($classSubjectsList as $item){
			$subjectsOptions[$item['ClassSubject']['education_grade_subject_id']] = $item['EducationSubject']['code']." - ".$item['EducationSubject']['name'];
		}
		
		$selectedGradeSubjectId = !empty($filterBySubject)? $filterBySubject : key($subjectsOptions); 
		$selectedGradeSubjectId = !empty($selectedGradeSubjectId)? $selectedGradeSubjectId : 0;
		
		$attendancesList = array();
		
		$data = $this->controller->Student->find('first', array('recursive' => 0, 'conditions' => array('Student.id' => $studentId)));
		$header = $data['SecurityUser']['first_name'].' '.$data['SecurityUser']['last_name'].' ('.$data['SecurityUser']['openemisid'].')';
		
		$attendancesList = $this->getAttendanceByStudentId($studentId, $selectedGradeSubjectId, $datepickerData['startDate'],$datepickerData['endDate'],$filterByType);
		
		if(empty($attendancesList) ){
			$this->Message->alert('general.view.notExists', array('type' => 'info'));	
		}
		
		$this->setVar('attendanceType', $this->controller->Utility->getSetupOptionsData($attendanceType));
		$this->setVar('data', $data);
		$this->setVar('attendancesList', $attendancesList);
		$this->setVar('attendanceTypeOptions', $this->controller->Utility->getSetupOptionsData($attendanceTypeOptions));
		$this->setVar('subjectsOptions', $this->controller->Utility->getSetupOptionsData($subjectsOptions));
		$this->setVar('selectedGradeSubject', $selectedGradeSubjectId);
		$this->setVar('isEdit', true);
		$this->setVar('selectedAttendanceType', $filterByType);
		$this->setVar('startDate', $datepickerData['startDate']);
		$this->setVar('endDate', $datepickerData['endDate']);
		$this->setVar('tabHeader', $this->Message->getLabel('Attendance.title'));
		
		$this->request->data['StudentAttendanceLesson']['startDate'] = $datepickerData['startDate'];
		$this->request->data['StudentAttendanceLesson']['endDate'] =  $datepickerData['endDate'];
		$this->request->data['SubjectList'] =  $filterBySubject;
		$this->request->data['StudentAttendanceType'] =  $filterByType;
	}
	
	public function setupDataBeforeSave($data, $classLessonId = NULL){
		unset($data['StudentAttendanceLesson']);
		
		for($i = 0; $i < count($data); $i ++){
			$data[$i]['StudentAttendanceLesson']['class_lesson_id'] = 	$classLessonId;
		}
		
		return $data;
	}
	
	public function getAttendanceByClass($classId, $educationSubjectId, $startDate, $endDate = NULL){
		$options['joins'] = array(
			array('table' => 'class_lessons',
				'alias' => 'ClassLesson',
				'type' => 'LEFT',
				'conditions' => array(
					'ClassLesson.id = StudentAttendanceLesson.class_lesson_id',
				)
			),
			array('table' => 'education_grades_subjects',
				'alias' => 'EducationGradeSubject',
				'type' => 'LEFT',
				'conditions' => array(
					'EducationGradeSubject.id = ClassLesson.education_grade_subject_id',
				)
			),
			array('table' => 'class_students',
				'alias' => 'ClassStudent',
				'type' => 'LEFT',
				'conditions' => array(
					'ClassStudent.class_id = '.$classId,
					'ClassStudent.student_id = StudentAttendanceLesson.student_id',
				)
			),
			array('table' => 'student_attendance_types',
				'alias' => 'StudentAttendanceType',
				'type' => 'LEFT',
				'conditions' => array(
					'StudentAttendanceType.id = StudentAttendanceLesson.student_attendance_type_id'
				)
			),
		);
		
		$options['conditions'] = array(
			'ClassLesson.class_id = '.$classId,
			'EducationGradeSubject.education_subject_id = '.$educationSubjectId,
		);
		
		if(!empty($startDate) && !empty($endDate)){
			$options['conditions']['ClassLesson.start_time >='] = $startDate;
			$options['conditions']['ClassLesson.start_time <='] = $endDate;
		}
		else{
			$options['conditions']['ClassLesson.start_time'] = $startDate;
		}
		
		$options['recursive'] = -1;
		$options['fields'] = array('StudentAttendanceLesson.*', 'StudentAttendanceType.short_form','ClassLesson.start_time');
		$options['order'] = array('StudentAttendanceLesson.student_id ', 'ClassLesson.start_time ASC');
		$data = $this->find('all', $options);
		//pr($data);die;
		$newData = array();
		for($i = 0; $i < count($data); $i ++){
			$student_id = $data[$i]['StudentAttendanceLesson']['student_id'];
			//$tempArr = array();
			$tempArr = $data[$i]['StudentAttendanceLesson'];
			$tempArr['short_form'] = $data[$i]['StudentAttendanceType']['short_form'];
			$tempArr['datetime'] = $data[$i]['ClassLesson']['start_time'];
			
			$newData['StudentAttendanceLesson'][$student_id][] = $tempArr;
		}
		return $newData;
	}
	
	public function getAttendanceByClassLessonId($classLessonId){
		$options['joins'] = array(
			// array('table' => 'class_students',
			// 	'alias' => 'ClassStudent',
			// 	'type' => 'LEFT',
			// 	'conditions' => array(
			// 		'ClassStudent.student_id = StudentAttendanceLesson.student_id',
			// 	)
			// ),
			array('table' => 'student_attendance_types',
				'alias' => 'StudentAttendanceType',
				'type' => 'LEFT',
				'conditions' => array(
					'StudentAttendanceType.id = StudentAttendanceLesson.student_attendance_type_id'
				)
			),
		);
		
		$options['conditions'] = array(
			'StudentAttendanceLesson.class_lesson_id = '.$classLessonId
		);
		
		$options['recursive'] = -1;
		$options['fields'] = array('StudentAttendanceLesson.*', 'StudentAttendanceType.short_form');
		$options['fields'] = array('StudentAttendanceLesson.*', 'StudentAttendanceType.*');//, 'ClassStudent.*'
	//	$options['order'] = array('StudentAttendanceLesson.student_id ', 'ClassLesson.start_time ASC');
		$data = $this->find('all', $options);
		
		$newData = array();
		for($i = 0; $i < count($data); $i ++){
			$student_id = $data[$i]['StudentAttendanceLesson']['student_id'];
			//$tempArr = array();
			$tempArr = $data[$i]['StudentAttendanceLesson'];
			$tempArr['short_form'] = $data[$i]['StudentAttendanceType']['short_form'];
			//$tempArr['datetime'] = $data[$i]['ClassLesson']['start_time'];
			
			$newData['StudentAttendanceLesson'][$student_id][] = $tempArr;
		}
		return $newData;
	}
	
	
	public function getAttendanceByStudentId($id, $educationGradeSubjectId, $startDate, $endDate = NULL, $filterBy = ''){
		$options['joins'] = array(
			array('table' => 'class_lessons',
				'alias' => 'ClassLesson',
				'type' => 'LEFT',
				'conditions' => array(
					'ClassLesson.id = StudentAttendanceLesson.class_lesson_id',
				)
			),
			array('table' => 'student_attendance_types',
				'alias' => 'StudentAttendanceType',
				'type' => 'LEFT',
				'conditions' => array(
					'StudentAttendanceType.id = StudentAttendanceLesson.student_attendance_type_id'
				)
			),
		);
		
		$options['conditions'] = array(
			'StudentAttendanceLesson.student_id = '.$id,
			'ClassLesson.education_grade_subject_id = '.$educationGradeSubjectId,
		);
		
		if(!empty($startDate) && !empty($endDate)){
			$options['conditions']['ClassLesson.start_time >='] = $startDate;
			$options['conditions']['ClassLesson.start_time <='] = $endDate;
		}
		else{
			$options['conditions']['ClassLesson.start_time'] = $startDate;
		}
		if(!empty($filterBy)){
			$options['conditions']['student_attendance_type_id'] = $filterBy;	
		}
		
		
		$options['recursive'] = -1;
		$options['fields'] = array('StudentAttendanceLesson.*', 'StudentAttendanceType.short_form','ClassLesson.start_time','ClassLesson.end_time');
		$options['order'] = array('StudentAttendanceLesson.student_id ', 'ClassLesson.start_time ASC');
		$data = $this->find('all', $options);
		
		$newData = array();
		for($i = 0; $i < count($data); $i ++){
			$student_id = $data[$i]['StudentAttendanceLesson']['student_id'];
			//$tempArr = array();
			$tempArr = $data[$i]['StudentAttendanceLesson'];
			$tempArr['short_form'] = $data[$i]['StudentAttendanceType']['short_form'];
			$tempArr['start_time'] = $data[$i]['ClassLesson']['start_time'];
			$tempArr['end_time'] = $data[$i]['ClassLesson']['end_time'];
			
			$newData[] = $tempArr;
		}
		return $newData;
	}

	public function getAttendanceByMonth($year,$month,$options=array()) {
		$studentAttendanceTypeConditions = array('StudentAttendanceType.id = StudentAttendanceLesson.student_attendance_type_id');
		// if (array_key_exists('getPresentOnly', $options)) {
		// 	if ($options['getPresentOnly']) {
		// 		$studentAttendanceTypeConditions['StudentAttendanceType.id'] = 3;
		// 	}
		// }

		$data = $this->find(
			'all',
			array(
				'recursive' => -1,
				'fields' => array(
					'ClassLesson.start_time AS attendance_date','COUNT(DISTINCT StudentAttendanceLesson.student_id) as count', 'StudentAttendanceType.id', 'StudentAttendanceType.name'
				),
				'joins' => array(
					array('table' => 'student_attendance_types',
						'alias' => 'StudentAttendanceType',
						'conditions' => $studentAttendanceTypeConditions
					),
					array('table' => 'class_lessons',
						'alias' => 'ClassLesson',
						'conditions' => array(
							'ClassLesson.id = StudentAttendanceLesson.class_lesson_id'
						)
					)
				),
				'group' => 'ClassLesson.start_time'
			)
		);
		return $data;
	}
	
}

?>