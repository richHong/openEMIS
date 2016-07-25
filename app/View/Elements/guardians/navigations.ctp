<?php
$navigations = array(
	$this->Label->get('general.general') => array('controller' => 'Guardians', 'action' => 'view', 'selected' => array('view', 'edit')),
	$this->Label->get('general.profileImage') => array('controller' => 'Guardians', 'action' => 'profileImage'),
	// PHPSM-54: Include contacts tab
    $this->Label->get('general.contact') => array('controller' => 'Guardians', 'action' => 'contact', 'selected' => '^contact'),
    // END PHPSM-54

);

echo $this->element('layout/navigations', array('navigations' => $navigations));
?>
