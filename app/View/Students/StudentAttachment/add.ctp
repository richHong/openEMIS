<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $this->Label->get($model.'.name'));

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, $action));
	echo $this->Form->create($model, $formOptions);
	
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	echo $this->Form->end();
$this->end();
?>
