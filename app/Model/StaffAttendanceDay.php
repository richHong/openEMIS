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
class StaffAttendanceDay extends AppModel {
	public $actsAs = array('ControllerAction');
	
	public $accessMapping = array(
		'staff_list' => 'read',
		'staff_edit' => 'update'
	);

	public function index() {
		return $this->redirect(array('action' => $this->alias,'staff_list'));
	}
	
	public function staff_list(){
		if ($this->Session->check('Security.accessViewType')) {
			$accessViewType = $this->Session->read('Security.accessViewType');
		}
		ini_set('memory_limit', '-1');
		
		$this->controller->Navigation->resetCrumbs();
		$header = $this->Message->getLabel('general.attendance');
		$this->controller->Navigation->addCrumb($this->Message->getLabel('staff.title'), array('controller' => 'Staff', 'action' => 'index'));
		$this->controller->Navigation->addCrumb($header);
		$this->controller->set('contentHeader', $this->Message->getLabel('Staff.title'));
		$this->controller->set('tabHeader', $this->Message->getLabel('general.attendance'));
		$this->controller->set('model', 'StaffAttendanceDay');
		
		$selectedDate = !empty($this->controller->params['pass'][1])?$this->controller->params['pass'][1]:"";

		$datepickerData = $this->controller->Utility->datepickerStartEndDate($selectedDate);
		
		$startDate = $datepickerData['startDate'];
		$endDate = $datepickerData['endDate'];
		$dateDiff = $datepickerData['dateDiff'];
		
		$StaffModel =  ClassRegistry::init('Staff');
		$options = array();
		if ($accessViewType == 2) {
			$options['conditions'] = array('Staff.security_user_id' => AuthComponent::user('id'));
		}
		$data = $StaffModel->getStaffList('all',$options);
		foreach ($data as $key => $val) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($val);
		}

		$attendanceType = ClassRegistry::init('StaffAttendanceType')->getAttendanceList('all', true);
		$this->controller->set('attendanceType', $attendanceType);
		$this->controller->set('data', $data);
		
		$attendanceData = $this->getStaffsAttendance($startDate, $endDate);
		$this->controller->set('attendanceData', $attendanceData);

		$this->controller->set('dateDiff', $dateDiff); 
		$this->controller->set('startDate', $startDate);
		$this->controller->set('endDate', $endDate);
		
		$this->controller->request->data[$this->name]['startDate'] = $startDate;
		$this->controller->request->data[$this->name]['endDate'] = $endDate;

		$this->controller->set('portletHeader', $this->Message->getLabel('StaffAttendanceDay.staffAttendance'));
	}
	
	public function staff_edit(){
		ini_set('memory_limit', '-1');
		$selectedDate = !empty($this->controller->params['pass'][1])?$this->controller->params['pass'][1]:"";
		$this->controller->set('selectedDate', $selectedDate);
		
		if(empty($selectedDate)){
			return $this->controller->redirect(array('action'=> 'staff'));
		}

		$header = $this->Message->getLabel('general.attendance');
		$this->controller->set('contentHeader', $this->Message->getLabel('Staff.title'));
		$this->controller->set('tabHeader', $this->Message->getLabel('general.attendance'));
		$this->controller->set('model', 'StaffAttendanceDay');
		$this->controller->set('title', $this->Message->getLabel('staff.title'));
		
		$this->controller->Navigation->resetCrumbs();
		$this->controller->Navigation->addCrumb($this->Message->getLabel('staff.title'), array('controller' => 'Staff', 'action' => 'index'));
		$this->controller->Navigation->addCrumb($this->Message->getLabel('general.attendance'), array('controller'=>'Staff/StaffAttendanceDay', 'action' => 'staff_list'));
		$this->controller->Navigation->addCrumb($selectedDate);
		
		$StaffModel =  ClassRegistry::init('Staff');
		
		$data = $StaffModel->getStaffList('all');
		foreach ($data as $key => $val) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($val);
		}
		$this->controller->set('data', $data);

		$staffAttendanceType = ClassRegistry::init('StaffAttendanceType')->getOptions('name', 'order', 'asc', array('visible'=>1));
		
		$this->controller->set('staffAttendanceTypeOptions', $staffAttendanceType);
		
		
		if($this->controller->request->is('post')){
			$postData = $this->controller->request->data;
			
			if(!empty($postData['hour'])){unset($postData['hour']);}
			if(!empty($postData['minute'])){unset($postData['minute']);}
			if(!empty($postData['meridian'])){unset($postData['meridian']);}
			
			if($this->saveAll($postData)){
				$this->controller->Message->alert('general.add.success');
				return $this->controller->redirect(array('action' => 'StaffAttendanceDay/staff_list', $selectedDate));
			}
		}
		else{
			$attendanceData = $this->getStaffsAttendance($selectedDate);
			$this->controller->request->data = $attendanceData;
		}

		$attendanceType = ClassRegistry::init('StaffAttendanceType')->getAttendanceList('all', true);
		$this->controller->set('attendanceType', $attendanceType);

		$this->controller->set('portletHeader', $this->Message->getLabel('StaffAttendanceDay.staffAttendance'));
	}
	
	public function attendance_view($controller, $params){
		$controller->Navigation->addCrumb($this->Message->getLabel('general.attendance'));

		$staffId = $controller->Session->read('Staff.id');
		$controller->set('id', $staffId);
		if(empty($staffId)){
			$controller->redirect(array('action' => 'index'));	
		}
		
		$data = ClassRegistry::init('Staff')->getSelectedStaff($staffId, 'full');
		$controller->set('data', $data);
		
		
		$StaffAttendanceType = ClassRegistry::init('StaffAttendanceType');
		$attendanceType = $StaffAttendanceType->getAttendanceList();
		$attendanceTypeOptions = array();
		foreach($attendanceType as $item){
			$attendanceTypeOptions[$item['StaffAttendanceType']['id']] = $item['StaffAttendanceType']['name'];
		}
		
		$filterByType = NULL;
		$selectedDate ='';
		if(count($params['pass']) == 1){
			$selectedDate = $params['pass'][0];
		}
		else if(count($params['pass']) >= 2){
			$filterByType = $params['pass'][0];
			$selectedDate = $params['pass'][1];
		}
		
		$datepickerData = $controller->Utility->datepickerStartEndDate($selectedDate, 14);

		$attendancesList = $this->getStaffsAttendance($datepickerData['startDate'], $datepickerData['endDate'],$staffId,$filterByType);
		if(!empty($attendancesList)){
			$attendancesList = $attendancesList['StaffAttendanceDay'][$staffId];	
		}
		else{
			$controller->Message->alert('general.view.notExists', array('type' => 'info'));	
		}

		$controller->set('attendancesList', $attendancesList);
		$controller->set('attendanceType', $attendanceType);
		$controller->set('attendanceTypeOptions', $attendanceTypeOptions);
		$controller->set('selectedAttendanceType', $filterByType);
		$controller->set('startDate', $datepickerData['startDate']);
		$controller->set('endDate', $datepickerData['endDate']);
		$controller->set('header', $data['SecurityUser']['first_name'].' '.$data['SecurityUser']['last_name'].' ('.$data['SecurityUser']['openemisid'].')');
	}
	
	function getStaffsAttendance($startDate, $endDate = NULL, $staffid = NULL, $attendanceType = NULL){
		$options['joins'] = array(
			array('table' => 'staff_attendance_types',
				'alias' => 'StaffAttendanceType',
				'type' => 'LEFT',
				'conditions' => array(
					'StaffAttendanceType.id = StaffAttendanceDay.staff_attendance_type_id',
				)
			),
		);
		$options['recursive'] = -1;
		$options['order'] = array('StaffAttendanceDay.staff_id ', 'StaffAttendanceDay.attendance_date ASC');
		$options['fields'] = array('StaffAttendanceDay.*', 'StaffAttendanceType.name');
		
		if(!empty($startDate) && !empty($endDate)){
			$options['conditions'] = array('StaffAttendanceDay.attendance_date >=' => $startDate, 'StaffAttendanceDay.attendance_date <=' => $endDate);
		}
		else{
			$options['conditions'] = array('StaffAttendanceDay.attendance_date' => $startDate);
		}
		
		if(!empty( $staffid)){
			array_push($options['conditions'], array('StaffAttendanceDay.staff_id' => $staffid));
		}
		
		if(!empty( $attendanceType)){
			array_push($options['conditions'], array('StaffAttendanceDay.staff_attendance_type_id' => $attendanceType));
		}

		$data = $this->find('all', $options);
		
		$newData = array();
		for($i = 0; $i < count($data); $i ++){
			$id = $data[$i]['StaffAttendanceDay']['staff_id'];
			$tempArr = $data[$i]['StaffAttendanceDay'];
			$tempArr['attendance_type_name'] = $data[$i]['StaffAttendanceType']['name'];
			$newData[$id]['StaffAttendanceDay'] = $tempArr;
		}
		return $newData;
	}
	
	
	
}

?>