<?php
$staticFields = array();
$customFields = array();
$createModifiedFields = array();
foreach ($fields as $key => $field) {
	$options = array();
	$options['key'] = $key;
	$options['field'] = $field;
	$options['data'] = $data;
	if (!in_array($key, array('modified_user_id','modified','created_user_id','created'))) {
		array_push($staticFields, $this->FormUtility->getViewFormElement($options));
	} else {
		array_push($createModifiedFields, $this->FormUtility->getViewFormElement($options));
	}
}

if (isset($model)) {
	if (array_key_exists($model.'CustomField', $fields)) {
		foreach ($fields[$model.'CustomField'] as $key => $value) {
			$options = array();
			// pr($value);
			$options['field'] = $value[$model.'CustomField'];
			$options['data'] = $data[$model.'CustomValue'][$key];
			$options['formDefaults'] = $this->FormUtility->getFormDefaults();
			$options['rowHTML'] = $this->FormUtility->getRowHTML();
			array_push($customFields, $this->CustomField->getViewFormElement($options));
		}
	}
}

		
		// if (array_key_exists($key, $data[$fieldModel])) {
		// 	$value = $data[$fieldModel][$key];

		// 	switch ($fieldType) {
		// 		case 'select':
		// 			if (array_key_exists($value, $field['options'])) {
		// 				$value = $field['options'][$value];
		// 			}
		// 			break;

		// 		case 'text':
		// 			$value = nl2br($value);
		// 			break;

		// 		case 'boolean':
		// 			$value = $value ? 'True' : 'False';
		// 			break;

		// 		case 'image':
		// 			//$value = $this->Image->getBase64Image($data[$model][$key . '_name'], $data[$model][$key], $field['attr']);
		// 			break;
					
		// 		case 'download':
		// 			$value = $this->Html->link($value, $field['attr']['url']);
		// 			break;

		// 		case 'modified_user_id':
		// 		case 'created_user_id':
		// 			$dataModel = $field['dataModel'];
		// 			if (isset($data[$dataModel]['first_name']) && isset($data[$dataModel]['last_name'])) {
		// 				$value = $data[$dataModel]['first_name'] . ' ' . $data[$dataModel]['last_name'];
		// 			}
		// 			break;




$allFields = array_merge($staticFields,$customFields);
$allFields = array_merge($allFields,$createModifiedFields);
echo implode('', $allFields);
?>
