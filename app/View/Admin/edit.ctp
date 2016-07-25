<?php
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->css('../js/plugins/fileupload/bootstrap-fileupload', array('inline' => false));
echo $this->Html->script('plugins/fileupload/bootstrap-fileupload', false);
echo $this->Html->script('holder', false);

$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $header);
$this->assign('portletHeader', $header);
$this->assign('tabHeader', $this->Label->get('general.general'));

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => 'view'));
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
	$('#InstitutionSite_date_opened, #InstitutionSite_date_closed').datepicker();
});
</script>

<?php
$this->end();
?>
