<div id="content">
	<div id="content-header">
		<h1><?php echo $this->fetch('contentHeader') ?></h1>
	</div>
	<div id="content-container">
		<?php echo $this->element('layout/breadcrumbs'); ?>
		<?php echo $this->fetch('contentBody') ?></div>
	</div>
</div>
<?php
if(isset($datepicker) && !empty($datepicker)) {
	echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
	echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
	echo $this->element('layout/datepicker');
}
if(isset($timepicker) && !empty($timepicker)) {
	echo $this->Html->css('../js/plugins/timepicker/bootstrap-timepicker.css', array('inline' => false));
	echo $this->Html->script('plugins/timepicker/bootstrap-timepicker', array('inline' => false));
	echo $this->element('layout/timepicker');
}
?>
