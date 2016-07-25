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

class StudentCustomField extends AppModel {
	public $roleModel = 'Student';
	public $actsAs = array('CustomField' => array('module' => 'Student'));
	public $belongsTo = array(
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
	public $hasMany = array(
		'StudentCustomFieldOption' => array(
			'className' => 'StudentCustomFieldOption',
			'foreignKey' => 'student_custom_field_id',
		),
		'StudentCustomValue' => array(
			'className' => 'StudentCustomValue',
			'foreignKey' => 'student_custom_field_id',
		)
	);

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
			'is_mandatory' => array(
				'ruleRequired' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('required')
				)
			)
			,
			'is_unique' => array(
				'ruleRequired' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('required')
				)
			)
		);
    }

    public function getFields($options=array()) {
    	parent::getFields();

    	$this->fields['order']['visible'] = false;
    	$this->fields['visible']['visible'] = false;
    	$this->fields['type']['type'] = 'select';
    	$this->fields['type']['options'] = $this->getCustomFieldTypes();
    	$this->fields['is_mandatory']['type'] = 'select';
    	$this->fields['is_mandatory']['options'] = $this->getYesnoOptions();
    	$this->fields['is_unique']['type'] = 'select';
    	$this->fields['is_unique']['options'] = $this->getYesnoOptions();
    	return $this->fields;
    }

	public function getOptions($value='name', $orderBy='order', $order='asc', $conditions=array()) {
		return $this->find('list', array(
			'order' => array('name asc')));
	}
}