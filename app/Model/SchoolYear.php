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

class SchoolYear extends AppModel {
	public $hasMany = array(
		'SClass',
		'AssessmentResult',
		'AssessmentItemType',
		'AssessmentItemResult'
	);
	
	public $actsAs = array(
		'FieldOption',
		'Year' => array('start_date' => 'start_year', 'end_date' => 'end_year'),
	);

    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'name' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('year')
                )
            ),
            'start_date' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('startDate')
                )
            ),
            'end_date' => array(
                'comparison' => array(
                    'rule'=>array('fieldComparison', '>', 'start_date'),
                    'allowEmpty'=>true,
                    'message' => $this->getErrorMessage('endDateGreater')
                )
            ),
            'school_days' => array(
                'required' => array(
                    'rule' => 'numeric',
                    'required' => true,
                    'message' => $this->getErrorMessage('schoolDay')
                )
            )
        );
    }
	
	public function getAllOptions($conditions) {
		$data = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $conditions,
			'order' => array($this->alias . '.order')
		));
		return $data;
	}
	
	public function getOptionFields() {
		$fields = parent::getOptionFields();
		$fields['start_date']['labelKey'] = 'date';
		$fields['start_date']['visible'] = array('index' => true, 'view' => true, 'edit' => true);
		$fields['start_year']['visible'] = false;
		$fields['end_date']['labelKey'] = 'date';
		$fields['end_date']['visible'] = array('index' => true, 'view' => true, 'edit' => true);
		$fields['end_year']['visible'] = false;
		$fields['school_days']['visible'] = array('index' => true, 'view' => true, 'edit' => true);
		
		return $fields;
	}

	public function getYearList($type='name', $order='DESC') {
		$value = 'SchoolYear.' . $type;
		$result = $this->find('list', array(
			'fields' => array('SchoolYear.id', $value),
			'order' => array($value . ' ' . $order)
		));
		return $result;
	}

	public function getCurrentSchoolYear() {
		$this->unbindModel(
			array(
				'hasMany' => array(
					'AssessmentResult',
					'AssessmentItemType',
					'AssessmentItemResult'
				)
			)
		);

		$data = $this->find('first',
			array(
				'fields' => array('SchoolYear.name'),
				'conditions' => array(
					'SchoolYear.start_date <= CURRENT_DATE',
					'SchoolYear.end_date >= CURRENT_DATE'
				),
				'order' => 'SchoolYear.end_date desc'
			)
		);
		return $data;
	}
}
