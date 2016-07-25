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

class Timetable extends AppModel {
	public $actsAs = array(
		'ControllerAction',
		'DatePicker' => array('start_date','end_date')
		);

	public $acoName = 'Timetable';

	public $accessMapping = array(
		'ajax_add_event' => 'update',
		'ajax_save_event' => 'update'
	);

    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'name' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('name')
                )
            ),
            'start_date' => array(
                'ruleNotLater' => array(
                    'rule' => array('compareDate', 'end_date'),
                    'message' => $this->getErrorMessage('startDateLater')
                ),
            )
        );
    }

    public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb($this->Message->getLabel('general.timetable'));
		$this->fields['class_id']['type'] = 'hidden';
		$this->fields['class_id']['value'] = $this->Session->read('SClass.id');
	}
	
	public function getTimetable($id){
		$data = $this->find('first', array(
			'conditions' => array('id'=>$id),
			'recursive' => -1
		));
		
		return $data;
	}

	public function saveTimetable($data){
		$data = $data['Timetable'];
		if(!empty($data['id'])){
			$this->id = $data['id'];
		}
		else{
			$this->create();
		}
		
		return $this->save($data);
	}
	
	public function getAllTimetableByClass($id, $searchMode = 'list'){
		$options['conditions'] = array('class_id'=> $id);
		$options['recursive'] = -1;
		
		if($searchMode == 'list'){
			$options['fields'] = array('id', 'name');
		}
		
		$data = $this->find($searchMode, $options);
		
		return $data;
	}
	
	public function getTimetableStartEndTime($id){
		$data = $this->find('first', array(
			'conditions' => array('id'=>$id),
			'fields' => array('start_date', 'end_date'),
			'recursive' => -1
		));
		
		return $data;
	}

	public function getListOfDiffMonths($startDate, $endDate){
		$curMonth = date("MY", strtotime($startDate));
		$endMonth = date('MY', strtotime($endDate.'+1 month'));
		
		$monthList = array();
		while($curMonth != $endMonth){
			$unixTime = strtotime($curMonth);
			$monthList[$unixTime] = 	date('M, Y', $unixTime);
			$curMonth = date('MY', strtotime($curMonth. '+1 month'));
			
		}
		
		return $monthList;
	}

	public function getAllTimetableByStaff($id){
		$data = $this->find('list', array(
			'conditions' => array('TimetableEntry.staff_id'=> $id),
			'recursive' => -1,
			'fields' => array('Timetable.id', 'Timetable.name'),
			'joins' => array(
				array(
					'table' => 'timetable_entries',
					'alias' => 'TimetableEntry',
					'conditions' => array('Timetable.id = TimetableEntry.timetable_id')
				)
			)
		));
		return $data;
	}
	
	public function getDisplayFields() {
		$model = get_class($this);
		$fields = array(
            'model' => $this->alias,
            'fields' => array(
                array('field' => 'name', 'model' => $model),
                array('field' => 'start_date', 'model' => $model, 'type' => 'datepicker', 'dateOptions' => array('id' => 'startDate')),
                array('field' => 'end_date', 'model' => $model, 'type' => 'datepicker', 'dateOptions' => array('id' => 'endDate')),
                array('field' => 'modified_by', 'model' => 'ModifiedUser', 'edit' => false),
                array('field' => 'modified', 'model' => $model, 'label' => $this->Message->getLabel('general.modifiedOn'), 'edit' => false),
                array('field' => 'created_by', 'model' => 'CreatedUser', 'edit' => false),
                array('field' => 'created', 'model' => $model, 'label' => $this->Message->getLabel('general.createdOn'), 'edit' => false)
            )
		);
		return $fields;
	}
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	
	public function index($params=null) {
		if($this->controller->name == 'Students'){
			$this->controller->set('contentHeader', $this->Message->getLabel('Student.title'));
			//$studentId = $this->Session->read('Student.id');
			$this->timetable_view_student($params);
			$this->render = 'override';
			$this->render_override = '../Students/Timetable/view';
		}
		else if($this->controller->name == 'Staff'){
			$this->controller->set('contentHeader', $this->Message->getLabel('Staff.title'));
			//$staffId = $this->Session->read('Staff.id');
			$this->timetable_view_staff($params);
			$this->render = 'override';
			$this->render_override = '../Staff/Timetable/view';
		}
		else if($this->controller->name == 'Classes'){
			$this->controller->set('contentHeader', $this->Message->getLabel('SClass.title'));
			//$classId = $this->Session->read('Class.id');
			$this->timetable_view_class($params);
			$this->render = 'override';
			$this->render_override = '../Classes/Timetable/view';
		}
		else{
			
		}
	}

	public function view($id=0) {
		$this->index();
	}

	public function edit($id=0){
		parent::edit($id);
		$this->render = 'edit';
	}
	
	public function add(){
		parent::add();
		$this->render = 'edit';
	}
	
	function setup_add_edit_form($params){
		/*$name = '';
		$startTime = date('Y-m-d', time());
		$endTime = date('Y-m-d', time()+86400);*/
		
		$className = $this->Session->read('Class.name');
		$classId = $this->Session->read('Class.id');
		$id = empty($params['pass'][0])? NULL : $params['pass'][0];
		$data = $this->getTimetable($id);
		
		if(!empty($data)){
			$name = $data['Timetable']['name'];	
			/*list($startYear, $startMonth, $startDate) = explode('-',$data['Timetable']['start_date']);	
			list($endYear, $endMonth, $endDate) = explode('-',$data['Timetable']['end_date']);	
			
			$startTime = array('year' => $startYear, 'month' => $startMonth, 'day' => $startDate);
			$endTime = array('year' => $endYear, 'month' => $endMonth, 'day' => $endDate);*/
		}	
		
		if($this->request->is('post')){
			
			$postData = $this->request->data;
		
			if(!empty($id) && !empty($data)){
				$postData['Timetable']['id'] = $id;
			}
			//pr($postData);
			if($this->saveTimetable($postData)){
				$this->Message->alert('general.add.success');
				return $this->redirect(array('action' => 'Timetable', $this->id));
			}
			else{
				$this->Message->alert('general.add.failed', array('type' => 'error'));
			}
		}
		else{
			$this->request->data = $data;
		}
		
		$this->Navigation->addCrumb($this->Message->getLabel('general.timetable'), array('controller' => 'Classes', 'action' => 'Timetable', $id));
		
		$fields = $this->getDisplayFields();
		$this->setVar('fields', $fields);
		
		$this->setVar('classId', $classId);
		$this->setVar('header', $className);
	}
	
	function timetable_view_class($params=null){
		$classId = $this->Session->read('SClass.id');
		$className = $this->Session->read('SClass.data.name');

		$timetableList = $this->getAllTimetableByClass($classId);
	
		$selectedTimetable = empty($params) ? key($timetableList) : $params;

		$timetableStartEndTime = $this->getTimetableStartEndTime($selectedTimetable);
		$setupData = $this->controller->Schedule->getTimetableSetupData($selectedTimetable);
		//pr($setupData);
		//pr($classId);
		$this->controller->set('timetableStartEndTime', $timetableStartEndTime);
		$this->controller->set('timetableList', $timetableList);
		$this->controller->set('selectedTimetable', $selectedTimetable);
		
		$this->controller->set('classSchoolYear', $this->controller->Session->read('Class.school_year'));

		foreach($setupData['data'] as $key => $row) {
			if (array_key_exists('SecurityUser', $row)) {
				$setupData['data'][$key]['SecurityUser']['full_name'] = $this->Message->getFullName($row);
			}
		}
		$this->controller->set('setupData',$setupData);

		//pr($timetableList);
		if(empty($timetableList)){
			$this->controller->Message->alert('general.view.notExists', array('type' => 'info'));
		}
	}
	
	function timetable_view_staff($timetableId) {
		$id = $this->controller->Session->read('Staff.id');
		
		$Staff = ClassRegistry::init('Staff');
		if(!$id || !$Staff->exists($id)) {
			$this->controller->Message->alert('general.view.notExists', array('type' => 'info'));
			$this->controller->redirect(array('action' => 'index'));
		}
		
		$data = $Staff->find('first', array('recursive' => 0, 'conditions' => array('Staff.id' => $id)));
		$this->controller->set('data', $data);
		$this->controller->set('id', $id);
		//$staffId =1;//empty($this->Session->read('Staff.id'))? 1:$this->Session->read('Staff.id');
		
		$timetableList = $this->getAllTimetableByStaff($id);
		
		$selectedTimetable = !empty($timetableId) ? $timetableId:key($timetableList);
		$timetableStartEndTime = $this->getTimetableStartEndTime($selectedTimetable);
		
		$this->controller->set('timetableStartEndTime', $timetableStartEndTime);
		$this->controller->set('timetableList', $timetableList);
		$this->controller->set('selectedTimetable', $selectedTimetable);
		
		$this->controller->Schedule->editable = false;
		$this->controller->Schedule->addable = false;
		$TimetableEntry = ClassRegistry::init('TimetableEntry');
		$this->controller->Schedule->timetableEntryData = $TimetableEntry->getAllSelectedClassTimetableEntryByStaffId($id, $selectedTimetable);

		foreach($this->controller->Schedule->timetableEntryData as $key => $row) {
			$this->controller->Schedule->timetableEntryData[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($row);
		}
		
		$setupData = $this->controller->Schedule->getTimetableSetupData($selectedTimetable);
		if(empty($timetableList)){
			$this->controller->Message->alert('general.view.notExists', array('type' => 'info'));
		}
		
		$this->controller->set('setupData',$setupData);
		$temp_array = array();
		$temp_array['SecurityUser'] = array();
		$temp_array['SecurityUser']['first_name'] = trim($this->controller->Session->read('Staff.data.SecurityUser.first_name'));
		$temp_array['SecurityUser']['middle_name'] = trim($this->controller->Session->read('Staff.data.SecurityUser.middle'));
		$temp_array['SecurityUser']['last_name'] = trim($this->controller->Session->read('Staff.data.SecurityUser.last_name'));
		$staffNum = trim($this->controller->Session->read('Staff.data.Staff.openemisid'));
		$header = trim($this->Message->getFullName($temp_array)) . ' (' . $staffNum . ')';
		
	}
	
	function timetable_view_student($timetableId){
		$studentId = $this->controller->Session->read('Student.id');
		
		$data = $this->controller->Student->find('first', array('recursive' => 0, 'conditions' => array('Student.id' => $studentId)));
		$data['SecurityUser']['full_name'] = $this->Message->getFullName($data);
		$this->controller->set('data', $data);
		$header = trim($this->Message->getFullName($data));
			
		$ClassStudent = ClassRegistry::init('ClassStudent');
		$classData = $ClassStudent->findByStudentId($studentId, array('fields' => 'class_id'));
		//pr($classData);
		
		if(empty($classData)){
			$this->controller->Message->alert('general.view.notExists', array('type' => 'info'));
		}
		else{
			$classId = $classData['ClassStudent']['class_id'];
			$timetableList = $this->getAllTimetableByClass($classId);
			$selectedTimetable = empty($timetableId) ? key($timetableList) : $timetableId;
			$timetableStartEndTime = $this->getTimetableStartEndTime($selectedTimetable);
			
			$this->controller->set('timetableStartEndTime', $timetableStartEndTime);
			$this->controller->set('timetableList', $timetableList);
			$this->controller->set('selectedTimetable', $selectedTimetable);
			
			$this->controller->Schedule->editable = false;
			$this->controller->Schedule->addable = false;
			
			$TimetableEntry = ClassRegistry::init('TimetableEntry');
			$this->controller->Schedule->timetableEntryData = $TimetableEntry->getAllSelectedClassTimetableEntry($selectedTimetable);
			$setupData = $this->controller->Schedule->getTimetableSetupData($selectedTimetable);
			
			foreach ($setupData['data'] as $key => $row) {
				$setupData['data'][$key]['SecurityUser']['full_name'] = $this->Message->getFullName($setupData['data'][$key]);
			}
			$this->controller->set('setupData',$setupData);
			
			if(empty($timetableList)){
				$this->controller->Message->alert('general.view.notExists', array('type' => 'info'));
			}
		}
	}
	
	
	public function ajax_add_event(){
		//echo "hahahaha - linked";
		$this->controller->layout = 'ajax';
		$classId = $this->Session->read('SClass.id');
		if(!empty($this->request->data['entry_id'])){
			$TimetableEntry = ClassRegistry::init('TimetableEntry');
			$data = $TimetableEntry->getSelectedTimetableEntryFullDetails($this->request->data['entry_id']);
			$this->setVar('data', $data);
		}
			
		if(!empty($classId)){
			$ClassSubject = ClassRegistry::init('ClassSubject');
			//$controller->set('subjectOptions',  $EducationGradesSubject->getSubjectsByClass($classId));getSubjectByClass
			$this->setVar('subjectOptions',  $this->controller->Utility->getSetupOptionsData($ClassSubject->getSubjectByClass($classId, 'list')));
			//$Staff = ClassRegistry::init('Staff');
			$ClassTeacher = ClassRegistry::init('ClassTeacher');
			//$controller->set('teacherOptions',  $Staff->getStaffList());
			$this->setVar('teacherOptions',  $this->controller->Utility->getSetupOptionsData($ClassTeacher->getTeacherByClass($classId, 'list')));
			$Room = ClassRegistry::init('Room');
			$this->setVar('locationOptions',  $this->controller->Utility->getSetupOptionsData($Room->getOptions('name', 'order', 'asc', array('visible'=>1))));
		}
	}

	public function ajax_save_event(){
		$this->render = false;

		$classId = $this->Session->read('SClass.id');
		
		if($this->request->is('ajax')) {
			$data = $this->request->data['TimetableEntry'];
			
			if ($this->request->is('post')) {
				$TimetableEntry = ClassRegistry::init('TimetableEntry');

				$TimetableEntry->set($data);
				if($TimetableEntry->validates()){
					$EducationSubject = ClassRegistry::init('EducationSubject');
					$subjectData = $EducationSubject->getGradeSubjectId($data['education_subject_id'], $classId);
					
					$Staff = ClassRegistry::init('Staff');
					$teacherData = $Staff->getSelectedStaff($data['staff_id']);
					
					if (!empty($data['id']) && $TimetableEntry->exists($data['id'])) {
						$TimetableEntry->id = $data['id'];
					}
					else{
						$TimetableEntry->create();
					}

					$data['education_grade_subject_id'] = $subjectData['EducationGradeSubject']['id'];


					$data['class_id'] = $classId;
					unset($data['education_subject_id']);
					unset($TimetableEntry->validate['education_subject_id']);
					
					$TimetableEntry->validate = Set::merge($TimetableEntry->validate, 
						array('education_grade_subject_id' => 
							array(
								'rule' => 'checkDropdownData',
								'required' => true,
								'message' => $this->Message->getLabel('class.selectValidSubject')
							)
						)
					);

					if ($TimetableEntry->saveAll($data)) {
						$id =  $TimetableEntry->id;
						
						$entryData = array(
							'id' => $id,
							'subject_title' => $subjectData['EducationSubject']['name'],
							'subject_code' => $subjectData['EducationSubject']['code'],
							'teacher_inCharge' => $this->Message->getFullName($teacherData),
							'teacher_openemisid' => $teacherData['SecurityUser']['openemisid'],
							'editable' => $data['editable']
						);
						
						$div = $this->controller->Schedule->getDisplayEntry($entryData);
						return json_encode(array('success'=> true, 'data'=>$div));
					}
					else{
						//return json_encode(array('success'=> false, 'errorMessage'=> $controller->Message->get('general.add.failed')));
						return json_encode(array('success'=> false, 'errorMessage'=> current($TimetableEntry->validationErrors)));
						//return json_encode(array('success'=> false, 'errorMessage'=> $data));
					}
				}
				else{
					return json_encode(array('success'=> false, 'errorMessage'=> current($TimetableEntry->validationErrors)));
				}
			}
		}
	}
	
	/*public function timetable_ajax_delete_event($controller, $params){
		$controller->autoRender = false;
		
		$controller->request->onlyAllow('post', 'delete');
		
		$this->TimetableEntry->id = $id;
		
		if($this->TimetableEntry->delete()){
			$controller->Message->alert('general.delete.success');
		} else {
			$controller->Message->alert('general.delete.failed', array('type' => 'error'));
		}
		return $controller->redirect(array('action' => 'timetables', $_POST['timetable_id']));
	}*/

}
?>
