<?php
$controller = $this->params['controller'];
$navigations = array(
	$this->Label->get('general.general') => array('controller' => $controller, 'action' => 'view', 'selected' => array('view', 'edit')),
	$this->Label->get('general.profileImage') => array('controller' => $controller, 'action' => 'profileImage'),
	$this->Label->get('general.contact') => array('controller' => 'Staff', 'action' => 'contact', 'selected' => '^contact'), // PHPSM-54: Include contacts tab
	$this->Label->get('general.timetable') => array('controller' => $controller, 'action' => 'timetable_view'),

	//'Qualifications' => array('controller' => $controller, 'action' => 'index'),
	//'Training' => array('controller' => $controller, 'action' => 'index'),
	$this->Label->get('staff.employment') => array('controller' => $controller, 'action' => 'employment', 'selected' => '^employment'),
	//'Attendance' => array('controller' => $controller, 'action' => 'attendance_view'),
	$this->Label->get('general.behaviour') => array('controller' => $controller, 'action' => 'behaviour', 'selected' => '^behaviour'),
	$this->Label->get('general.attachments') => array('controller' => $controller, 'action' => 'attachment', 'selected' => '^attachment')
	//'Access' => array('controller' => $controller, 'action' => 'index')
);

echo $this->element('layout/navigations', array('navigations' => $navigations));
?>
