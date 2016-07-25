<div class="form-group option-filter">
<?php
	$formOptions = $this->FormUtility->getFormOptions(array('controller' =>  $this->params['controller'], 'action' => $this->params['action']));
	$formOptions['inputDefaults']['div'] = false;
	$formOptions['inputDefaults']['label'] = false;
	$formOptions['inputDefaults']['between'] = '<div class="col-md-2">';
	
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->input('Today', array('type' => 'submit', 'class' => 'btn btn-primary', 'between'  => '<div class="button-wrapper">'));
	echo $this->FormUtility->datepicker('startDate', array(
		'id' => 'datepickerStart',
		'data-date' => $this->data[$model]['startDate'],
		'data-date-format' => 'yyyy-mm-dd',
		'url' => $this->params['controller'] . '/' . $model . '/index',
		'change' => 'Datepicker.change(this)'
	));
?>
	<div class="input-label"><?php echo $this->Label->get('general.to') ?></div>
<?php
	echo $this->Form->input('endDate', array('readonly'=> 'readonly','between' => '<div class="col-md-2">')); 
	echo $this->Form->end();
?>
</div>

<script type="text/javascript">
$(function () {
	$('#datepickerStart').datepicker();
});
</script>
