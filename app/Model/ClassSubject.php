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
class ClassSubject extends AppModel {
	public $belongsTo = array(
		'SClass' => array(
			'className' => 'SClass',
			'foreignKey' => 'class_id',
		),
		'EducationGradesSubject'=> array(
			'className' => 'EducationGradesSubject',
			'foreignKey' => 'education_grade_subject_id',
		)
	);

	public $actsAs = array('ControllerAction','Export' => array('module' => 'SClass'));
	
	public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb($this->Message->getLabel('ClassSubject.title'));
		$this->setVar('header', $this->Message->getLabel('ClassSubject.title'));
	}
	
	public function index() {
		$data = $this->getListData();
		
		$this->setVar(compact('data'));
	}

	public function edit() {
		$classId = $this->Session->read('SClass.id');
		
		if ($this->request->is(array('post', 'put'))) {
			if (!empty($this->request->data['ClassSubject'])) {
				$data = $this->request->data['ClassSubject'];
				foreach ($data as $i => $obj) {
					if (empty($obj['id']) && $obj['visible'] == 0) {
						unset($data[$i]);
					}
				}
				if (!empty($data)) {
					$this->saveAll($data);
				}
				$this->Message->alert('general.edit.success');
				return $this->redirect(array('action' => get_class($this)));
			}
		}
		
		$data = $this->getSubjectsForEdit($classId);
		$this->setVar(compact('data', 'classId'));
	}

	public function reportGetFieldNames() {
		return $this->getFieldNamesFromData($this->reportData);
	}

	public function reportGetData() {
		$this->reportData = $this->getListData();
		return $this->reportData;
	}

	public function getListData($options=array()) {
		$classId = $this->Session->read('SClass.id');
		$data = $this->find('all', array(
			'recursive' => 0,
			'fields' => array('EducationGrade.code', 'EducationGrade.name', 'EducationSubject.code', 'EducationSubject.name'),
			'joins' => array(
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradeSubject',
					'conditions' => array('EducationGradeSubject.id = ClassSubject.education_grade_subject_id')
				),
				array(
					'table' => 'education_grades',
					'alias' => 'EducationGrade',
					'conditions' => array('EducationGrade.id = EducationGradeSubject.education_grade_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradeSubject.education_subject_id')
				),
				array(
					'table' => 'education_programmes',
					'alias' => 'EducationProgramme',
					'conditions' => array('EducationProgramme.id = EducationGrade.education_programme_id')
				)
			),
			'conditions' => array(
				'ClassSubject.class_id' => $classId,
				'ClassSubject.visible' => 1
			),
			'order' => array('EducationProgramme.order', 'EducationGrade.order', 'EducationSubject.order')
		));

		if(empty($data)) $this->Message->alert('general.view.noRecords');
		return $data;
	}
	
	/* used in AssessmentItemResults
	public function getAvailableSubjects($classId, $assessment_item_type_id = NULL) {
			$defaultOptions['joins'] = array(
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradesSubject',
					'conditions' => array('EducationGradesSubject.id = ClassSubject.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				),
				
			);

		$defaultOptions['fields'] = array('EducationSubject.*', 'EducationGradesSubject.*', 'AssessmentItem.*');
			$defaultOptions['recursive'] = -1;
			$defaultOptions['order'] = array('EducationSubject.order');
			$defaultOptions['conditions'] = array('ClassSubject.class_id' => $classId);

			if(empty($assessment_item_type_id)){
			  //  $defaultOptions['conditions'] = array_merge($defaultOptions['conditions'] , $extraConditions);
				$defaultOptions['joins'][] = array(
				'table' => 'assessment_items',
				'alias' => 'AssessmentItem',
				'type' => 'LEFT',
				'conditions' => array(
					'AssessmentItem.education_grade_subject_id = EducationGradesSubject.id',
					'AssessmentItem.class_id = ClassSubject.class_id',
											'AssessmentItem.assessment_item_type_id is null'
				)
			);
			}
			else{
				$defaultOptions['joins'][] = array(
				'table' => 'assessment_items',
				'alias' => 'AssessmentItem',
				'type' => 'LEFT',
				'conditions' => array(
					'AssessmentItem.education_grade_subject_id = EducationGradesSubject.id',
					'AssessmentItem.class_id = ClassSubject.class_id',
											'AssessmentItem.assessment_item_type_id = '.$assessment_item_type_id
				)
			);
		}

		$data = $this->find('all', $defaultOptions);
		return $data;
	}
	*/

	
	public function getSubjectByClass($classId, $searchMode = 'all') {
		
		if($searchMode == 'all'){
			$options['fields'] = array('ClassSubject.id', 'ClassSubject.education_grade_subject_id', 'EducationSubject.id', 'EducationSubject.code', 'EducationSubject.name');
		}else if($searchMode =='grade'){
			$options['fields'] = array('EducationGradesSubject.id', 'EducationSubject.name', 'EducationSubject.code');
		}
		else{
			$options['fields'] = array('EducationSubject.id', 'EducationSubject.name', 'EducationSubject.code');
		}
		$options['recursive'] = -1;
		$options['joins'] = array(
			array(
				'table' => 'education_grades_subjects',
				'alias' => 'EducationGradesSubject',
				'conditions' => array('EducationGradesSubject.id = ClassSubject.education_grade_subject_id')
			),
			array(
				'table' => 'education_subjects',
				'alias' => 'EducationSubject',
				'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
			)
		);
		$options['conditions'] = array('ClassSubject.class_id' => $classId);
		$options['order'] = array('EducationSubject.code');
		
		$results = $this->find('all', $options);

		if($searchMode == 'list' ||  $searchMode=='grade'){
			$list = array();
			foreach($results as $obj){
				if($searchMode == 'list'){
					$list[$obj['EducationSubject']['id']] = sprintf("%s - %s", $obj['EducationSubject']['code'], $obj['EducationSubject']['name']);
				}else{
					$list[$obj['EducationGradesSubject']['id']] = sprintf("%s - %s", $obj['EducationSubject']['code'], $obj['EducationSubject']['name']);
				}
			}
			
			return $list;
		}
		
		return $results;
	}

	public function getSubjectByClassSubject($classId, $subjectId) {
		$data = $this->find('all', array(
			'fields' => array('ClassSubject.id', 'ClassSubject.education_grade_subject_id', 'EducationSubject.id', 'EducationSubject.code', 'EducationSubject.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradesSubject',
					'conditions' => array('EducationGradesSubject.id = ClassSubject.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				)
			),
			'conditions' => array('ClassSubject.class_id' => $classId, 'EducationSubject.education_subject_id' => $subjectId),
			'order' => array('EducationSubject.code')
		));

		return $data;
	}

	public function getSubjectByGrade($gradeId) {
		$data = $this->find('all', array(
			'fields' => array('ClassSubject.id', 'ClassSubject.education_grade_subject_id', 'EducationSubject.code', 'EducationSubject.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradesSubject',
					'conditions' => array('EducationGradesSubject.id = ClassSubject.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				)
			),
			'conditions' => array('EducationGradesSubject.education_grade_id' => $gradeId),
			'order' => array('EducationSubject.code')
		));

		return $data;
	}

	public function getSubjectByStudentId($studentId) {
		$data = $this->find('all', array(
			'fields' => array('ClassSubject.id', 'ClassSubject.education_grade_subject_id', 'EducationSubject.code', 'EducationSubject.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradesSubject',
					'conditions' => array('EducationGradesSubject.id = ClassSubject.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				),
				array(
					'table' => 'class_students',
					'alias' => 'ClassStudent',
					'conditions' => array('ClassStudent.class_id = ClassSubject.class_id')
				)
			),
			'conditions' => array('ClassStudent.student_id' => $studentId),
			'order' => array('EducationSubject.code')
		));

		return $data;
	}
	
	public function getSubjectsByClass($classId) {
		$data = $this->find('all', array(
			'fields' => array('ClassSubject.*', 'EducationSubject.*', 'EducationGrade.*'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradesSubject',
					'conditions' => array('EducationGradesSubject.id = ClassSubject.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				),
				array(
					'table' => 'education_grades',
					'alias' => 'EducationGrade',
					'conditions' => array('EducationGrade.id = EducationGradesSubject.education_grade_id')
				)
			),
			'conditions' => array('ClassSubject.class_id' => $classId),
			'order' => array('EducationGrade.order', 'EducationSubject.order')
		));
		return $data;
	}
	
	public function getSubjectsForEdit($classId) {
		$data = $this->EducationGradesSubject->find('all', array(
			'recursive' => -1,
			'fields' => array(
				'ClassSubject.id', 'ClassSubject.visible', 
				'EducationGrade.code', 'EducationGrade.name',
				'EducationSubject.code', 'EducationSubject.name', 
				'EducationGradesSubject.id'
			),
			'joins' => array(
				array(
					'table' => 'class_grades',
					'alias' => 'ClassGrade',
					'conditions' => array(
						'ClassGrade.education_grade_id = EducationGradesSubject.education_grade_id',
						'ClassGrade.visible = 1',
						'ClassGrade.class_id = ' . $classId
					)
				),
				array(
					'table' => 'class_subjects',
					'alias' => 'ClassSubject',
					'type' => 'LEFT',
					'conditions' => array(
						'ClassSubject.class_id = ClassGrade.class_id',
						'ClassSubject.education_grade_subject_id = EducationGradesSubject.id'
					)
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				),
				array(
					'table' => 'education_grades',
					'alias' => 'EducationGrade',
					'conditions' => array('EducationGrade.id = EducationGradesSubject.education_grade_id')
				),
				array(
					'table' => 'education_programmes',
					'alias' => 'EducationProgramme',
					'conditions' => array('EducationProgramme.id = EducationGrade.education_programme_id')
				)
			),
			'order' => array('ClassSubject.id DESC', 'EducationProgramme.order', 'EducationGrade.order', 'EducationSubject.order')
		));
		return $data;
	}
}
