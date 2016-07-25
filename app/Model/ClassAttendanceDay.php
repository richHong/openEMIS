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

class ClassAttendanceDay extends AppModel {
	public $useTable = 'student_attendance_days';

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
		$this->Navigation->addCrumb($this->Message->getLabel('Attendance.title'));
	}

	public function index($param1='', $param2='') {
		$className = $this->Session->read('SClass.data.name');
		$classId = $this->Session->read('SClass.id');

		$datepickerData = $this->controller->Utility->datepickerStartEndDate($param1);
		$configModel = ClassRegistry::init('ConfigItem');
		$attendanceSession = $configModel->getValue('student_attendance_session');
		
		$this->setVar('dateDiff', $datepickerData['dateDiff']);
		$this->setVar('numOfSegment', $attendanceSession);
		$this->setVar('startDate', $datepickerData['startDate']);
		$this->setVar('endDate', $datepickerData['endDate']);
		$this->setVar('className', $className);
		$this->setVar('classId', $classId);
		
		$attendanceType = $this->StudentAttendanceType->getAttendanceList('all', true);
		$this->setVar('attendanceType', $attendanceType);
		
		$data = $this->Student->ClassStudent->getStudentsByClass($classId);
		foreach ($data as $key => $val) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($val);
		}

		$this->setVar('data', $data);
		
		$attendanceData = $this->getAttendance($classId, $datepickerData['startDate'], $datepickerData['endDate']);
		$this->setVar('attendanceData', $attendanceData);
		
		if (empty($data)) {
			$this->Message->alert('general.view.notExists', array('type' => 'info'));	
		}
		$this->request->data[get_class($this)]['startDate'] = $datepickerData['startDate'];
		$this->request->data[get_class($this)]['endDate'] =  $datepickerData['endDate'];
	}


	public function edit($selectedDate='', $selectedSession=null) {
		$className = $this->Session->read('SClass.data.name');
		$classId = $this->Session->read('SClass.id');
		$this->set('className', $className);
		$this->set('classId', $classId);
		
		$this->setVar('selectedDate', $selectedDate);
		
		if($this->request->is(array('post', 'put'))){
			$postData = $this->request->data;
			if ($debug_msg = !empty($postData)) {
				$StudentAttendanceDay = ClassRegistry::init('StudentAttendanceDay');
				if($StudentAttendanceDay->saveAll($postData)){
					$this->Message->alert('general.add.success');
					return $this->redirect(array('action' => $this->alias));
				} else {
					$this->Message->alert('general.add.failed');
				}
			}
		}
		
		$attendanceType = $this->StudentAttendanceType->getAttendanceList();
		$this->setVar('attendanceType', $attendanceType);
		
		$attendanceTypeOptions = array();
		foreach($attendanceType as $item){
			$attendanceTypeOptions[$item['StudentAttendanceType']['id']] = $item['StudentAttendanceType']['short_form'];
		}
		$this->setVar('attendanceTypeOptions', $attendanceTypeOptions);
		
		$data = $this->Student->ClassStudent->getStudentsByClass($classId);
		foreach ($data as $key => $val) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($val);
		}
		$this->setVar('data', $data);
		
		/*
		$ClassTeacher = ClassRegistry::init('ClassTeacher');
		$teachersData = $ClassTeacher->getTeacherByClass($classId);
		$this->setVar('teachersData', $teachersData);
		*/
		if ($selectedSession == null) {
			$selectedSession = 1;
		}
		$attendanceSession = $selectedSession;
		$this->setVar('attendanceSession', $attendanceSession);
		
		$attendanceData = $this->getAttendance($classId, $selectedDate, NULL, $attendanceSession);
		if (array_key_exists('ClassAttendanceDay', $attendanceData)) {
			$attendanceData['StudentAttendanceDay'] = $attendanceData['ClassAttendanceDay'];
			unset($attendanceData['ClassAttendanceDay']);
		}
		
		$this->setVar('attendanceData', $attendanceData);
		
		switch(substr($attendanceSession,-1))
		{ 
			case 1: $attendanceSession.="st"; break;
			case 2: $attendanceSession.="nd"; break;
			case 3: $attendanceSession.="rd"; break;
			default: $attendanceSession.="th"; break;
		}
		$this->setVar('attendanceSessionSuffix', $attendanceSession);
	}

	public function getAttendance($classId, $startDate, $endDate = NULL, $attendanceSession = NULL){
		$options = array();
		$options['joins'] = array(
			array(
				'table' => 'class_students',
				'alias' => 'ClassStudent',
				'type' => 'LEFT',
				'conditions' => array(
					'ClassStudent.class_id = '.$classId,
					'ClassStudent.student_id = ClassAttendanceDay.student_id',
				)
			),
			array(
				'table' => 'student_attendance_types',
				'alias' => 'StudentAttendanceType',
				'type' => 'LEFT',
				'conditions' => array('StudentAttendanceType.id = ClassAttendanceDay.student_attendance_type_id')
			)
		);
		$options['recursive'] = -1;
		$options['order'] = array('ClassAttendanceDay.student_id', 'ClassAttendanceDay.attendance_date ASC');
		$options['fields'] = array('ClassAttendanceDay.*', 'StudentAttendanceType.short_form');
		
		if(!empty($startDate) && !empty($endDate)){
			$options['conditions'] = array('ClassAttendanceDay.attendance_date BETWEEN  ? AND ?' => array($startDate, $endDate));
		} else {
			$options['conditions'] = array('ClassAttendanceDay.attendance_date' => $startDate);
		}
		
		if(!empty($attendanceSession)) {
			$options['conditions']['ClassAttendanceDay.session'] = $attendanceSession;
		}
		
		$data = $this->find('all', $options);
		
		$newData = array();
		for($i = 0; $i < count($data); $i ++){
			$student_id = $data[$i]['ClassAttendanceDay']['student_id'];
			$tempArr = $data[$i]['ClassAttendanceDay'];
			$tempArr['short_form'] = $data[$i]['StudentAttendanceType']['short_form'];
			$newData['ClassAttendanceDay'][$student_id][] = $tempArr;
		}
		return $newData;
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
}
