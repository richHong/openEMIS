<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	$params = array('action' => $model);
	if ($action == 'edit') {
		$params[] = 'view';
		$params[] = $this->data[$model]['id'];
	}
	echo $this->FormUtility->link('back', $params);
$this->end();

$this->start('tabBody');
	$params = array('action' => $model, $action);
	if ($action == 'edit') {
		$params[] = $this->data[$model]['id'];
	}
	$formOptions = $this->FormUtility->getFormOptions($params);
	echo $this->Form->create($model, $formOptions);
	if ($action == 'add') {
		echo $this->Form->hidden('order', array('value' => 0));
	}
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	echo $this->Form->end();
$this->end();
?>

