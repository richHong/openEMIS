<?php
/*
@OPENEMIS LICENSE LAST UPDATED ON 2013-05-16

OpenEMIS
Open Education Management Information System

Copyright � 2013 UNECSO.  This program is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free Software Foundation
, either version 3 of the License, or any later version.  This program is distributed in the hope 
that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE.See the GNU General Public License for more details. You should 
have received a copy of the GNU General Public License along with this program.  If not, see 
<http://www.gnu.org/licenses/>.  For more information please wire to contact@openemis.org.
*/

class OptionComponent extends Component {
    public $components = array('Message');

	public function get($code) {
		$options = array(
			'yesno' => array(0 => $this->Message->getLabel('general.no'), 1 => $this->Message->getLabel('general.yes')),
			'gender' => array('M' => $this->Message->getLabel('general.male'), 'F' => $this->Message->getLabel('general.female'))
		);
		
		$index = explode('.', $code);
		foreach($index as $i) {
			if(isset($options[$i])) {
				$option = $options[$i];
			} else {
				$option = array('[' . $this->Message->getLabel('general.optionNotFound') . ']');
				break;
			}
		}
		return $option;
	}

	public function handleOptionsInData($fields,$data) {
		$optionKey = array();
		foreach ($fields as $key => $value) {
			if (array_key_exists('options', $value)) {
				$optionKey[$key] = $value['options'];
			}
		}

		foreach ($data as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				foreach ($value2 as $key3 => $value3) {
					// pr($key3);
					if (array_key_exists($key3, $optionKey)) {
						if (array_key_exists($value3, $optionKey[$key3])) {
							$data[$key1][$key2][$key3] = $optionKey[$key3][$value3];
						}
					}
					
				}
			}
		}
		return $data;
	}
}
