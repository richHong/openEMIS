<div class="form-group option-filter">
<?php
	//$formOptions = $this->FormUtility->getFormDefaults();
	//pr($formOptions);
	$formOptions = $this->FormUtility->getFormOptions(array('controller' =>  $this->params['controller'], 'action' => $this->params['action']));
	$formOptions['inputDefaults']['div'] = false;
	$formOptions['inputDefaults']['label'] = false;
	$formOptions['inputDefaults']['between'] = '<div class="col-md-2">';
	$formOptions['id'] = 'datepickerForm';
	$formOptions['link'] = $this->params['controller']."/".$this->params['action'];
	if (isset($urlPath)) $formOptions['link'] = $urlPath;
	//pr($formOptions);
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->input('Today', array('type' => 'submit', 'class' => 'btn btn-primary', 'between'  => '<div class="button-wrapper">' , 'onclick' => 'Attendance.getTodayDate(this)'));
	echo $this->FormUtility->datepicker('startDate', array('id' => 'datepickerStart', 'data-date' => $this->data[$model]['startDate'], 'data-date-format' => "yyyy-mm-dd"));
?>
	<div class ='input-label'>to</div>
<?php
	echo $this->Form->input('endDate', array('readonly'=> 'readonly','between' => '<div class="col-md-2">')); 
  	if(!empty($formElements)){
		foreach($formElements as $element){
			echo $element. ' ';
		}
	}
	echo $this->Form->end();
?>
</div>
