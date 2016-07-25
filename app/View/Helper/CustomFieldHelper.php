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

App::uses('AppHelper', 'View/Helper');

class CustomFieldHelper extends AppHelper {
	public $helpers = array('Html', 'Form', 'Label');
	public $fieldTypes = array(
		1 => 'Label',
		2 => 'Text',
		3 => 'Integer',
		4 => 'Dropdown'
	);
	
	public function hasOptions($type) {
		return $type == 3 || $type == 4; // dropdown or checkbox
	}

	public function getValueNameFromType($thisType) {
		switch ($thisType) {
			case '1':
				$valueName = 'text_value';
				break;

			case '2':
				$valueName = 'textarea_value';
				break;

			case '3': case '4':
				$valueName = 'int_value';
				break;
			
			default:
				$valueName = null;
				break;
		}
		return $valueName;
	}

	public function getEditFormElement($options=array()) {		
		$field = (array_key_exists('field', $options))? $options['field']: null;
		if ($field==null) return '';
		$data = (array_key_exists('data', $options))? $options['data']: array();
		$formDefaults = (array_key_exists('formDefaults', $options))? $options['formDefaults']: '';
		$rowHTML = (array_key_exists('rowHTML', $options))? $options['rowHTML']: '';
		$index = (array_key_exists('index', $options))? $options['index']: '';
		$model = (array_key_exists('model', $options))? $options['model']: '';
		$roleId = (array_key_exists('roleId', $options))? $options['roleId']: null;

		$html = '';
		//1 -> varchar, 2 -> int, 3 -> textArea, 4 -> Multiple, 5 -> Textarea
		$fieldType = isset($field['type']) ? $field['type'] : null;
		$customFieldId = isset($field['id']) ? $field['id'] : null;

		$valueVar = $this->getValueNameFromType($fieldType);
		if ($valueVar==null) return '';

		$label = (array_key_exists('name', $field))? $field['name']: '';
		$value = (!empty($data) && array_key_exists($valueVar, $data))? $data[$valueVar]: '';
		$id = (!empty($data) && array_key_exists('id', $data))? $data['id']: null;

		$fieldModel = $model.'CustomValue';
		$fieldName = $fieldModel . '.' . $index . '.' . $valueVar;
		$fieldIdName = $fieldModel . '.' . $index . '.' . 'id';
		$roleIdName = $fieldModel . '.' . $index . '.' . strtolower($model). '_id';
		$customFieldIdName = $fieldModel . '.' . $index . '.' . strtolower($model). '_custom_field_id';
		$options = array();
		if(!empty($label)) {
			$options['label'] = array('text' => $label, 'class' => $formDefaults['label']['class']);
		}

		switch ($fieldType) {
			case '1': case '3':
				// handled below
				break;

			case '2':
				$options['type'] = 'textarea';
				break;

			case '4': 
				if (array_key_exists('option', $field) || empty($field['options'])) {
					$options['empty'] = '-- '.$this->Label->get('general.noData').' --';
				} else {
					if (isset($field['default'])) {
						$options['default'] = $field['default'];
					} else {
						$options['empty'] = '-- '.$this->Label->get('general.select').' --';
					}
				}
				if (isset($field['options'])) {
					$options['options'] = $field['options'];
				}
				if (isset($field['empty'])) {
					$options['empty'] = $field['empty'];
				}
				// if (!empty($this->request->data)) {
				// 	if(!empty($this->request->data[$fieldModel][$key])) {
				// 		$options['default'] = $this->request->data[$fieldModel][$key];
				// 	}
				// }
				break;
			default:
				# code...
				break;
		}

		$options['value'] = $value;
		$html .= $this->Form->input($fieldName, $options);
		if ($id != null) $html .= $this->Form->input($fieldIdName, array('value' => $id, 'type'=>'hidden'));
		if ($roleId != null) $html .= $this->Form->input($roleIdName, array('value' => $roleId, 'type'=>'hidden'));
		$html .= $this->Form->input($customFieldIdName, array('value' => $customFieldId, 'type'=>'hidden'));

		return $html;
	}


	public function getViewFormElement($options=array()) {
		(array_key_exists('field', $options))? $field = $options['field']: null;
		if ($field==null) return '';
		(array_key_exists('data', $options))? $data = $options['data']: array();
		(array_key_exists('formDefaults', $options))? $formDefaults = $options['formDefaults']: '';
		(array_key_exists('rowHTML', $options))? $rowHTML = $options['rowHTML']: '';

		$html = '';
		$fieldType = isset($field['type']) ? $field['type'] : null;

		$valueVar = $this->getValueNameFromType($fieldType);
		if ($valueVar==null) return '';

		$label = (array_key_exists('name', $field))? $field['name']: '';
		$value = (!empty($data))? $data[$valueVar]: '';

		switch ($fieldType) {
			case '4': 
				if (array_key_exists('options', $field)) {
					if ($field['options'] != null) {
						if (array_key_exists($value, $field['options'])) {
							$value = $field['options'][$value];
						}	
					}
				}
				break;
			default:
				# code...
				break;
		}
		return sprintf($rowHTML, $label, $value);;
	}
}