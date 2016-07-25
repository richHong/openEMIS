<?php
echo $this->Html->css('../js/plugins/fileupload/bootstrap-fileupload', array('inline' => false));
echo $this->Html->script('plugins/fileupload/bootstrap-fileupload', false);
echo $this->Html->script('holder', false);

$this->extend('/Layouts/portlet');

$this->assign('contentHeader', $contentHeader);
$this->start('portletHeader');
	echo $this->Icon->get('add');
	echo $this->Label->get('Administrator.addAdmin');
$this->end();

$this->start('portletBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => 'add'));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->Form->input('SecurityUser.username', array('autocomplete' => 'off'));
	echo $this->Form->input('SecurityUser.password', array('autocomplete' => 'off'));
	echo '<div class="col-md-offset-2 form-buttons">';
	echo $this->Form->button($this->Label->get('general.add'), array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo '</div>';
	echo $this->Form->end();
?>

<?php
$this->end();
?>