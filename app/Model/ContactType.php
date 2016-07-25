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

class ContactType extends AppModel {
	public $hasMany = array('StudentContact', 'StaffContact', 'GuardianContact');
	public $actsAs = array('FieldOption');
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
	
	// // PHPSM-54: Make sure new column (i.e. type) is reflected on view
	// public function getOptionFields() {
	// 	return array(
	// 		'type' => array(
	// 			'label' => 'Type',
	// 			'display' => true,
	// 			'options' => $this->getTypeArray()
	// 		)
	// 	);
	// }
	
	// public function getTypeArray() {
	// 	return array(
	// 		'1' => 'Mobile',
	// 		'2' => 'Phone',
	// 		'3' => 'Fax',
	// 		'4' => 'Email',
	// 		'5' => 'Other'
	// 	);
	// }
	// // END PHPSM-54
}
