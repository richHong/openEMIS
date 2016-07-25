<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Guardians/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => 'GuardianPassword','index'));
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->hidden('id', array('value' => $data['GuardianPassword']['id']));
	if (array_key_exists('username', $data['GuardianPassword']) && $data['GuardianPassword']['username']!='') {
		echo $this->Form->input('GuardianPassword.username', array('disabled' => 'disabled', 'value' => $data['GuardianPassword']['username']));
		echo $this->Form->input('password', array('value' => ''));
		echo $this->Form->input('new_password', array('value' => '', 'type' => 'password'));
		echo $this->Form->input('confirm_new_password', array('value' => '', 'type' => 'password'));
	} else {
		echo $this->Form->input('GuardianPassword.username', array('autocomplete' => 'off'));
		// echo $this->Form->input('password', array('value' => ''));
		echo $this->Form->input('new_password', array('value' => '', 'type' => 'password'));
		echo $this->Form->input('confirm_new_password', array('value' => '', 'type' => 'password'));
	}

	
	echo '<div class="col-md-offset-2 form-buttons">';
	echo $this->Form->button('Save', array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo '</div>';
	echo $this->Form->end();
$this->end();
?>