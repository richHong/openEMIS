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

class AutoGenerateIdBehavior extends ModelBehavior {
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array();
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
	}

	public function getUniqueID(Model $Model) {
		$roleName = (array_key_exists('module', $this->settings[$Model->alias]))? $this->settings[$Model->alias]['module']: null;
		$lowerCasedModelName = strtolower($roleName);

		$ConfigItem = ClassRegistry::init('ConfigItem');
		$prefix = $ConfigItem->find('first', array('limit' => 1,
			'fields' => 'ConfigItem.value',
			'conditions' => array(
				'ConfigItem.name' => $lowerCasedModelName.'_prefix'
			)
		));
		return $this->generateUniqueNumberForId($Model, $prefix['ConfigItem']['value']);
	} 

	private function generateUniqueNumberForId(Model $Model, $prefix="") {
		$generate_no = '';
		$prefix = explode(",", $prefix);
		$str = $Model->SecurityUser->find('first', array('order' => array('SecurityUser.id DESC'), 'limit' => 1, 'fields' => 'SecurityUser.id'));

		if ($prefix[0] > 0) {
			$id = $str['SecurityUser']['id'] + 1;
			if (strlen($id) < 6) {
				$str = str_pad($id, 6, "0", STR_PAD_LEFT);
			} else {
				$str = $id;
			}
			// Get two random number
			$rnd1 = rand(0, 9);
			$rnd2 = rand(0, 9);
			$generate_no = $prefix[1] . $str . $rnd1 . $rnd2;
		}

		return $generate_no;
	} 


}