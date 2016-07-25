<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('back');
	echo $this->FormUtility->link('edit', array('action' => $model, 'edit', $data[$model]['id']));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Guardians/profile');
$this->end();

$this->start('tabBody');
	echo $this->element('layout/view');
$this->end();
?>
