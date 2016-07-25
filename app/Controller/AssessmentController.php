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

class AssessmentController extends AppController {
	public $uses = Array(
		'AssessmentItemType',
		'AssessmentItem',
		'EducationGrade',
		'EducationSubject',
		'EducationGradesSubject'
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Navigation->addCrumb($this->Message->getLabel('admin.title'), array('controller' => "Admin", 'action' => 'view'));
		$this->Navigation->addCrumb($this->Message->getLabel('Assessment.title'));
		
		$this->set('tabElement', '../Admin/tabs');
		$this->set('contentHeader', $this->Message->getLabel('admin.title'));
		$this->set('portletHeader', $this->Message->getLabel('Assessment.title'));
		$this->set('header', $this->Message->getLabel('Assessment.title'));
		$this->set('model', 'AssessmentItemType');
	}
	
	public function index() {
		$data = $this->AssessmentItemType->find('all', array(
			'recursive' => 0, 
			'conditions' => array('AssessmentItemType.class_id' => 0),
			'order' => array('EducationGrade.order', 'AssessmentItemType.name')
		));
		if(empty($data)) $this->Message->alert('general.view.noRecords');
		$this->set(compact('data'));
	}
	
	public function add() {
		$fields = $this->AssessmentItemType->getFields(array('action' => 'add'));
		$gradeOptions = $this->EducationGrade->getProgrammeGradeOptions();
		$selectedGrade = key($gradeOptions);
		
		if ($this->request->is(array('post', 'put'))) {
			$submitType = $this->request->data['submit'];
			if ($submitType == 'reload') {
				$selectedGrade = $this->request->data['AssessmentItemType']['education_grade_id'];
			} else {
				$data = $this->request->data;
				unset($data['submit']);
				if (!empty($data['AssessmentItem'])) {
					$assessmentItems = $data['AssessmentItem'];
					
					// set min, max, weighting to 0 if null values entered
					foreach ($assessmentItems as $i => $obj) {
						if ($obj['visible'] == 0) {
							unset($assessmentItems[$i]);
						} else {
							if (empty($obj['min'])) { $assessmentItems[$i]['min'] = 0; }
							if (empty($obj['max'])) { $assessmentItems[$i]['max'] = 0; }
							if (empty($obj['weighting'])) { $assessmentItems[$i]['weighting'] = 0; }
						}
					}
					$data['AssessmentItem'] = $assessmentItems;
				}
				if ($this->AssessmentItemType->saveAll($data)) {
					$this->Message->alert('general.add.success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Message->alert('general.add.failed');
				}
			}
		}
		$items = $this->EducationGradesSubject->getSubjectsByGrade($selectedGrade);
		
		$this->set(compact('fields', 'gradeOptions', 'items'));
	}
	
	public function view($id=0) {
		if ($this->AssessmentItemType->exists($id)) {
			$this->AssessmentItemType->recursive = 0;
			$data = $this->AssessmentItemType->findById($id);
			$fields = $this->AssessmentItemType->getFields();
			$items = $this->AssessmentItem->getItems($id);
			
			$this->set(compact('data', 'fields', 'items'));
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => 'index'));
		}
	}
	
	public function edit($id=0) {
		if ($this->AssessmentItemType->exists($id)) {
			$gradeOptions = $this->EducationGrade->getProgrammeGradeOptions();
			
			$this->AssessmentItemType->recursive = 0;
			$data = $this->AssessmentItemType->findById($id);
			$fields = $this->AssessmentItemType->getFields(array('action' => 'edit'));
			$items = $this->AssessmentItem->getItemsForEdit($id);
			
			if ($this->request->is(array('post', 'put'))) {
				if (!empty($this->request->data['AssessmentItem'])) {
					$assessmentItems = $this->request->data['AssessmentItem'];
					// set min, max, weighting to 0 if null values entered
					foreach ($assessmentItems as $i => $obj) {
						if (empty($obj['id']) && $obj['visible'] == 0) {
							unset($assessmentItems[$i]);
						} else {
							if (empty($obj['min'])) { $assessmentItems[$i]['min'] = 0; }
							if (empty($obj['max'])) { $assessmentItems[$i]['max'] = 0; }
							if (empty($obj['weighting'])) { $assessmentItems[$i]['weighting'] = 0; }
						}
					}
					$this->request->data['AssessmentItem'] = $assessmentItems;
				}
				if ($this->AssessmentItemType->saveAll($this->request->data)) {
					$this->Message->alert('general.edit.success');
					return $this->redirect(array('action' => 'view', $id));
				} else {
					if (!empty($items)) {
						// set the assessment items based on submitted values if saving fails
						foreach ($items as $i => $obj) {
							$items[$i]['AssessmentItem'] = array_merge($obj['AssessmentItem'], $this->request->data['AssessmentItem'][$i]);
						}
					}
					$this->Message->alert('general.edit.failed');
				}
			} else {
				$this->request->data = $data;
			}
			
			$this->set(compact('fields', 'items', 'gradeOptions'));
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => 'index'));
		}
	}
} 
