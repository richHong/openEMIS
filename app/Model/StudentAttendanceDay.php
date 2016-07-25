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
class StudentAttendanceDay extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';
	
//Page 
	public $actsAs = array('ControllerAction','Export' => array('module' => 'Student'));

	public function day($controller, $params){
		$className = $controller->Session->read('Class.name');
		$classId = $controller->Session->read('Class.id');
		$selectedDate = !empty($params['pass'][0])?$params['pass'][0]:"";
		//$classId = $params['pass'][0];
		//$selectedDate = !empty($params['pass'][1])?$params['pass'][1]:"";
		
		$datepickerData = $controller->Utility->datepickerStartEndDate($selectedDate);
		
		$configModel = ClassRegistry::init('ConfigItem');
		$attendanceSession = $configModel->getValue('student_attendance_session');
		
		$controller->set('dateDiff', $datepickerData['dateDiff']);
		$controller->set('numOfSegment',$attendanceSession);
		$controller->set('startDate', $datepickerData['startDate']);
		$controller->set('endDate', $datepickerData['endDate']);
		$controller->set('className', $className);
		$controller->set('classId', $classId);
		
		$StudentAttendanceType = ClassRegistry::init('StudentAttendanceType');
		$attendanceType = $StudentAttendanceType->getAttendanceList('all', true);
		$controller->set('attendanceType', $attendanceType);
		
		$ClassStudent = ClassRegistry::init('ClassStudent');
		$data = $ClassStudent->getStudentsByClass($classId);
		$controller->set('data', $data);
		
		$ClassTeacher = ClassRegistry::init('ClassTeacher');
		$teachersData = $ClassTeacher->getTeacherByClass($classId);
		$controller->set('teachersData', $teachersData);
		//pr($classId);
		
		$attendanceData = $this->getAttendanceByClass($classId, $datepickerData['startDate'], $datepickerData['endDate']);
		$controller->set('attendanceData', $attendanceData);
		
		if(empty($data) ){
			$controller->Message->alert('general.view.notExists', array('type' => 'info'));	
		}
		
	/*	$controller->set('title', 'Students');
		$controller->set('contentHeader', $className);
		$controller->set('model', 'StudentAttendanceDay');*/
		//Breadcrumbs
		$controller->Navigation->addCrumb($this->Message->getLabel('class.dailyAttendance'), array('controller'=>'Attendance', 'action' => 'index', 1));
		$controller->Navigation->addCrumb($className);
		$controller->set('header', $className);
		
		$controller->request->data['SClass']['startDate'] = $datepickerData['startDate'];
		$controller->request->data['SClass']['endDate'] =  $datepickerData['endDate'];
	}
	
	public function day_edit($controller, $params){
		$className = $controller->Session->read('Class.name');
		$classId = $params['pass'][0];
		$controller->set('className', $className);
		$controller->set('classId', $classId);
		
		
		$date = empty($params['pass'][2])? date('Y-m-d'):$params['pass'][2];
		$controller->set('selectedDate', $date);
		
		if($controller->request->is('post')){
			$postData = $controller->request->data;
			
			if($this->saveAll($postData)){
				return $controller->redirect(array('action' => 'attendance_class', $date));
			}
		}
		
		$StudentAttendanceType = ClassRegistry::init('StudentAttendanceType');
		$attendanceType = $StudentAttendanceType->getAttendanceList();
		$controller->set('attendanceType', $attendanceType);
		
		$attendanceTypeOptions = array();
		foreach($attendanceType as $item){
			$attendanceTypeOptions[$item['StudentAttendanceType']['id']] = $item['StudentAttendanceType']['short_form'];
		}
		$controller->set('attendanceTypeOptions', $attendanceTypeOptions);
		
		$ClassStudent = ClassRegistry::init('ClassStudent');
		$data = $ClassStudent->getStudentsByClass($classId);
		$controller->set('data', $data);
		
		$ClassTeacher = ClassRegistry::init('ClassTeacher');
		$teachersData = $ClassTeacher->getTeacherByClass($classId);
		$controller->set('teachersData', $teachersData);
		
		$attendanceSession = empty($params['pass'][1])? 1:$params['pass'][1];
		$controller->set('attendanceSession', $attendanceSession);
		
		$attendanceData = $this->getAttendanceByClass($classId, $date, NULL,$attendanceSession);
		$controller->set('attendanceData', $attendanceData);
		
		
		switch(substr($attendanceSession,-1))
		{ 
			case 1: $attendanceSession.="st"; break;
			case 2: $attendanceSession.="nd"; break;
			case 3: $attendanceSession.="rd"; break;
			default: $attendanceSession.="th"; break;
		}
		$controller->set('attendanceSessionSuffix', $attendanceSession);
		
		//Breadcrumbs
		$controller->Navigation->addCrumb($this->Message->getLabel('general.attendance'));
		
		$controller->set('header', $className);
	}

	public function beforeAction() {
		parent::beforeAction();

		$this->setVar('contentHeader', $this->Message->getLabel('Student.title'));
		$this->setVar('tabHeader', $this->Message->getLabel('StudentAttendanceDay.title'));
		$this->Navigation->addCrumb($this->Message->getLabel('Attendance.title'));
	}

	public function index($param1='', $param2='') {
		$studentId = $this->Session->read('Student.id');
		if(empty($studentId)){
			return $this->redirect(array('action'=> 'index'));
		}

		$data = $this->controller->Student->find('first', array('recursive' => 0, 'conditions' => array('Student.id' => $studentId)));
		
		$StudentAttendanceType = ClassRegistry::init('StudentAttendanceType');
		$attendanceType = $StudentAttendanceType->getAttendanceList();
		
		$this->setVar('attendanceType', $attendanceType);
		$this->setVar('data', $data);
		
		$filterBy = "";
		$selectedDate ="";
		
		if($param2 == ''){
			$selectedDate = $param1;
		}
		else if($param2 != ''){
			$filterBy = $param1;
			$selectedDate = $param2;
		}
		
		$datepickerData = $this->controller->Utility->datepickerStartEndDate($selectedDate);
		$configModel = ClassRegistry::init('ConfigItem');
		$attendanceSession = $configModel->getValue('student_attendance_session');
	
		$options = array(
			'startDate' => $datepickerData['startDate'],
			'endDate' => $datepickerData['endDate'],
			'filterBy' => $filterBy,
			'attendanceSession' => $attendanceSession
		);

		$attendancesList = $this->getListData($options);
	
		if(empty($attendancesList) ){
			$this->Message->alert('general.view.notExists', array('type' => 'info'));	
		}
		
		$attendanceTypeOptions = array();
		foreach($attendanceType as $item){
			$attendanceTypeOptions[$item['StudentAttendanceType']['id']] = $item['StudentAttendanceType']['name'];
		}
		
		$this->setVar('dateDiff', $datepickerData['dateDiff']);
		$this->setVar('isEdit', false);
		$this->setVar('attendanceTypeOptions', $attendanceTypeOptions);
		$this->setVar('attendancesList', $attendancesList);
		$this->setVar('numOfSegment', $attendanceSession);
		$this->setVar('attendanceSession', $attendanceSession);
		$this->setVar('selectedAttendanceType', $filterBy);
		$this->setVar('startDate', $datepickerData['startDate']);
		$this->setVar('endDate', $datepickerData['endDate']);
		$this->setVar('className', '$className');
		
		$this->request->data[get_class($this)]['startDate'] = $datepickerData['startDate'];
		$this->request->data[get_class($this)]['endDate'] =  $datepickerData['endDate'];
	}

	public function getStudentAttendanceByDate($studentId, $startDate, $endDate = '') {
		$sqlFields = array(
			'fields' => array(
					'StudentAttendanceDay.student_attendance_type_id',
					'StudentAttendanceType.name',
					'COUNT(StudentAttendanceDay.student_attendance_type_id) as count'
			),
			'joins' => 
				array(
					array('table' => 'student_attendance_types',
						'alias' => 'StudentAttendanceType',
						'type' => 'LEFT',
						'conditions' => array(
							'StudentAttendanceType.id = StudentAttendanceDay.student_attendance_type_id'
					)
				)
			),
			'conditions' => array(
					'StudentAttendanceDay.attendance_date >=' => $startDate, 
					'StudentAttendanceDay.attendance_date <=' => $endDate,
					'StudentAttendanceDay.student_id = ' => $studentId
					),
			'group' => 'StudentAttendanceDay.student_attendance_type_id'
		);
		$attendanceData = $this->find('all', $sqlFields);
		
		return $attendanceData;
	}

	public function reportGetFieldNames() {
		$fieldNames = $this->getFieldNamesFromData($this->reportData);
		return $fieldNames;
	}

	public function reportGetData() {
		$configModel = ClassRegistry::init('ConfigItem');
		$attendanceSession = $configModel->getValue('student_attendance_session');
		$options = array(
			'attendanceSession' => $attendanceSession
		);
		$rawdata = $this->getListData($options);

		// need to massage the data
		$data = array();
		foreach ($rawdata as $key => $value) {
			foreach ($rawdata[$key]['session'] as $key2 => $value2) {
				$tArray = $value;
				$tArray['session'] = $value2;
				$tArray['session']['date'] = $tArray['attendance_date'];
				unset($tArray['session']['id']);
				unset($tArray['attendance_date']);
				array_push($data, $tArray);
			}
		}

		$this->reportData = $data;
		return $this->reportData;
	}

	public function getListData($options=array()) {
		$studentId = $this->Session->read('Student.id');
		(array_key_exists('startDate', $options))? $startDate = $options['startDate']: $startDate='';
		(array_key_exists('endDate', $options))? $endDate = $options['endDate']: $endDate='';
		(array_key_exists('filterBy', $options))? $filterBy = $options['filterBy']: $filterBy='';
		(array_key_exists('attendanceSession', $options))? $attendanceSession = $options['attendanceSession']: $attendanceSession='';
		return $this->getSingleDayAttendance($studentId,$startDate,$endDate, $filterBy, $attendanceSession);
	}

	public function getAppendDateData($data){
		$results = array();
		
		$date = $data['StudentAttendanceDay']['attendance_date'];
		foreach($data as $key=>$value){
			 if (is_numeric ($key)) {
				 $tempData = $value;
				 $tempData['StudentAttendanceDay']['attendance_date'] = $date;
				 
				 array_push($results, $tempData);
			 }
		}
		
		return $results;
	}

	public function getAttendanceByMonth($year,$month,$options=array()) {
		$studentAttendanceTypeConditions = array('StudentAttendanceType.id = StudentAttendanceDay.student_attendance_type_id');
		// if (array_key_exists('getPresentOnly', $options)) {
		// 	if ($options['getPresentOnly']) {
		// 		$studentAttendanceTypeConditions['StudentAttendanceType.id'] = 3;
		// 	}
		// }

		$data = $this->find(
			'all',
			array(
				'fields' => array(
					'StudentAttendanceDay.attendance_date','COUNT(DISTINCT StudentAttendanceDay.student_id) as count', 'StudentAttendanceType.id', 'StudentAttendanceType.name'
					),
				'joins' => array(
					array('table' => 'student_attendance_types',
						'alias' => 'StudentAttendanceType',
						'conditions' => $studentAttendanceTypeConditions,
						'order' => 'order asc'
					)
				),
				'group' => 'StudentAttendanceDay.attendance_date, StudentAttendanceDay.student_attendance_type_id',
				'conditions' => array(
					'MONTH(attendance_date)' => $month,
					'YEAR(attendance_date)' => $year
				),

			)
		);
		return $data;
	}
	
	public function getAttendanceByClass($classId, $startDate, $endDate = NULL, $attendanceSession = NULL){
		$options['joins'] = array(
			array('table' => 'class_students',
				'alias' => 'ClassStudent',
				'type' => 'LEFT',
				'conditions' => array(
					'ClassStudent.class_id = '.$classId,
					'ClassStudent.student_id = StudentAttendanceDay.student_id',
				)
			),
			array('table' => 'student_attendance_types',
				'alias' => 'StudentAttendanceType',
				'type' => 'LEFT',
				'conditions' => array(
					'StudentAttendanceType.id = StudentAttendanceDay.student_attendance_type_id'
				)
			),
		);
		$options['recursive'] = -1;
		$options['order'] = array('StudentAttendanceDay.student_id ', 'StudentAttendanceDay.attendance_date ASC');
		$options['fields'] = array('StudentAttendanceDay.*', 'StudentAttendanceType.short_form');
		
		if(!empty($startDate) && !empty($endDate)){
			$options['conditions'] = array('StudentAttendanceDay.attendance_date BETWEEN  ? AND ?' => array($startDate, $endDate));
		}
		else{
			$options['conditions'] = array('StudentAttendanceDay.attendance_date' => $startDate);
		}
		
		if(!empty( $attendanceSession)){
			array_push($options['conditions'], array('StudentAttendanceDay.session' => $attendanceSession));
		}
		
		$data = $this->find('all', $options);
		
		$newData = array();
		for($i = 0; $i < count($data); $i ++){
			$student_id = $data[$i]['StudentAttendanceDay']['student_id'];
			$tempArr = $data[$i]['StudentAttendanceDay'];
			$tempArr['short_form'] = $data[$i]['StudentAttendanceType']['short_form'];
			$newData['StudentAttendanceDay'][$student_id][] = $tempArr;
		}
		return $newData;
	}
	
	public function getSingleDayAttendance($id, $startDate, $endDate, $filterBy = '', $session = 1){

		$options['conditions'] = array(
			'StudentAttendanceDay.student_id' => $id,
		);

		$andArray = array();
		if (isset($startDate) && $startDate!='') {
			array_push($andArray, array('StudentAttendanceDay.attendance_date >=' => $startDate));
		}
		if (isset($endDate) && $endDate!='') {
			array_push($andArray, array('StudentAttendanceDay.attendance_date <=' => $endDate));
		}
		if (!empty($andArray)) {
			$options['conditions']['AND'] = $andArray;
		}

		if(!empty($filterBy)){
			$options['conditions']['student_attendance_type_id'] = $filterBy;	
		}
		
		$options['joins'] = array(
			array('table' => 'student_attendance_types',
				'alias' => 'StudentAttendanceType',
				'type' => 'LEFT',
				'conditions' => array(
					'StudentAttendanceType.id = StudentAttendanceDay.student_attendance_type_id'
				)
			)
		);
		
		$options['recursive'] = -1;
		$options['fields'] = array('StudentAttendanceDay.*','StudentAttendanceType.short_form');
		$options['order'] = array('StudentAttendanceDay.attendance_date ASC', 'StudentAttendanceDay.session ASC');
		
		$data = $this->find('all', $options);

		$newData = array();
		$selectedDate = '';
		foreach($data as $attendance){
			$attendance_date = $attendance['StudentAttendanceDay']['attendance_date'];
			if($selectedDate != $attendance_date){
				$newData[] = array('attendance_date'=> $attendance_date);
				$selectedDate = $attendance_date;
			}
		}
		
		foreach($newData as $key => $newAttendance){
			foreach($data as $attendance){
				if($newAttendance['attendance_date'] == $attendance['StudentAttendanceDay']['attendance_date']){
					$subTempArr = array();
					$subTempArr['id'] = $attendance['StudentAttendanceDay']['id'];
					$subTempArr['remarks'] = $attendance['StudentAttendanceDay']['remarks'];
					$subTempArr['short_form'] = $attendance['StudentAttendanceType']['short_form'];
					$subTempArr['session'] = $attendance['StudentAttendanceDay']['session'];		
					
					$newAttendance['session'][] = $subTempArr;
					$newData[$key] = $newAttendance;
				}
			}
		}

		return $newData;
	}
}

?>