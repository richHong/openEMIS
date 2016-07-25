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

class StudentAttendanceType extends AppModel {
	public $actsAs = array('FieldOption');
	public $hasMany = array('StudentAttendanceDay', 'StudentAttendanceLesson');
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
			'short_form' => array(
				'ruleRequired' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('shortForm')
				)
			)
		);
	}
	
	public function getOptionFields() {
		$fields = parent::getOptionFields();
		$fields['short_form']['labelKey'] = 'general';
		$fields['short_form']['visible'] = array('index' => true, 'view' => true, 'edit' => true);
		return $fields;
	}
	
	public function getAttendanceList($type = 'all', $showAll = false){
		if($type == 'list'){
			$options['fields'] = array('id', 'name');
		}
		else{
			$options['fields'] = array('id', 'name', 'short_form');
		}
				if(!$showAll){
				$options['conditions'] = array('visible' => 1); 
				}
		$data = $this->find($type, $options);	
		
		return $data;
	}
}
?>