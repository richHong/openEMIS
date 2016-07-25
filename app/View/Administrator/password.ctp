<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Users/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => 'password', $id));
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->hidden('id', array('value' => $id));
	echo $this->Form->input('password', array('value' => ''));
	echo $this->Form->input('new_password', array('value' => '', 'type' => 'password'));
	echo $this->Form->input('confirm_new_password', array('value' => '', 'type' => 'password'));
	echo '<div class="col-md-offset-2 form-buttons">';
	echo $this->Form->button('Save', array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo '</div>';
	echo $this->Form->end();
$this->end();
?>