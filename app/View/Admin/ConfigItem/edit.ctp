<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $this->Label->get($model.'.title'));

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model, 'view', $this->data[$model]['id'], 'type' => $type));
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, $action, $this->data[$model]['id'], 'type' => $type));
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->hidden('id');
	echo $this->Form->hidden('name');
	echo $this->Form->input('type', array('disabled' => 'disabled'));
	echo $this->Form->input('label', array('disabled' => 'disabled'));
	echo $this->Form->input('description', array('disabled' => 'disabled'));
	echo $this->element('../Admin/ConfigItem/controls');
	echo $this->FormUtility->getFormButtons();
	echo $this->Form->end();
$this->end();
?>
