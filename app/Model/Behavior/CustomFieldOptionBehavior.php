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

class CustomFieldOptionBehavior extends ModelBehavior {
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array();
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
		if (!array_key_exists('module', $this->settings[$Model->alias])) {
			pr('Please set a module for CustomFieldOptionBehavior');die;
		}
	}

	public function beforeValidate(Model $Model, $options=array()) {
		$roleModel = $this->settings[$Model->alias]['module'];
		$fieldModel = $roleModel.'CustomField';

		if (array_key_exists($Model->alias, $Model->data)) {
			$Model->validate = array(
				'value' => array(
					'size' => array(
						'rule' => array('between', 1, 250),
						'message' => $Model->getErrorMessage('btw1And250')
					)					
				)
			);
		}

	}
}