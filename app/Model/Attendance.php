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
class Attendance extends AppModel {
 	public $useTable = false;

    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'subjectId' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('subject')
                )
            ),
            'classId' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('class')
                )
            ),
            'name' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('name')
                )
            )
        );
    }
	
	public function processAction($controller, $action, $name) {
	//	pr($action);
		$controller->autoRender = false;
		call_user_func_array(array($this, $action), array($controller, $controller->params));
		
		$exclude = array('attendance_view','attendance_class_edit','attendance_class');		
		if(!in_array($action, $exclude)) {
			$ctp = substr($action, strlen($name)+1);
			$controller->render($name . '/' . $ctp);
		}
		
		$controller->response->send();
		$controller->_stop();
	}
	
	public function attendance_class($controller, $params){
		$configModel = ClassRegistry::init('ConfigItem');
		$attendanceView = $configModel->getValue('attendance_view');
		
		if($params['controller'] == 'Classes'){
			if($attendanceView == 'lesson') {
				$StudentAttendanceLesson = ClassRegistry::init('StudentAttendanceLesson');
				$StudentAttendanceLesson->lesson($controller, $params);
				$controller->render('../Attendance/lesson/index');
			} else { // day
				$StudentAttendanceDay = ClassRegistry::init('StudentAttendanceDay');
				$StudentAttendanceDay->day($controller, $params);
				$controller->render('../Attendance/day/index');
				
			}
		}
	}
	
	
	public function attendance_view($controller, $params){
		$configModel = ClassRegistry::init('ConfigItem');
		$attendanceView = $configModel->getValue('attendance_view');
		
		if($params['controller'] == 'Students'){
			if($attendanceView == 'lesson') {
				$StudentAttendanceLesson = ClassRegistry::init('StudentAttendanceLesson');
				$StudentAttendanceLesson->lesson_view($controller, $params);
				$controller->render('../Attendance/lesson/students/view');
			} else { // day
				$StudentAttendanceDay = ClassRegistry::init('StudentAttendanceDay');
				$StudentAttendanceDay->day_view($controller, $params);
				$controller->render('../Attendance/day/students/view');
			}
		}
		else if($params['controller'] == 'Staff'){
			$StaffAttendanceDay = ClassRegistry::init('StaffAttendanceDay');
			$StaffAttendanceDay->attendance_view($controller, $params);
			$controller->render('../Attendance/staff/view');
		}
	}
	
	public function attendance_class_edit($controller, $params){
		$configModel = ClassRegistry::init('ConfigItem');
		$attendanceView = $configModel->getValue('attendance_view');
		
		if($params['controller'] == 'Classes'){
			if($attendanceView == 'lesson') {
				$StudentAttendanceLesson = ClassRegistry::init('StudentAttendanceLesson');
				$StudentAttendanceLesson->lesson_edit($controller, $params);
				$controller->render('../Attendance/lesson/edit');
			} else { // day
				$StudentAttendanceDay = ClassRegistry::init('StudentAttendanceDay');
				$StudentAttendanceDay->day_edit($controller, $params);
				$controller->render('../Attendance/day/edit');
			}
		}
	}
}

?>