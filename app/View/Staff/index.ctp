<?php
$this->extend('/Layouts/portlet');

$this->assign('contentHeader', $contentHeader);

$this->start('portletHeader');
	echo $this->Icon->get('search');
	echo $this->Label->get('general.search');
$this->end();

$this->start('portletBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => 'search'));
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->input('staff_status_id', array('options' => $statusOptions, 'empty' => $this->Label->get('staff.all')));
	echo $this->Form->input('SecurityUser.openemisid', array('required' => false));
	echo $this->Form->input('SecurityUser.first_name', array('required' => false));
	echo $this->Form->input('SecurityUser.last_name', array('required' => false));
	echo '<div class="col-md-offset-2 form-buttons">';
	echo $this->Form->button($this->Label->get('general.search'), array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo '</div>';
	echo $this->Form->end();
$this->end();
?>
