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

App::uses('AppController', 'Controller');
/**
 * Attendance Controller
 *
 * @property Class $Class
 */
class AttendanceController extends AppController {
	public $uses = array(
		'SClass',
		'EducationSubject',
		'ClassStudent',
		'ClassLesson',
		'ClassTeacher',
		//'StudentAttendanceType',
		//'StaffAttendanceType',
		//'StudentAttendanceDay',
		//'StudentAttendanceLesson',
		'Attendance',
		//'TimetableEntry',
		//'Staff'
	);
	
	public $components = array('Utility');
	public $modules = array(
		'StaffAttendanceDay',
		'day' => 'StudentAttendanceDay',
		'lesson' => 'StudentAttendanceLesson'
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('header', __('Attendance'));
		
		if(!$this->request->is('ajax')) {
			if(in_array($this->action, array('StaffAttendanceDay'))) {
				$this->Navigation->addCrumb('Staff', array('controller' => 'Staff', 'action' => 'index'));
			}
			else if(!in_array($this->action, array('index'))) {
				$this->Navigation->addCrumb('Attendance', array('controller' => 'Attendance', 'action' => 'index'));
			}
		}
		
	}
	
	public function index($type = NULL) {
		$configModel = ClassRegistry::init('ConfigItem');
		$attendanceView = $configModel->getValue('attendance_view');
		$attendanceView = !empty($attendanceView)? $attendanceView : 'day';
		$this->set('attendanceView', $attendanceView);
		
		$this->Message->clearAlert();
		$this->Navigation->addCrumb('Search student attendance');
		
		//$type = (empty($type) || $type > 2)? 1:$type;
		//$this->set('type', $type);
		if($attendanceView == 'lesson'){
			$subjectOptions = $this->EducationSubject->getSubjectList();
			$this->set('subjectOptions', $this->Utility->getSetupOptionsData($subjectOptions));
			
			$subjectEmptyDisplay = '-- Please select --';
			if(empty($subjectOptions)){
				$subjectEmptyDisplay = NULL;
			}
			
			$this->set('subjectEmptyDisplay', $subjectEmptyDisplay);
			
			if(!empty($this->params['pass'][1]) && !empty($this->params['pass'][2])){
				$selectedSubjectId = $this->params['pass'][2];
				$selectedClassId = $this->params['pass'][1];
				
				$this->set('selectedClassId',$selectedClassId);
				$this->set('selectedSubjectId',$selectedSubjectId);
				
				if(empty($selectedSubjectId)){
					$classOptions = array();
					
					$this->Message->alert('general.view.notExists', array('type' => 'warn'));
				}
				else{
					$classOptions = $this->SClass->getClassListBySubjectId($selectedSubjectId);
				}
				
				$this->set('classOptions',$this->Utility->getSetupOptionsData($classOptions));
			}
		}
		
		if($this->request->is('post')){
			$postData = $this->request->data;
			$this->Attendance->set($postData);
			
			if($attendanceView == 'lesson'){
				$redirect = true;
				$action = 'lesson';
				$validateFields = array('fieldList' => array('classId', 'subjectId'));
				
				if(!empty($postData['Attendance']['classId'])){
					$id = $postData['Attendance']['classId'];
					
					
					$this->SClass->recursive = -1;
					$classData = $this->SClass->findAllById($id, array('SClass.id', 'SClass.name'));
					
					//pr($classData);
					if(!empty($classData)){
						$this->Session->write('Class.id', $id);
						$this->Session->write('Class.name', $classData[0]['SClass']['name']);
						
						$redirectData = array('controller' => $this->request->params['controller'] , 'action' => $action, $postData['Attendance']['classId'], $postData['Attendance']['subjectId']);
					}
					else{
						$redirect = false;
						$this->set('classOptions', $this->SClass->getClassListBySubjectId($id));
						$this->Message->alert('general.view.notExists', array('type' => 'warn'));
					}
				}
			} 
			else{
				$redirect = true;
				$action = 'day';	
				$className = $postData['Attendance']['name'];
				$validateFields = array('fieldList' => array('name'));
				
				if(!empty($className)){
					$classData = $this->SClass->getClassIdByName($className);
					
					if(!empty($classData)){
						$id = $classData['SClass']['id'];
						
						$this->Session->write('Class.id', $id);
						$this->Session->write('Class.name', $classData['SClass']['name']);
		
						$redirectData = array('controller' => $this->request->params['controller'] , 'action' => $action,$id);
					}
					else{
						$redirect = false;
						$this->Message->alert('general.view.notExists', array('type' => 'warn'));
					}
				}
			}
			
			if($this->Attendance->validates($validateFields) && $redirect){
				return $this->redirect($redirectData);
			}
		}
	}
	
	public function ajax_find_class() {
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$search = $this->params->query['term'];
			$data = $this->SClass->autocomplete($search);
			return json_encode($data);
		}
	}
	
	public function getClassBySubjectId($id){
		$this->autoRender = false;
		
		$data = $this->SClass->getClassListBySubjectId($id);
		$returnStr = '<option value="">-- No Data --</option>';
		if(!empty($data)){
			$returnStr = '';
			foreach($data as $key => $item){
				$returnStr .= '<option value="'.$key.'">'.$item.'</option>';
			}
		}
		echo $returnStr;
	}
} 

?>