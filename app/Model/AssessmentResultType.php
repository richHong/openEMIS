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

class AssessmentResultType extends AppModel {
	public $actsAs = array('FieldOption');
	public $hasMany = array('AssessmentItemResult', 'AssessmentResult');
	public $belongsTo = array(
		'ModifiedUser' => array(
			'className' => 'SecurityUser',
			'fields' => array('first_name', 'last_name'),
			'foreignKey' => 'modified_user_id'
		),
		'CreatedUser' => array(
			'className' => 'SecurityUser',
			'fields' => array('first_name', 'last_name'),
			'foreignKey' => 'created_user_id'
		)
	);

	public function __construct() {
		parent::__construct();

		$this->validate = array(
			'min' => array(
				'ruleRequired' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('min')
				)
			),
			'max' => array(
				'ruleRequired' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('max')
				)
			)
		);
	}
	
	public function getOptionFields() {
		$fields = parent::getOptionFields();
		$displayFields = array('min', 'max');
		foreach ($displayFields as $f) {
			$fields[$f]['visible'] = array('index' => true, 'view' => true, 'edit' => true);
		}
		return $fields;
	}

	public function getAssessmentResultTypeList($type='name', $order='ASC') {
		$value = 'AssessmentResultType.' . $type;
		$orderField = 'AssessmentResultType.order';
		$result = $this->find('list', array(
			'fields' => array('AssessmentResultType.id', $value),
			'order' => array($orderField . ' ' . $order)
		));
		return $result;
	}
}
