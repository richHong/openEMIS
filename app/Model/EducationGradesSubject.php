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

class EducationGradesSubject extends AppModel {
	public $belongsTo = array(
		'EducationGrade',
		'EducationSubject'
	);

	public $hasMany = array(
		'ClassSubject' => array(
			'foreignKey' => 'education_grade_subject_id'
		),
		'AssessmentItem' => array(
			'foreignKey' => 'education_grade_subject_id'
		)
	);
	public $actsAs = array('ControllerAction');
	
	public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb($this->Message->getLabel($this->alias . '.title'));
		
		$this->setVar('selectedPage', get_class($this));
		$this->setVar('header', $this->Message->getLabel($this->alias . '.title'));
	}
	
	public function index($selectedGrade=0) {
		$gradeOptions = $this->EducationGrade->getProgrammeGradeOptions();
		$data = array();
		
		if(!empty($gradeOptions)) {
			if(!array_key_exists($selectedGrade, $gradeOptions)) {
				$selectedGrade = key($gradeOptions);
			}
			$this->recursive = 0;
			$data = $this->findAllByEducationGradeId($selectedGrade);
		} else {
			$this->Message->alert($this->alias . '.noGrades');
		}
		
		$this->setVar('selectedSecondary', $selectedGrade);
		$this->setVar('secondaryOptions', $gradeOptions);
		$this->setVar('data', $data);
	}
	
	public function edit($selectedGrade=0) {
		$gradeOptions = $this->EducationGrade->getProgrammeGradeOptions();
		$data = array();
		
		if(!empty($gradeOptions)) {
			if(!array_key_exists($selectedGrade, $gradeOptions)) {
				$selectedGrade = key($gradeOptions);
			}
			if ($this->request->is(array('post', 'put'))) {
				$this->deleteAll(array('education_grade_id' => $selectedGrade));
				if(isset($this->request->data['EducationGradesSubject'])) {
					$data = $this->request->data['EducationGradesSubject'];
					foreach($data as $obj) {
						if(isset($obj['education_subject_id'])) {
							$obj['education_grade_id'] = $selectedGrade;
							$this->create();
							$this->save($obj);
						}
					}
				}
				$this->Message->alert('general.edit.success');
				return $this->redirect(array('action' => get_class($this), 'index', $selectedGrade));
			} else {
				$data = $this->EducationSubject->find('all', array(
					'recursive' => -1,
					'fields' => array('EducationSubject.*', 'EducationGradesSubject.*'),
					'joins' => array(
						array(
							'table' => 'education_grades_subjects',
							'alias' => 'EducationGradesSubject',
							'type' => 'LEFT',
							'conditions' => array(
								'EducationGradesSubject.education_subject_id = EducationSubject.id',
								'EducationGradesSubject.education_grade_id = ' . $selectedGrade
							)
						)
					),
					'order' => array('EducationGradesSubject.education_grade_id DESC', 'EducationSubject.order')
				));
			}
		} else {
			$this->Message->alert($this->alias . '.noGrades');
		}
		
		$this->setVar('secondaryOptions', $gradeOptions);
		$this->setVar('selectedSecondary', $selectedGrade);
		$this->setVar('data', $data);
	}
	
	public function getEducationGradesSubject() {
		$data = $this->find('all', array(
			'fields' => array('EducationGradesSubject.id', 'EducationSubject.code', 'EducationSubject.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				)
			)
		));

		$list = array();
		foreach($data as $obj){

			$list[$obj['EducationGradesSubject']['id']] = sprintf("%s - %s", $obj['EducationSubject']['code'], $obj['EducationSubject']['name']);
		}

		return $list;
	}
	
	public function getSubjectsByGrade($gradeId) {
		$data = $this->find('all', array(
			'recursive' => 0,
			'fields' => array('EducationGradesSubject.id', 'EducationSubject.id', 'EducationSubject.code', 'EducationSubject.name'),
			'conditions' => array('EducationGradesSubject.education_grade_id' => $gradeId),
			'order' => array('EducationSubject.order')
		));
		return $data;
	}

	public function getSubjectsByClass($classId) {
		$data = $this->find('all', array(
			'fields' => array('EducationSubject.id', 'EducationSubject.code', 'EducationSubject.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				),
				array(
					'table' => 'class_grades',
					'alias' => 'ClassGrade',
					'conditions' => array('ClassGrade.education_grade_id = EducationGradesSubject.education_grade_id')
				)
			),
			'conditions' => array('ClassGrade.class_id' => $classId),
			'order' => array('EducationSubject.name')
		));

		$list = array();
		foreach($data as $obj){
			$list[$obj['EducationSubject']['id']] = sprintf("%s - %s", $obj['EducationSubject']['code'], $obj['EducationSubject']['name']);
		}

		return $list;
	}
	
	public function getSubjectsWithEducationSubjectIdByClass($classId) {
		$data = $this->find('all', array(
			'fields' => array('EducationGradesSubject.id', 'EducationSubject.code', 'EducationSubject.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				),
				array(
					'table' => 'class_grades',
					'alias' => 'ClassGrade',
					'conditions' => array('ClassGrade.education_grade_id = EducationGradesSubject.education_grade_id')
				)
			),
			'conditions' => array('ClassGrade.class_id' => $classId),
			'order' => array('EducationSubject.name')
		));

		$list = array();
		foreach($data as $obj){
			$list[$obj['EducationGradesSubject']['id']] = sprintf("%s - %s", $obj['EducationSubject']['code'], $obj['EducationSubject']['name']);
		}

		return $list;
	}

	public function getSubjectsByClassGrade($gradeId) {
		$data = $this->find('all', array(
			'fields' => array('EducationGradesSubject.id', 'EducationSubject.code', 'EducationSubject.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				)
			),
			'conditions' => array('EducationGradesSubject.education_grade_id' => $gradeId),
			'order' => array('EducationSubject.name')
		));

		$list = array();
		foreach($data as $obj){
			$list[$obj['EducationGradesSubject']['id']] = sprintf("%s - %s", $obj['EducationSubject']['code'], $obj['EducationSubject']['name']);
		}

		return $list;
	}
	
	public function autocomplete($gradeId, $search) {
		//$search = sprintf('%%%s%%', $search);
		$list = $this->find('all', array(
			'recursive' => -1,
			'fields' => array('EducationGradesSubject.id', 'EducationSubject.code', 'EducationSubject.name'),
			'joins' => array(
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				)
			),
			'conditions' => array(
				'EducationGradesSubject.education_grade_id' => $gradeId,
				'OR' => array(
					'EducationSubject.code LIKE' => $search,
					'EducationSubject.name LIKE' => $search
				)
			),
			'order' => array('EducationGradesSubject.id', 'EducationSubject.code', 'EducationSubject.name')
		));

		
		$data = array();
		
		foreach($list as $obj) {
			$subjectId = $obj['EducationGradesSubject']['id'];
			$subjectCode = $obj['EducationSubject']['code'];
			$subjectName = $obj['EducationSubject']['name'];
			$subject = $subjectCode . ' - ' . $subjectName;
			
			$data[] = array(
				'label' => trim(sprintf('%s - %s', $subjectCode, $subjectName)),
				'value' => array('subject-id' => $subjectId, 'subject-name' => $subject)
			);
		}

		return $data;
	}
}