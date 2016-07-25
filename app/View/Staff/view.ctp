<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $this->Label->get('general.general'));

$this->start('tabActions');
	echo $this->FormUtility->link('edit', array('action' => 'edit', $data[$model]['id']));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Staff/profile');
$this->end();

$this->start('tabBody');
	echo $this->element('layout/view');
$this->end();
?>