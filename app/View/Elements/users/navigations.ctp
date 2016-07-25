<?php
$controller = $this->params['controller'];
$navigations = array(
	$this->Label->get('general.details') => array('controller' => $controller, 'action' => 'view'),
	$this->Label->get('general.profileImage') => array('controller' => $controller, 'action' => 'profileImage'),
	$this->Label->get('general.changePassword') => array('controller' => $controller, 'action' => 'password')
);

echo $this->element('layout/navigations', array('navigations' => $navigations));
?>
