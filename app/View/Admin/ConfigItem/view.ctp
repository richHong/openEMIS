<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $this->Label->get($model.'.title'));

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model, 'index', $type));
	echo $this->FormUtility->link('edit', array('action' => $model, 'edit', $data[$model]['id'], 'type' => $type));
$this->end();

$this->start('tabBody');
	echo $this->element('layout/view');
$this->end();
?>
