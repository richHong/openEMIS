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

class ClassResult extends AppModel {
	public $useTable = 'assessment_item_results';
	
	public $belongsTo = array(
		'Student',
		'SchoolYear',
		'AssessmentItem',
		'AssessmentResultType'
	);

	public $accessMapping = array(
		'exportView' => 'execute'
	);
	
	public $actsAs = array('ControllerAction','Export' => array('module' => 'SClass'));
	
	public $EducationGrade;
	public $EducationSubject;

	public function __construct() {
		parent::__construct();
		$this->validate = array(
			'marks' => array(
				'decimal1' => array(
					'rule' => 'checkDecimalAndPositive',
					'message' => $this->getErrorMessage('decimal1AndPositive')
				)
			)
		);
	}

	public function checkDecimalAndPositive() {
		if (is_numeric($this->data[$this->alias]['marks'])) {
			$floatVal = floatVal($this->data[$this->alias]['marks']);
			if ($floatVal<0) {
				return false;
			} else {
				// check if it has 1 decimal place at most
				if (fmod($floatVal*10, 1) != 0) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	public function beforeAction() {
		parent::beforeAction();
		
		$this->setVar('header', $this->Message->getLabel($this->alias.'.title'));
		$this->Navigation->addCrumb($this->Message->getLabel($this->alias.'.title'));
		
		$this->EducationGrade = $this->AssessmentItem->EducationGradesSubject->EducationGrade;
		$this->EducationSubject = $this->AssessmentItem->EducationGradesSubject->EducationSubject;
	}
	
	public function index() {
		$classId = $this->Session->read('SClass.id');
		$subjects = $this->EducationSubject->find('list');
		$grades = $this->EducationGrade->find('list');
		$this->AssessmentItem->recursive = 0;

		$classId = $this->Session->read('SClass.id');
		
		// need to find the education grade that the class has is in
		$ClassSubject = ClassRegistry::init('ClassSubject');
		$subjectList = $ClassSubject->find(
			'list',
			array(
				'recursive' => 0,
				'fields' => array('EducationGradesSubject.id'),
				'conditions' => array(
					'ClassSubject.class_id' => $classId
				)
			)
		);

		$data = array();
		$data['Assessment'] = $this->AssessmentItem->find('all', array(
			'conditions' => array(
				'AssessmentItem.education_grade_subject_id' => $subjectList,
				'AssessmentItemType.class_id' => 0, 
				'AssessmentItemType.visible' => 1, 
				'AssessmentItem.visible' => 1
			),
			'order' => array(
				'AssessmentItemType.order', 'AssessmentItemType.id'
			)
		));
		
		$data['Assignment'] = $this->AssessmentItem->find('all', array(
			'conditions' => array('AssessmentItemType.class_id' => $classId, 'AssessmentItemType.visible' => 1, 'AssessmentItem.visible' => 1),
			'order' => array('AssessmentItemType.order', 'AssessmentItemType.id')
		));
		if(empty($data['Assessment'])&&empty($data['Assignment'])) $this->Message->alert('general.view.noRecords');

		// $data = $this->getListData();

		$this->setVar(compact('data', 'grades', 'subjects'));
	}
	
	public function view($id=0) {
		if ($this->AssessmentItem->exists($id)) {			
			$this->Session->write('ClassResult.id', $id);
			$obj = $this->AssessmentItem->findById($id);
			$this->EducationSubject->recursive = 0;
			$subject = $this->EducationSubject->findById($obj['EducationGradesSubject']['education_subject_id']);
			$header = $obj['AssessmentItemType']['name'] . ' - ' . $subject['EducationSubject']['name'];
			$data = $this->getListDataForView(array('id'=>$id));
			$this->setVar(compact('data', 'id', 'header'));
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => get_class($this)));
		}
	}
	
	public function edit($id=0) {
		if ($this->AssessmentItem->exists($id)) {
			$classId = $this->Session->read('SClass.id');
			$obj = $this->AssessmentItem->findById($id);
			$data = $this->getStudents($classId, $id);
			if ($this->request->is(array('post', 'put'))) {
				if (!empty($this->request->data[get_class($this)])) {
					if ($this->saveAll($this->request->data[get_class($this)])) {
						$this->Message->alert('general.edit.success');
						return $this->redirect(array('action' => get_class($this), 'view', $id));
					} else {
						foreach ($data as $key => $value) {
							$foundValueInPost = $this->findRelevantClassResultInPost($this->request->data, $value['ClassResult']['id']);

							$data[$key]['ClassResult'] = $foundValueInPost;
						}

						$this->Message->alert('general.edit.failed');
					}
				}
			}
			$this->EducationSubject->recursive = 0;
			$subject = $this->EducationSubject->findById($obj['EducationGradesSubject']['education_subject_id']);
			$header = $obj['AssessmentItemType']['name'] . ' - ' . $subject['EducationSubject']['name'];
			
			$resultTypeOptions = $this->AssessmentResultType->find('list', array('conditions' => array('visible' => 1)));
			
			$this->setVar(compact('data', 'id', 'header', 'resultTypeOptions'));
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => get_class($this)));
		}
	}

	public function reportGetFieldNames() {
		$fieldNames = $this->getFieldNamesFromData($this->reportData);

		unset($fieldNames['AssessmentItem.id']);
		unset($fieldNames['AssessmentItem.weighting']);
		unset($fieldNames['AssessmentItem.visible']);
		unset($fieldNames['AssessmentItem.assessment_item_type_id']);
		unset($fieldNames['AssessmentItem.education_grade_subject_id']);
		unset($fieldNames['EducationGradesSubject.id']);
		unset($fieldNames['EducationGradesSubject.education_grade_id']);
		unset($fieldNames['EducationGradesSubject.education_subject_id']);
		unset($fieldNames['EducationGradesSubject.modified_user_id']);
		unset($fieldNames['EducationGradesSubject.modified']);
		unset($fieldNames['EducationGradesSubject.created_user_id']);
		unset($fieldNames['EducationGradesSubject.created']);
		unset($fieldNames['AssessmentItemType.id']);
		unset($fieldNames['AssessmentItemType.id']);
		unset($fieldNames['AssessmentItemType.order']);
		unset($fieldNames['AssessmentItemType.visible']);
		unset($fieldNames['AssessmentItemType.school_year_id']);
		unset($fieldNames['AssessmentItemType.education_grade_id']);
		unset($fieldNames['AssessmentItemType.class_id']);
		unset($fieldNames['AssessmentItemType.modified_user_id']);
		unset($fieldNames['AssessmentItemType.modified']);
		unset($fieldNames['AssessmentItemType.created_user_id']);
		unset($fieldNames['AssessmentItemType.created']);

		return $fieldNames;
	}

	public function reportGetData() {
		$this->reportData = $this->getListData();

		$data1 = array();
		$data2 = array();
		if (isset($this->reportData['Assessment'])) {
			$data1 = $this->reportData['Assessment'];
		} else if (isset($this->reportData['Assignment'])) {
			$data2 = $this->reportData['Assignment'];
		}
		$this->reportData = array_merge($data1,$data2);
		return $this->reportData;
	}

	public function exportView() {
		$this->render = false;
		$data = array();
		if ($this->Session->check('ClassResult.id')) {
			$this->reportData = $this->getListDataForView(array('id'=>$this->Session->read('ClassResult.id')));
			$fieldNames = $this->getFieldNamesFromData($this->reportData);

			
		}
		$this->exportCSV($data);
	}

	public function getListData($options=array()) {
		// undone... implement in future after this is passed
		return $data;
	}

	public function getListDataForView($options=array()) {
		$data = array();
		if (array_key_exists('id', $options)) {
			$classId = $this->Session->read('SClass.id');
			$data = $this->getStudents($classId, $options['id']);	
		}
		return $data;
	}

	private function findRelevantClassResultInPost($data,$classResultId) {
		$foundValue = null;
		if (array_key_exists('ClassResult', $data)) {
			foreach ($data['ClassResult'] as $key => $value) {
				if ($value['id'] == $classResultId) {
					$foundValue = $value;
				}
			}
		}
		return $foundValue;
	}
	
	public function getStudents($classId, $assessmentItemId) {
		$this->AssessmentItem->recursive = 1;
		$assessmentItemData = $this->AssessmentItem->findById($assessmentItemId);

		$assessmentItemEducationGrade = $assessmentItemData['EducationGradesSubject']['education_grade_id'];
		$assessmentItemResults = $assessmentItemData['AssessmentItemResult'];

		$studentData = $this->Student->ClassStudent->find(
			'all',
			array(
				'recursive' => 1,
				'fields' => array('SClass.*','Student.*'),
				'conditions' => array(
					array(
						'ClassStudent.class_id' => $classId,
						'ClassStudent.education_grade_id' => $assessmentItemEducationGrade
					)
				)
			)
		);

		foreach ($studentData as $key => $value) {
			$studentData[$key] = array_merge($studentData[$key], 
				$this->find(
					'first',
					array(
						'conditions' => array(
							'ClassResult.assessment_item_id' => $assessmentItemId,
							'ClassResult.student_id' => $value['Student']['id']
						)
					)
				)
			);
			$this->Student->SecurityUser->recursive = -1;
			$studentData[$key] = array_merge($studentData[$key], $this->Student->SecurityUser->findById($value['Student']['security_user_id']));

			if(!array_key_exists('ClassResult', $studentData[$key])) {
				$studentData[$key]['ClassResult'] = array();
				$studentData[$key]['ClassResult']['marks'] = '';
				$studentData[$key]['ClassResult']['assessment_result_type_id'] = '';
			}
			if(!array_key_exists('AssessmentResultType', $studentData[$key])) {
				$studentData[$key]['AssessmentResultType'] = array();
				$studentData[$key]['AssessmentResultType']['name'] = '';
			}
		}
		return $studentData;
	}
}
