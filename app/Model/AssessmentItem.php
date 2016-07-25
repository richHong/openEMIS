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

class AssessmentItem extends AppModel {
	public $displayField = 'id';
	public $totalPercent = 0;
	
	public $belongsTo = array(
		'AssessmentItemType',
		'EducationGradesSubject' => array(
			'className' => 'EducationGradesSubject',
			'foreignKey' => 'education_grade_subject_id'
		)
	);
	public $hasMany = array('AssessmentItemResult');

	public $validate = array(
		/*
		'min' => array(
           'required' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please enter a valid Basic Mark'
			)
        ),
		'max' => array(
            'comparison' => array(
            	'rule'=>array('fieldComparison', '>', 'min'), 
            	'required'=>true,
            	'message' => 'Max Mark must be greater than Basic Mark'
            )
        ),
		'weightage' => array(
            'comparison' => array(
            	'rule'=>array('weightage_validate', 'weightage'), 
            	'required'=>true,
            	'message' => 'Total Weightage must be 100%'
            )
        )
		*/
	);
	

    function weightage_validate($check1, $field2){
    	if(($this->totalPercent*1)!=100){
    		return false;
    	}

    	return true;
    }

    function setWeightageData($data){
    	foreach($data as $key=>$value){
			$this->totalPercent += $value['weightage'];
    	}
    }
	
	public function getItems($typeId) {
		$class = get_class($this);
		$conditions = array($class.'.assessment_item_type_id' => $typeId, $class.'.visible' => 1);
		$data = $this->find('all', array(
			'recursive' => -1,
			'fields' => array(
				'EducationSubject.id', 'EducationSubject.code', 'EducationSubject.name', 'EducationGrade.id', 
				'AssessmentItem.id', 'AssessmentItem.min', 'AssessmentItem.max', 'AssessmentItem.weighting'
			),
			'joins' => array(
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradeSubject',
					'conditions' => array('EducationGradeSubject.id = AssessmentItem.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradeSubject.education_subject_id')
				),
				array(
					'table' => 'education_grades',
					'alias' => 'EducationGrade',
					'conditions' => array('EducationGrade.id = EducationGradeSubject.education_grade_id')
				)
			),
			'conditions' => $conditions,
			'order' => array('EducationSubject.order')
		));
		return $data;
	}
	
	public function getItemsForEdit($typeId) {
		$class = get_class($this);
		$options = array();
		
		$options['recursive'] = -1;
		$options['fields'] = array(
			'EducationSubject.id', 'EducationSubject.code', 'EducationSubject.name', 'EducationGradesSubject.id',
			'AssessmentItem.id', 'AssessmentItem.min', 'AssessmentItem.max', 'AssessmentItem.weighting', 'AssessmentItem.visible'
		);
		
		$options['joins'] = array(
			array(
				'table' => 'assessment_item_types',
				'alias' => 'AssessmentItemType',
				'conditions' => array(
					'AssessmentItemType.education_grade_id = EducationGradesSubject.education_grade_id',
					'AssessmentItemType.id = ' . $typeId
				)
			),
			array(
				'table' => 'education_subjects',
				'alias' => 'EducationSubject',
				'conditions' => array('EducationSubject.id = EducationGradesSubject.education_subject_id')
			),
			array(
				'table' => 'assessment_items',
				'alias' => 'AssessmentItem',
				'type' => 'LEFT',
				'conditions' => array(
					'AssessmentItem.assessment_item_type_id = AssessmentItemType.id',
					'AssessmentItem.education_grade_subject_id = EducationGradesSubject.id'
				)
			)
		);
		$options['order'] = array('AssessmentItem.id DESC', 'EducationSubject.order');
		
		$data = $this->EducationGradesSubject->find('all', $options);
		return $data;
	}
	
	public function getItemsByClassId($classId) {
		$class = get_class($this);
		$conditions = array($class.'.class_id' => $classId);
		$data = $this->find('all', array(
			'recursive' => -1,
			'fields' => array('EducationSubject.*', 'EducationGrade.*', 'AssessmentItem.*'),
			'joins' => array(
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradeSubject',
					'conditions' => array('EducationGradeSubject.id = AssessmentItem.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradeSubject.education_subject_id')
				),
				array(
					'table' => 'education_grades',
					'alias' => 'EducationGrade',
					'conditions' => array('EducationGrade.id = EducationGradeSubject.education_grade_id')
				)
			),
			'conditions' => $conditions,
			'order' => array('EducationSubject.order')
		));
		return $data;
	}
        
    public function getItemsByClassIdAssessmentItemType($classId, $assessmentItemTypeId) {
		$class = get_class($this);
		$conditions = array($class.'.class_id' => $classId, $class.'.assessment_item_type_id' => $assessmentItemTypeId);
		$data = $this->find('all', array(
			'recursive' => -1,
			'fields' => array('EducationSubject.*', 'EducationGrade.*', 'AssessmentItem.*'),
			'joins' => array(
				array(
					'table' => 'education_grades_subjects',
					'alias' => 'EducationGradeSubject',
					'conditions' => array('EducationGradeSubject.id = AssessmentItem.education_grade_subject_id')
				),
				array(
					'table' => 'education_subjects',
					'alias' => 'EducationSubject',
					'conditions' => array('EducationSubject.id = EducationGradeSubject.education_subject_id')
				),
				array(
					'table' => 'education_grades',
					'alias' => 'EducationGrade',
					'conditions' => array('EducationGrade.id = EducationGradeSubject.education_grade_id')
				)
			),
			'conditions' => $conditions,
			'order' => array('EducationSubject.order')
		));
		return $data;
	}

	public function getAssessmentByClassIdSubjectId($classId, $subjectId) {
		$data = $this->find('all', array(
			'fields' => array('AssessmentItem.id', 'AssessmentItem.min', 'AssessmentItem.max', 'AssessmentItem.weightage', 'AssessmentItemType.code', 
				'AssessmentItemType.name', 'AssessmentItem.education_grade_subject_id', 'AssessmentItem.assessment_item_type_id', 'EducationSubject.id', 'EducationSubject.id', 'EducationSubject.code', 'EducationSubject.name', 'AssessmentItem.class_id'),
			'recursive' => -1,
			'joins' => array(
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
				)
			),
			'conditions' => array('AssessmentItem.class_id' => $classId, 'EducationGradesSubject.id' => $subjectId),
			'order' => array('EducationSubject.order', 'AssessmentItem.class_id', 'AssessmentItemType.id')
		));

		return $data;
	}
}
