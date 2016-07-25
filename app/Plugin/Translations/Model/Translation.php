<?php
/*
@OPENEMIS LICENSE LAST UPDATED ON 2013-05-16

OpenEMIS
Open Education Management Information System

Copyright © 2013 UNECSO.  This program is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free Software Foundation
, either version 3 of the License, or any later version.  This program is distributed in the hope 
that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE.See the GNU General Public License for more details. You should 
have received a copy of the GNU General Public License along with this program.  If not, see 
<http://www.gnu.org/licenses/>.  For more information please wire to contact@openemis.org.
*/

App::uses('AppModel', 'Model');

class Translation extends AppModel {
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
	
	public $validate = array(
		'eng' => array(
			'ruleRequired' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Please ensure the english translation is keyed in.'
			)
		)
	);

	public function getFields($options=array()) {
		parent::getFields();
		return $this->fields;
	}

	public function get_all_elements_in_array($current_array, $final_array) {
		foreach($current_array as $key => $data) {
			if (is_array($data)) {
				$final_array = $this->get_all_elements_in_array($data, $final_array);
			} else {
				array_push($final_array, $data);
			}
		}
		return $final_array;
	}

	public function populate_database($transData) {
		$final_array = array();
		$processedData = array_unique($this->get_all_elements_in_array($transData, $final_array));
		$result = array();
		foreach ($processedData as $key => $data) {
			array_push($result, array('eng' => $data));
		}
		$count = 0;
		foreach ($result as $key => $data) {

			if (!$this->hasAny(array('eng' => $data['eng']))) {
				$this->save($data);
				echo 'saving --  '.print_r($data,true);
				$count++;
			}
			$this->clear();
			
		}
		return 'New Translations created: '.$count;
	}

}
