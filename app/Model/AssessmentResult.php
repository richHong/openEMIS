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
/**
 * AssessmentResult Model
 *
 * @property AssessmentResultType $AssessmentResultType
 * @property Student $Student
 * @property SchoolYear $SchoolYear
 * @property ModifiedUser $ModifiedUser
 * @property CreatedUser $CreatedUser
 * @property AssessmentItemResult $AssessmentItemResult
 */
class AssessmentResult extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

	// toDelete: this function is actually not being used... but just leaving around in case it might be useful - DONT USE IT... USE THE ONE IN AssessmentItemResult
	public function getAssessmentResultByStudentId($studentId, $schoolYearId) {
		$data = $this->find('all', array(
			'fields' => array('AssessmentResultType.id', 'AssessmentResultType.name', 'AssessmentResult.marks'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'assessment_result_types',
					'alias' => 'AssessmentResultType',
					'conditions' => array('AssessmentResultType.id = AssessmentResult.assessment_result_type_id')
				)
			),
			'conditions' => array('AssessmentResult.student_id' => $studentId, 'AssessmentResult.school_year_id' => $schoolYearId),
			'order' => array('AssessmentResultType.order')
		));

		return $data;
	}



	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'AssessmentResultType' => array(
			'className' => 'AssessmentResultType',
			'foreignKey' => 'assessment_result_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SchoolYear' => array(
			'className' => 'SchoolYear',
			'foreignKey' => 'school_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'AssessmentItemResult' => array(
			'className' => 'AssessmentItemResult',
			'foreignKey' => 'assessment_result_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
