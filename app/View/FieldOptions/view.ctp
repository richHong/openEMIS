<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	$params = array('action' => 'index', $selectedOption);
	if(isset($conditionId)) {
		$params = array_merge($params, array($conditionId => $selectedSubOption));
	}
	echo $this->FormUtility->link('back', $params);
	if ($editable) {
		$params = array('action' => 'edit', $selectedOption, $selectedValue);
		if(isset($conditionId)) {
			$params = array_merge($params, array($conditionId => $selectedSubOption));
		}
		echo $this->FormUtility->link('edit', $params);
	}
$this->end();

$this->start('tabBody');
	echo $this->element('layout/view');
$this->end();
?>

