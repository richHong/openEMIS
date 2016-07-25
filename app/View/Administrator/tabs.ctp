<?php
$tabs = array(
	$this->Label->get('general.general') => array(
		'url' => array('controller' => 'Administrator', 'action' => 'view'),
		'selected' => array('view', 'edit')
	),
	$this->Label->get('general.account') => array(
		'url' => array('controller' => 'Administrator', 'action' => 'AdministratorPassword'),
		'selected' => 'AdministratorPassword'
	)
);

echo $this->element('layout/tabs', array('tabs' => $tabs));
?>