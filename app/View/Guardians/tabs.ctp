<?php
$tabs = array(
	$this->Label->get('general.general') => array(
		'url' => array('controller' => 'Guardians', 'action' => 'view'),
		'selected' => array('view', 'edit')
	),
	$this->Label->get('general.contacts') => array(
		'url' => array('controller' => 'Guardians', 'action' => 'GuardianContact'),
		'selected' => 'GuardianContact'
	),
	$this->Label->get('GuardianIdentity.name') => array(
		'url' => array('controller' => 'Guardians', 'action' => 'GuardianIdentity'),
		'selected' => 'GuardianIdentity'
	),
	$this->Label->get('GuardianStudent.name') => array(
		'url' => array('controller' => 'Guardians', 'action' => 'GuardianStudent'),
		'selected' => 'GuardianStudent'
	),
	$this->Label->get('general.account') => array(
		'url' => array('controller' => 'Guardians', 'action' => 'GuardianPassword'),
		'selected' => 'GuardianPassword	'
	)
);

echo $this->element('layout/tabs', array('tabs' => $tabs));
?>
