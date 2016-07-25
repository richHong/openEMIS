<?php
$defaults = $this->FormUtility->getFormDefaults();

$staticFields = array();
$customFields = array();
$createModifiedFields = array();

foreach($fields as $key => $field) {
	$options = array();
	$options['key'] = $key;
	$options['field'] = $field;
	$options['data'] = $this->request->data;
	$options['formDefaults'] = $defaults;
	if (!in_array($key, array('modified_user_id','modified','created_user_id','created'))) {
		array_push($staticFields, $this->FormUtility->getEditFormElement($options));
	} else {
		array_push($createModifiedFields, $this->FormUtility->getEditFormElement($options));
	}
}

if (isset($model)) {
	if (array_key_exists($model.'CustomField', $fields)) {
		$index = 0;
		foreach ($fields[$model.'CustomField'] as $key => $value) {
			$options = array();
			$options['field'] = $value[$model.'CustomField'];
			if ($this->request->data) $options['data'] = $this->request->data[$model.'CustomValue'][$key];
			$options['formDefaults'] = $this->FormUtility->getFormDefaults();
			$options['rowHTML'] = $this->FormUtility->getRowHTML();
			$options['index'] = $index;
			$options['model'] = $model;
			if (isset($roleId)) $options['roleId'] = $roleId;
			array_push($customFields, $this->CustomField->getEditFormElement($options));
			$index++;
		}
	}
}

	// $fieldType = isset($field['type']) ? $field['type'] : 'string';
	// $visible = $this->FormUtility->isFieldVisible($field, 'edit');
	// if ($visible) {
	// 	$fieldModel = array_key_exists('model', $field) ? $field['model'] : $model;
	// 	$fieldName = $fieldModel . '.' . $key;
	// 	$options = array();
	// 	$label = $this->Label->getLabel($fieldModel, $key, $field);
	// 	if(!empty($label)) {
	// 		$options['label'] = array('text' => $label, 'class' => $defaults['label']['class']);
	// 	}

	// 	switch ($fieldType) {
	// 		case 'disabled': 
	// 			$options['type'] = 'text';
	// 			$options['disabled'] = 'disabled';
	// 			if (isset($field['options'])) {
	// 				$options['value'] = $field['options'][$this->request->data[$fieldModel][$key]];
	// 			}
	// 			echo $this->Form->hidden($fieldName);
	// 			break;
				
	// 		case 'select':
	// 			if (array_key_exists('option', $field) || empty($field['options'])) {
	// 				$options['empty'] = '-- '.$this->Label->get('general.noData').' --';
	// 			} else {
	// 				if (isset($field['default'])) {
	// 					$options['default'] = $field['default'];
	// 				} else {
	// 					$options['empty'] = '-- '.$this->Label->get('general.select').' --';
	// 				}
	// 			}
	// 			if (isset($field['options'])) {
	// 				$options['options'] = $field['options'];
	// 			}
	// 			if (isset($field['empty'])) {
	// 				$options['empty'] = $field['empty'];
	// 			}

	// 			if (!empty($this->request->data)) {
	// 				if(!empty($this->request->data[$fieldModel][$key])) {
	// 					$options['default'] = $this->request->data[$fieldModel][$key];
	// 				}
	// 			}
	// 			break;
				
	// 		case 'text':
	// 			$options['type'] = 'textarea';
	// 			break;

	// 		case 'boolean':
	// 			$options['type'] = 'checkbox';
	// 			$options['style'] = 'float: left; width:15px';
	// 			$options['label']['class'] = '';
	// 			$options['label']['style'] = 'margin-top:10px';
	// 			break;
			
	// 		case 'hidden':
	// 			$options['type'] = 'hidden';
	// 			$options['label'] = false;
	// 			$options['div'] = false;
	// 			break;

// 	// 		case 'dataRows':
// 	// 			echo $this->element('layout/dataRows', array('dataRowName' => $key));
// 	// 			break;
			
// 			case 'image':
// 				$imgOptions = array();
// 				$imgOptions['field'] = 'photo_content';
// 				$imgOptions['width'] = '110';
// 				$imgOptions['height'] = '110';
// 				$imgOptions['label'] = $label;
// 				if (isset($this->data['SecurityUser']['photo_name']) && isset($this->data['SecurityUser']['photo_content'])) {
// 					$imgOptions['src'] = $this->Image->getBase64($this->data[
// 						'SecurityUser']['photo_name'], $this->data['SecurityUser']['photo_content']);
// 				}
// 				echo $this->element('layout/file_upload_preview', $imgOptions);
// 				break
// ;				
// 	// 		case 'date':
// 	// 			$attr = array('id' => $fieldModel . '_' . $key);
// 	// 			if (array_key_exists($fieldModel, $this->request->data)) {

	// 				if (array_key_exists($key, $this->request->data[$fieldModel])) {
	// 					$attr['data-date'] = $this->request->data[$fieldModel][$key];
	// 					$attr['data-date'] = date('d-m-Y', strtotime($attr['data-date']));
	// 				}
	// 			}
	// 			if (array_key_exists('attr', $field)) {
	// 				$attr = array_merge($attr, $field['attr']);
	// 			}
	// 			echo $this->FormUtility->datepicker($fieldName, $attr);
	// 			break;
				
	// 		case 'time':
	// 			$attr = array('id' => $fieldModel . '_' . $key);
				
	// 			if (array_key_exists('attr', $field)) {
	// 				$attr = array_merge($dateOptions, $field['attr']);
	// 			}
	// 			echo $this->FormUtility->timepicker($fieldName, $attr);
	// 			break;
	// 		case 'file':
	// 			echo $this->element('layout/attachment');
	// 			break;
	// 		case 'file_upload';
	// 			$attr = array('field' => $key);
	// 			echo $this->element('layout/attachment_upload', $attr);
	// 			break;
	// 		default:
	// 			break;
			
	// 	}
	// 	if (isset($field['value'])) {
	// 		$options['value'] = $field['value'];
	// 	}
	// 	if (!in_array($fieldType, array('image', 'date', 'time', 'file', 'file_upload', 'dataRows'))) {
	// 		echo $this->Form->input($fieldName, $options);
		

$allFields = array_merge($staticFields,$customFields);
$allFields = array_merge($allFields,$createModifiedFields);
echo implode('', $allFields);
?>
