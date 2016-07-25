<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $this->Label->get($model.'.name'));

$this->start('tabActions');
	echo $this->FormUtility->link('back');
	echo $this->FormUtility->link('edit', array('action' => $model, 'edit', $data[$model]['id']));
	echo $this->FormUtility->link('deleteModal');
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
	echo $this->element('layout/view');
$this->end();

$this->start('modalBody');
	$url = array('controller' => $this->params['controller'], 'action' => $model.'/attachment_delete', $this->params['pass'][1]);
	echo $this->element('layout/deleteModal', array('url' => $url, 'model' => $model));
$this->end();
?>
