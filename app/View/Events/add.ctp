<?php
$this->extend('/Layouts/portlet');

$this->assign('contentHeader', $contentHeader);

$this->start('portletHeader');
	echo $this->Icon->get('calendar');
	echo $this->Label->get('event.title');
$this->end();

$this->start('portletBody');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => 'add'));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	echo $this->Form->end();
$this->end();
?>
