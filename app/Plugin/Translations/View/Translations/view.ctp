<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $this->Label->get($model.'.name'));

$this->start('tabActions');
	echo $this->FormUtility->link('back');
	echo $this->FormUtility->link('edit', array('action' => 'edit', $data[$model]['id']));
$this->end();

$this->prepend('portletBody');
$this->end();

$this->start('tabBody');
	echo $this->element('layout/view');
$this->end();
?>
