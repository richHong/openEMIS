<?php
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->script('app.form', array('inline' => false));
echo $this->Html->script('app.ajaxtable', array('inline' => false));

$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$id = '';
if (isset($this->data[$model])) {
	$id = $this->data[$model]['id'];
}

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model, ($action=='add' ? '' : 'view'), $id));
$this->end();

$this->prepend('portletBody');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, $action, $id));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	echo $this->Form->button('ReportCardTemplateComments', array('id' => 'ReportCardTemplateComments', 'type' => 'submit', 'name' => 'submit', 'value' => 'ReportCardTemplateComments', 'class' => 'hidden'));
	echo $this->Form->button('ReportCardTemplateSignatures', array('id' => 'ReportCardTemplateSignatures', 'type' => 'submit', 'name' => 'submit', 'value' => 'ReportCardTemplateSignatures', 'class' => 'hidden'));
	$this->Form->end();
$this->end();
?>

<script type="text/javascript">
	$(function () {
		$('#activeFromDate, #activeToDate, #displayDate').datepicker();
	});
</script>
