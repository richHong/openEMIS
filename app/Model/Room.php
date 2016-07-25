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

class Room extends AppModel {
	public $hasMany = array(
		'ClassLesson',
		'TimetableEntry'
	);
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

    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'name' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('name')
                )
            )
        );
    }

	public function getOptionFields() {
		$fields = parent::getOptionFields();
		$fields['location']['labelKey'] = 'general';
		$fields['location']['visible'] = array('index' => true, 'view' => true, 'edit' => true);
		
		return $fields;
	}

	public function getRoomList($type='name', $order='DESC') {
		$value = 'Room.' . $type;
		$result = $this->find('list', array(
			'fields' => array('Room.id', $value),
			'order' => array($value . ' ' . $order)
		));
		return $result;
	}
}
