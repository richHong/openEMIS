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

class ClassesController extends AppController {
	public $uses = array(
		'SchoolYear',
		'SClass',
		'ClassGrade',
		'EducationProgramme',
		'EducationGrade',
		'ClassLesson',
		'Student',
		'Room',
		'EducationGradesSubject',
		'EducationSubject',
		'Staff',
		'Timetable',
		'TimetableEntry',
		'ClassTeacher',
		'ClassAttachment',
		'ClassResult',
		'ClassStudent'
	);
	
	public $components = array('Paginator', 'Utility', 'Schedule', 'FileUploader', 'Sortable');

	public $modules = array(
		'Timetable',
		'ClassStudent',
		'ClassTeacher',
		'ClassSubject',
		'ClassAssignment',
		'ClassResult',
		'ClassLesson',
		'ClassAttachment',
		'attendance' => 'Attendance',
		'ClassAttendanceDay',
		'ClassAttendanceLesson',
		// 'StudentAttendanceDay'
	);

	public function beforeFilter() {
		parent::beforeFilter();
		
		$this->set('header', $this->Message->getLabel('SClass.title'));
		$actions = array('add', 'index', 'view', 'search', 'edit', 'export');
		
		$this->set('model', 'SClass');
		$this->set('tabElement', '../Classes/tabs');
		$this->set('contentHeader', $this->Message->getLabel('SClass.title'));
		
		if (!$this->request->is('ajax')) {
			$this->Navigation->addCrumb($this->Message->getLabel('SClass.title'), array('controller' => 'Classes', 'action' => 'index'));

			if (!in_array($this->action, $actions)) { // if the action is not one of the actions not requiring the id
				if (!$this->Session->check('SClass.id')) { // if id not exists in session
					$this->Message->alert('general.view.notExists');
					return $this->redirect(array('action' => 'index'));
				} else {
					$id = $this->Session->read('SClass.id');
					if (!$this->SClass->exists($id)) {
						$this->Message->alert('general.view.notExists');
						return $this->redirect(array('action' => 'index'));
					} else {
						$name = trim($this->Session->read('SClass.data.name'));
						$this->set('name', $name);
						$this->Session->write('Report.SClass.reportHeader', array(
							$this->Message->getLabel('general.name')=>$name)
						);
						$this->set('portletHeader', $name);
						if ($this->action !== 'view' && $this->action !== 'edit') {
							$this->Navigation->addCrumb($name, array('controller' => 'Classes', 'action' => 'view', $id));
						}
					}
				}
			}
		}
	}


	public function export() {
		$currentModel = 'SClass';
		$this->render = false;
		
		$options = array();
		$options['accessViewType'] = $this->Session->read('SClass.export.accessViewType');
		$options['selectedYear'] = $this->Session->read('SClass.export.selectedYear');

		$data = array();

		$data['data'] = $this->getListData($options);
		foreach ($data['data'] as $key => $value) {
			if (array_key_exists('ClassGrade', $data['data'][$key])) {
				unset($data['data'][$key]['ClassGrade']);
			}	
		}
		$data['fieldNames'] = $this->$currentModel->getFieldNamesFromData($data['data']);

		$data['fileName'] = 'Class';
		$nowTime = time();
		$nowTime = date('Y-m-d H:i:s');
		$data['fileName'] .= ' - '.$nowTime;
		$this->$currentModel->exportCSV($data);
	}

	public function getListData($options=array()) {
		if (array_key_exists('accessViewType', $options)) {
			$accessViewType = $options['accessViewType'];
		}
		if (array_key_exists('selectedYear', $options)) {
			$selectedYear = $options['selectedYear'];
		}

		$fields = array(
			'SClass.name','SClass.seats_total','SchoolYear.name','SchoolYear.start_date', 'SchoolYear.end_date','SchoolYear.school_days'
		);
		if ($accessViewType != 2) {
			$this->SClass->recursive = 0;
			$data = $this->SClass->findAllBySchoolYearId($selectedYear);
			$data = $this->SClass->find(
				'all',
				array(
					'fields' => $fields,
					'conditions' => array(
						'SClass.school_year_id' => $selectedYear
					)
				)
			);
		} else {
			// only the classes that the teacher is in
			$staffId = $this->Staff->getStaffIdBySecurityId(AuthComponent::user('id'));
			$this->SClass->recursive = 0;
			$data = $this->SClass->find(
				'all',
				array(
					'fields' => $fields,
					'joins' => array(
						array(
							'table' => 'class_teachers',
							'alias' => 'ClassTeacher',
							'conditions' => array('SClass.id = ClassTeacher.class_id')
						)
					),
					'conditions' => array(
						'SClass.school_year_id' => $selectedYear,
						'ClassTeacher.staff_id' => $staffId,
					)
				)
			);
		}
		
		foreach ($data as $i => $obj) {
			$data[$i]['ClassGrade'] = $this->ClassGrade->findAllByClassIdAndVisible($obj['SClass']['id'], 1);
		}
		return $data;
	}
	
	public function index($selectedYear=0) {
		if ($this->Session->check('Security.accessViewType')) {
			$accessViewType = $this->Session->read('Security.accessViewType');
		}
		$this->Navigation->addCrumb($this->Message->getLabel('SClass.list'));
		$yearOptions = $this->SchoolYear->find('list', array('conditions' => array('visible' => 1), 'order' => 'end_date desc'));
		
		if (empty($selectedYear)) {
			$selectedYear = key($yearOptions);
		}
		
		$options = array();
		$options['accessViewType'] = $accessViewType;
		$options['selectedYear'] = $selectedYear;
		$this->Session->write('SClass.export.accessViewType', $accessViewType);
		$this->Session->write('SClass.export.selectedYear', $selectedYear);
		$data = $this->getListData($options);
		if(empty($data)) $this->Message->alert('general.view.noRecords');

		$programmes = $this->EducationProgramme->find('list');

		$this->set(compact('data', 'yearOptions', 'selectedYear', 'programmes'));
	}
	
	public function view($id=0) {
		if ($id == 0) {
			if ($this->Session->check('SClass.id')) {
				$id = $this->Session->read('SClass.id');
			} else {
				$this->Message->alert('general.view.notExists');
				return $this->redirect(array('action' => 'index'));
			}
		}
		
		if ($this->SClass->exists($id)) {
			$fields = $this->SClass->getFields();
			
			$this->SClass->recursive = 0;
			$data = $this->SClass->findById($id);
			$name = $data['SClass']['name'];
			$grades = $this->ClassGrade->findAllByClassIdAndVisible($id, 1);
			$programmes = $this->EducationProgramme->find('list');
			$this->Session->write('SClass.id', $id);
			$this->Session->write('SClass.data', $data['SClass']);
			
			$this->set(compact('data', 'name', 'fields', 'grades', 'programmes'));
			$this->set('portletHeader', $name);
			$this->set('header', $this->Message->getLabel('general.general'));
			$this->Navigation->addCrumb($name);
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => 'index'));
		}
	}
	
	public function add() {
		$fields = $this->SClass->getFields();
		
		if ($this->request->is(array('post', 'put'))) {
			$this->SClass->create();
			
			$data = $this->request->data;
			if (!empty($data['ClassGrade'])) {
				$grades = $data['ClassGrade'];
				foreach ($grades as $i => $obj) {
					if ($obj['visible'] == 0) {
						unset($grades[$i]);
					}
				}
				$data['ClassGrade'] = $grades;
			}
			if ($this->SClass->saveAll($data)) {
				$this->Message->alert('general.add.success');
				return $this->redirect(array('action' => 'view', $this->SClass->getLastInsertId()));
			} else {
				$this->Message->alert('general.add.failed');
			}
		}
		
		$this->EducationGrade->recursive = 0;
		$grades = $this->EducationGrade->findAllByVisible(1);
		
		$this->Navigation->addCrumb($this->Message->getLabel('general.add'));
		$this->set(compact('fields', 'grades'));
	}
	
	public function edit($id=0) {
		if ($this->SClass->exists($id)) {
			$this->SClass->recursive = 0;
			$fields = $this->SClass->getFields(array('action' => 'edit'));
			$data = $this->SClass->findById($id);
			$name = $data['SClass']['name'];
			
			if ($this->request->is(array('post', 'put'))) {
				if (!empty($this->request->data['ClassGrade'])) {
					$grades = $this->request->data['ClassGrade'];
					foreach ($grades as $i => $obj) {
						if (empty($obj['id']) && $obj['visible'] == 0) {
							unset($grades[$i]);
						}
					}
					$this->request->data['ClassGrade'] = $grades;
				}
				if ($this->SClass->saveAll($this->request->data)) {
					$this->Message->alert('general.edit.success');
					return $this->redirect(array('action' => 'view', $id));
				} else {
					$this->Message->alert('general.edit.failed');
				}
			} else {
				$this->request->data = $data;
			}
			
			$grades = $this->ClassGrade->getGradesForEdit($id);
			$this->set(compact('data', 'name', 'fields', 'grades'));
			$this->set('portletHeader', $name);
			$this->set('header', $this->Message->getLabel('general.general'));
			$this->Navigation->addCrumb($name);
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function delete() {
		$this->autoRender = false;
		$id = $this->Session->read('SClass.id');
		$this->SClass->id = $id;

		if(!$id || !$this->SClass->exists()) {
			$this->Message->alert('general.view.notExists', array('type' => 'warn'));
			$this->redirect(array('action' => 'index'));
		}

		$this->request->onlyAllow('post', 'delete');
		if ($this->SClass->delete()) {
			$this->Message->alert('general.delete.success');
		} else {
			$this->Message->alert('general.delete.failed', array('type' => 'error'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	//--------------------------------------------------------------------------

	public function timetableAjaxDeleteEvent($id){
		$this->autoRender = false;
		
		$this->request->onlyAllow('post', 'delete');
		
		$this->TimetableEntry->id = $id;
		
		if($this->TimetableEntry->delete()){
			$this->Message->alert('general.delete.success');
		} else {
			$this->Message->alert('general.delete.failed', array('type' => 'error'));
		}
		return $this->redirect(array('action' => 'timetables', $_POST['timetable_id']));
	}
}
