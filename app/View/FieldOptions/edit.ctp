<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	$params = array('action' => 'view', $selectedOption, $selectedValue);
	if(isset($conditionId)) {
		$params = array_merge($params, array($conditionId => $selectedSubOption));
	}
	echo $this->FormUtility->link('back', $params);
$this->end();

$this->start('tabBody');
	$formURL = array_merge($params, array('action' => 'edit'));
	$formOptions = $this->FormUtility->getFormOptions($formURL);
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
$this->end();
?>
