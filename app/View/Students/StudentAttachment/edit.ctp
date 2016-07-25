<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader;

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model, 'view', $this->data[$model]['id']));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, $action, $this->data[$model]['id']));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	echo $this->Form->end();
$this->end();
?>
