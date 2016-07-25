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

class AssessmentItemType extends AppModel {
	public $belongsTo = array(
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
	public $actsAs = array('Reorder');

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
            'school_year_id' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('year')
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
	
	public function getFields($options=array()) {
		parent::getFields();
		
		$yearOptions = array(0 => $this->Message->getLabel('general.allYears'));
		$yearOptions = array_merge($yearOptions, $this->SchoolYear->find('list', array('conditions' => array('visible' => 1), 'order' => 'start_year')));
		$gradeOptions = $this->EducationGrade->getProgrammeGradeOptions();
		
		if (!isset($options['action'])) {
			$options['action'] = '';
		}
		
		if ($options['action'] == 'add') {
			$this->fields['visible']['type'] = 'hidden';
			$this->fields['visible']['value'] = 1;
			$this->fields['order']['value'] = 0;
			$this->fields['order']['visible'] = array('edit' => true);
			$this->fields['education_grade_id']['visible'] = false;
		} else {
			$this->fields['visible']['type'] = 'select';
			$this->fields['visible']['options'] = $this->getStatusOptions();
			
			if ($options['action'] == 'edit') {
				$this->fields['education_grade_id']['type'] = 'disabled';
			} else {
				$this->fields['education_grade_id']['type'] = 'select';
			}
			$this->fields['education_grade_id']['options'] = $gradeOptions;
		}
		
		$this->fields['order']['type'] = 'hidden';
		$this->fields['school_year_id']['labelKey'] = 'SchoolYear';
		$this->fields['school_year_id']['type'] = 'select';
		$this->fields['school_year_id']['options'] = $yearOptions;
		$this->fields['class_id']['type'] = 'hidden';
		$this->fields['class_id']['value'] = 0;
		
		return $this->fields;
	}
	
	public function getAssessmentItemTypeList($type='name', $order='DESC') {
		$value = 'AssessmentItemType.' . $type;
		$result = $this->find('list', array(
			'fields' => array('AssessmentItemType.id', $value),
			'order' => array($value . ' ' . $order)
		));
		return $result;
	}
}
