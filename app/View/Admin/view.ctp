<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $header);
$this->assign('portletHeader', $header);
$this->assign('tabHeader', $this->Label->get('general.general'));

$this->start('tabActions');
	echo $this->FormUtility->link('edit', array('action' => 'edit'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Admin/profile');
$this->end();

$this->start('tabBody');
	echo $this->element('layout/view');
$this->end();
?>
