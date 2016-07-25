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

class AssessmentItemResult extends AppModel {
	public $displayField = 'id';
	public $belongsTo = array(
		'AssessmentItem',
		'AssessmentResult',
		'AssessmentResultType',
		'Student',
		'SchoolYear'
	);

	public function getAssessmentResultByStudentId($studentId, $schoolYearId) {
		$data = $this->find('all', array(
			'fields' => array('AssessmentItem.id', 'AssessmentItem.min', 'AssessmentItem.max', 'AssessmentItem.weighting', 
				'EducationSubject.name', 'EducationSubject.code', 'AssessmentItemType.name', 'AssessmentItemType.code',
				'AssessmentResult.id', 'AssessmentResultType_Item.id', 'AssessmentResultType_Item.name', 'AssessmentResultType_Result.id', 
				'AssessmentResultType_Result.name', 'AssessmentItemResult.marks', 'AssessmentResult.marks', 'EducationSubject.id',
				'SchoolYear.name'
				),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'assessment_result_types',
					'type' => 'LEFT',
					'alias' => 'AssessmentResultType_Item',
					'conditions' => array('AssessmentResultType_Item.id = AssessmentItemResult.assessment_result_type_id')
				),
				array(
					'table' => 'assessment_results',
					'type' => 'LEFT',
					'alias' => 'AssessmentResult',
					'conditions' => array('AssessmentResult.id = AssessmentItemResult.assessment_result_id')
				),
				array(
					'table' => 'assessment_items',
					'alias' => 'AssessmentItem',
					'conditions' => array('AssessmentItem.id = AssessmentItemResult.assessment_item_id')
				),
				array(
					'table' => 'assessment_result_types',
					'type' => 'LEFT',
					'alias' => 'AssessmentResultType_Result',
					'conditions' => array('AssessmentResultType_Result.id = AssessmentResult.assessment_result_type_id')
				),
				array(
					'table' => 'assessment_item_types',
					'alias' => 'AssessmentItemType',
					'conditions' => array('AssessmentItemType.id = AssessmentItem.assessment_item_type_id')
				),
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradesSubject',
					'conditions' => array('EducationGradesSubject.id = AssessmentItem.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				),
				array(
					'table' => 'school_years',
					'alias' => 'SchoolYear',
					'conditions' => array('SchoolYear.id = AssessmentItemResult.school_year_id')
				)
			),
			'conditions' => array('AssessmentItemResult.student_id' => $studentId, 'AssessmentItemResult.school_year_id' => $schoolYearId),
			'order' => array('EducationSubject.order', 'AssessmentItemType.id')
		));

		return $data;
	}

	public function getYearOptions($studentId) {
		$data = $this->find('list', array(
			'recursive' => 0,
			'fields' => array('SchoolYear.id', 'SchoolYear.name'),
			'conditions' => array('AssessmentItemResult.student_id' => $studentId),
			'order' => array('SchoolYear.start_year')
		));
		return $data;
	}


	public function getAssessmentResultOverviewByStudentId($studentId, $schoolYearId){
		$data = $this->find('all', array(
			'fields' => array('DISTINCT EducationSubject.name', 'EducationSubject.code', 'AssessmentItemResult.marks', 'EducationSubject.id',
				'SchoolYear.name','AssessmentItemResult.id', 'AssessmentResultType.name', 'AssessmentItem.assessment_item_type_id', 'EducationGradesSubject.education_grade_id', 'AssessmentItemType.name'
				),
			// 'field' => array(
			// 	'DISTINCT EducationSubject.*',
			// 	'AssessmentItemResult.*',
			// 	'SchoolYear.*',
			// 	'AssessmentResultType.*',
			// 	'AssessmentItem.*'
			// ),
			'recursive' => -1,
			'joins' => array(
				/*array(
					'table' => 'assessment_results',
					'type' => 'LEFT',
					'alias' => 'AssessmentResult',
					'conditions' => array('AssessmentResult.id = AssessmentItemResult.assessment_result_id')
				),*/
				array(
					'table' => 'assessment_result_types',
					'type' => 'LEFT',
					'alias' => 'AssessmentResultType',
					'conditions' => array('AssessmentResultType.id = AssessmentItemResult.assessment_result_type_id')
				),
				array(
					'table' => 'assessment_items',
					'alias' => 'AssessmentItem',
					'conditions' => array('AssessmentItem.id = AssessmentItemResult.assessment_item_id')
				),
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradesSubject',
					'conditions' => array('EducationGradesSubject.id = AssessmentItem.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
				),
				array(
					'table' => 'school_years',
					'alias' => 'SchoolYear',
					'conditions' => array('SchoolYear.id = AssessmentItemResult.school_year_id')
				),
				array(
					'table' => 'assessment_item_types',
					'alias' => 'AssessmentItemType',
					'conditions' => array('AssessmentItemType.id = AssessmentItem.assessment_item_type_id')
				)
			),
			'conditions' => array('AssessmentItemResult.student_id' => $studentId, 'AssessmentItemResult.school_year_id' => $schoolYearId),
			'order' => array('EducationSubject.order','AssessmentItem.assessment_item_type_id')
		));

		return $data;
	}

	public function getAssessmentResultByClassIdSubjectId($classId, $subjectId) {
		$subjects = '';
		$ai = ClassRegistry::init('AssessmentItem');

		$cs = ClassRegistry::init('ClassStudent');

		$assessments = $ai->getAssessmentByClassIdSubjectId($classId, $subjectId);
		$students = $cs->getStudentsByClass($classId); 


		$result = array();


		foreach($students as $student){
			$format = array();
			$format['Student']['student_id'] = $student['Student']['id'];
			$format['SecurityUser']['first_name'] = $student['SecurityUser']['first_name'];
			$format['SecurityUser']['middle_name'] = $student['SecurityUser']['middle_name'];
			$format['SecurityUser']['last_name'] = $student['SecurityUser']['last_name'];
			$format['SecurityUser']['openemisid'] = $student['SecurityUser']['openemisid'];


			$finalResult = 0;
			$resultId = array();
			$assessmentResultId = '';
			$assessment_result_type_id = '';
			$assesment_result_type = '';
			foreach($assessments as $assessment){
				$data = $this->find('all', array(
					'fields' => array('AssessmentItemResult.marks', 'AssessmentResult.id', 'AssessmentResult.marks', 'AssessmentItemResult.id', 'AssessmentItemResult.assessment_item_id', 'AssessmentResultType.id', 'AssessmentResultType.name',
						'AssessmentResultType_Result.id', 'AssessmentResultType_Result.name'

						),
					'recursive' => -1,
					'joins' => array(
						array(
							'table' => 'assessment_result_types',
							'type' => 'LEFT',
							'alias' => 'AssessmentResultType',
							'conditions' => array('AssessmentResultType.id = AssessmentItemResult.assessment_result_type_id')
						),
						array(
							'table' => 'assessment_results',
							'type' => 'LEFT',
							'alias' => 'AssessmentResult',
							'conditions' => array('AssessmentResult.id = AssessmentItemResult.assessment_result_id')
						),
						array(
							'table' => 'assessment_result_types',
							'type' => 'LEFT',
							'alias' => 'AssessmentResultType_Result',
							'conditions' => array('AssessmentResultType_Result.id = AssessmentResult.assessment_result_type_id')
						)
					),
					'conditions' => array('AssessmentItemResult.assessment_item_id' => $assessment['AssessmentItem']['id'], 'AssessmentItemResult.student_id' => $student['Student']['id']),
					'order' => array('AssessmentItemResult.id')
				));
				$format['AssessmentItemResult'][$assessment['AssessmentItem']['id']] = array('assessment_item_id'=>$assessment['AssessmentItem']['id'], 'id' => null, 'name' => $assessment['AssessmentItemType']['name'], 'code' => $assessment['AssessmentItemType']['code'], 'weightage' => $assessment['AssessmentItem']['weightage'], 'marks' => '', 'format_marks' => '', 'actual_max_marks' => $assessment['AssessmentItem']['max'], 'result_type' => '', 'assessment_result_type_id' => null, 'assessment_result_id' => null);

				foreach($data as $obj){
					$format['AssessmentItemResult'][$assessment['AssessmentItem']['id']] = array('assessment_item_id'=>$assessment['AssessmentItem']['id'], 'id'=>$obj['AssessmentItemResult']['id'], 'name' => $assessment['AssessmentItemType']['name'], 'code' => $assessment['AssessmentItemType']['code'], 'weightage' => $assessment['AssessmentItem']['weightage'], 'marks' => $obj['AssessmentItemResult']['marks'], 'format_marks' => $obj['AssessmentItemResult']['marks'] . ' / ' . $assessment['AssessmentItem']['max'], 
						'actual_max_marks' => $assessment['AssessmentItem']['max'], 'result_type' => $obj['AssessmentResultType']['name'], 
						'assessment_result_type_id' => $obj['AssessmentResultType']['id'], 'assessment_result_id' => $obj['AssessmentResult']['id']);
					
					if(!in_array($obj['AssessmentResult']['id'], $resultId)){
						$finalResult += $obj['AssessmentResult']['marks'];
						array_push($resultId, $obj['AssessmentResult']['id']);
					}
					$assessmentResultId = $obj['AssessmentResult']['id'];
					$assessment_result_type_id = $obj['AssessmentResultType_Result']['id'];
					$assesment_result_type = $obj['AssessmentResultType_Result']['name'];
				}
			}
			$format['AssessmentResult'] = array('id'=> $assessmentResultId, 'marks' => $finalResult, 'assessment_result_type_id' => $assessment_result_type_id, 'result_type' => $assesment_result_type);
			$result[$student['Student']['id']] = $format;
		}


		return $result;
	}
}
