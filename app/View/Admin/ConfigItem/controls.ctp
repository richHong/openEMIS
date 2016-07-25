<?php
if (isset($valueType)) {
	switch ($valueType) {
		case 'toggleVal':
			$options = array(0 => $this->Label->get('general.no'), 1 => $this->Label->get('general.yes'));
			if (array_key_exists('value', $this->request->data['ConfigItem'])) {
				$toggleBool  = strtok($this->request->data['ConfigItem']['value'], ',');
				$toggleVal = strtok(',');
			}
			$valueToggleParams = array();
			$valueToggleParams['options'] = $options;
			if (isset($toggleBool)) {
				$valueToggleParams['value'] = $toggleBool;
			}
			if (isset($toggleVal)) {
				$valueValParams['value'] = $toggleVal;
			}
			echo $this->Form->input('enabled', $valueToggleParams);
			echo $this->Form->input('value', $valueValParams);
			break;

		case 'dropdown':
			echo $this->Form->input('value', array('options' => $options));
			break;

		case 'time':
			echo $this->FormUtility->timepicker('value', array('attr' => $attr));
			break;
			
		default:
			break;
	}
} else {
	echo $this->Form->input('value');
}
?>
