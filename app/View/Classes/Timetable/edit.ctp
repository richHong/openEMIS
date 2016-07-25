<?php
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));

$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $this->Label->get($model.'.name'));

$id = '';
if (isset($this->data[$model])) {
	$id = $this->data[$model]['id'];
}

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model, ($action=='add' ? '' : 'view'), $id));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, $action, $id));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	$this->Form->end();
?>

<script type="text/javascript">
$(function () {
	$('#Timetable_start_date').datepicker();
	$('#Timetable_end_date').datepicker();
});
</script>
<?php
$this->end();
?>