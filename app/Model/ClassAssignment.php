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

class ClassAssignment extends AppModel {
	public $useTable = 'assessment_item_types';
	
	public $belongsTo = array(
		'SClass' => array(
			'className' => 'SClass',
			'foreignKey' => 'class_id',
		),
		'EducationGrade',
		'SchoolYear',
		'ModifiedUser' => array(
			'className' => 'SecurityUser',
			'fields' => array('first_name', 'last_name'),
			'foreignKey' => 'modified_user_id',
			'type' => 'LEFT'
		),
		'CreatedUser' => array(
			'className' => 'SecurityUser',
			'fields' => array('first_name', 'last_name'),
			'foreignKey' => 'created_user_id',
			'type' => 'LEFT'
		)
	);
	public $hasMany = array('AssessmentItem');
	public $actsAs = array('ControllerAction','Export' => array('module' => 'SClass'));

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
            'education_grade_id' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('grade')
                )
            )
        );
    }
	
	public function beforeAction() {
		parent::beforeAction();
		
		$this->setVar('header', $this->Message->getLabel('ClassAssignment.title'));
		$this->Navigation->addCrumb($this->Message->getLabel('ClassAssignment.title'));
		
		$classId = $this->Session->read('SClass.id');
		
		$this->fields['education_grade_id']['visible'] = false;
		$this->fields['education_grade_id']['labelKey'] = 'AssessmentItemType';
		$this->fields['visible']['type'] = 'select';
		$this->fields['visible']['options'] = $this->getStatusOptions();
		$this->fields['order']['type'] = 'hidden';
		$this->fields['order']['value'] = 0;
		$this->fields['order']['visible'] = array('edit' => true);
		$this->fields['school_year_id']['type'] = 'hidden';
		$this->fields['school_year_id']['value'] = $this->SClass->field('school_year_id', array('id' => $classId));
		$this->fields['class_id']['type'] = 'hidden';
		$this->fields['class_id']['value'] = $classId;
		
		$gradeOptions = $this->EducationGrade->getProgrammeGradeOptions();
		if ($this->action == 'add') {
			$this->fields['education_grade_id']['visible'] = false;
			$this->fields['visible']['type'] = 'hidden';
			$this->fields['visible']['value'] = 1;
		} else if ($this->action == 'view') {
			$this->fields['education_grade_id']['visible'] = array('view' => true);
			$this->fields['education_grade_id']['type'] = 'select';
			$this->fields['education_grade_id']['options'] = $gradeOptions;
		} else {
			$this->fields['education_grade_id']['visible'] = array('edit' => true);
			$this->fields['education_grade_id']['type'] = 'disabled';
			$this->fields['education_grade_id']['options'] = $gradeOptions;
		}
	}
	
	public function index() {
		$data = $this->getListData();
		if(empty($data)) $this->Message->alert('general.view.noRecords');
		$this->setVar(compact('data'));
	}
	
	public function add() {
		$classId = $this->Session->read('SClass.id');
		$gradeOptions = $this->EducationGrade->ClassGrade->getGradeListByClassId($classId);
		$selectedGrade = key($gradeOptions);
		
		if ($this->request->is(array('post', 'put'))) {
			$submitType = $this->request->data['submit'];
			if ($submitType == 'reload') {
				$selectedGrade = $this->request->data['ClassAssignment']['education_grade_id'];
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
				if ($this->saveAll($data)) {
					$this->Message->alert('general.add.success');
					return $this->redirect(array('action' => get_class($this)));
				} else {
					$this->Message->alert('general.add.failed');
				}
			}
		}
		$items = $this->EducationGrade->EducationGradesSubject->getSubjectsByGrade($selectedGrade);
		
		$this->setVar(compact('gradeOptions', 'items'));
	}
	
	public function view($id=0) {
		if ($this->exists($id)) {
			$this->recursive = 0;
			$data = $this->findById($id);
			$items = $this->AssessmentItem->getItems($id);
			
			$this->setVar(compact('data', 'items'));
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => get_class($this)));
		}
	}
	
	public function edit($id=0) {
		if ($this->exists($id)) {
			$gradeOptions = $this->EducationGrade->getProgrammeGradeOptions();
			
			$this->recursive = 0;
			$data = $this->findById($id);
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
				if ($this->saveAll($this->request->data)) {
					$this->Message->alert('general.edit.success');
					return $this->redirect(array('action' => get_class($this), 'view', $id));
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
			
			$this->setVar(compact('items', 'gradeOptions'));
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => get_class($this)));
		}
	}

	public function reportGetFieldNames() {
		$fields = $this->fields;
		unset($fields['id']);
		unset($fields['order']);
		unset($fields['visible']);
		return $this->getFieldNamesFromFields($fields);
	}

	public function reportGetData() {
		return $this->handleOptionsInData($this->fields,$this->getListData());
	}

	public function getListData($options=array()) {
		$this->recursive = 0;
		$classId = $this->Session->read('SClass.id');
		$data = $this->findAllByClassId($classId, null, array('EducationGrade.order', 'ClassAssignment.name'));
		return $data;
	}
}
