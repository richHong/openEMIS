<?php
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->css('../js/plugins/fileupload/bootstrap-fileupload', array('inline' => false));
echo $this->Html->script('plugins/fileupload/bootstrap-fileupload', false);
echo $this->Html->script('holder', false);

$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $this->Label->get('general.general'));

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => 'view', $this->data[$model]['id']));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Staff/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => 'edit', $this->data[$model]['id']));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	$this->Form->end();
?>

<script type="text/javascript">
$(function () {
	$('#Staff_start_date').datepicker();
});
</script>

<?php
$this->end();
?>
