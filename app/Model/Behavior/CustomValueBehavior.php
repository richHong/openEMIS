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

class CustomValueBehavior extends ModelBehavior {

	public $fieldId;
	public $fieldType;
	public $roleModel;
	public $fieldModel;
	public $valueVariable;

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array();
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
		if (!array_key_exists('module', $this->settings[$Model->alias])) {
			pr('Please set a module for CustomsValueBehavior');die;
		}
	}

	public function beforeValidate(Model $Model, $options=array()) {
		$this->roleModel = $this->settings[$Model->alias]['module'];
		$this->fieldModel = $this->roleModel.'CustomField';
		if (array_key_exists($Model->alias, $Model->data)) {
			if (array_key_exists(strtolower($this->roleModel).'_custom_field_id', $Model->data[$Model->alias])) {
				$custom_field_id = $Model->data[$Model->alias][strtolower($this->roleModel).'_custom_field_id'];
				
				$customFieldData = $Model->{$this->fieldModel}->find(
					'first',
					array(
						'recursive' => -1,
						'fields' => array($this->fieldModel.'.type',$this->fieldModel.'.is_mandatory',$this->fieldModel.'.is_unique',$this->fieldModel.'.id'),
						'conditions' => array('id' => $custom_field_id)
					)
				);

				
				$this->fieldId = $customFieldData[$this->fieldModel]['id'];
				$this->fieldType = $customFieldData[$this->fieldModel]['type'];
				$is_mandatory = $customFieldData[$this->fieldModel]['is_mandatory'];
				$is_unique = $customFieldData[$this->fieldModel]['is_unique'];

				$this->valueVariable = $Model->{$this->fieldModel}->getValueNameFromType($this->fieldType);

				switch ($this->fieldType) {
					case '1':
						 $Model->validate = array(
							'text_value' => array(
								'size' => array(
									'rule' => array('between', 0, 250),
									'message' => $Model->getErrorMessage('under250')
								)
							)
						);
						break;

					case '2':
						$Model->validate = array(
						);
						break;

					case '3':
						$Model->validate = array(
							'text_value' => array(
								'rule' => array('naturalNumber', true),
								'allowEmpty' => true,
								'message' => $Model->getErrorMessage('naturalNumber')
							)

						);						
						break;

					case '4':
						$Model->validate = array(
						);
					default:
						$Model->validate = array();
						break;
				}
			}

			if ($is_mandatory) {
				$Model->validate[$this->valueVariable]['required']['rule'] = 'notEmpty';
				$Model->validate[$this->valueVariable]['required']['message'] = $Model->getErrorMessage('required');
			}

			// in the case of 2. text area and 4. select... do not check for unique 
			if ($is_unique && $Model->{$this->fieldModel}->hasIsUnique($this->fieldType)) {
				$Model->validate[$this->valueVariable]['unique']['rule'] = 'isUniqueValue';
				$Model->validate[$this->valueVariable]['unique']['message'] = $Model->getErrorMessage('unique');
			}
		}
	}

	public function isUniqueValue(Model $Model) {
		if (array_key_exists($this->valueVariable, $Model->data[$Model->alias]) && $Model->data[$Model->alias][$this->valueVariable]!='') {
			$conditions = 
				array('AND' =>
					array(
						strtolower($this->roleModel).'_custom_field_id'=> $this->fieldId,
						$this->valueVariable => $Model->data[$Model->alias][$this->valueVariable],
						strtolower($this->roleModel).'_id !=' => $Model->data[$Model->alias][strtolower($this->roleModel).'_id']
					)
				);	
			if ($Model->hasAny($conditions)) {
				return false;
			}
		}
		return true;
	}


}
