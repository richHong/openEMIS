<?php
if ($this->Session->check('Security.accessViewType')) {
	$accessViewType = $this->Session->read('Security.accessViewType');
} else {
	// maybe want to kill the operation as the person is an unidentified user
}

switch ($accessViewType) {
	case 2: 
		$tabs = array(
			$this->Label->get('general.general') => array(
				'url' => array('controller' => 'Staff', 'action' => 'view'),
				'acoName' => 'StaffProfile',
				'selected' => array('view', 'edit')
			),
			$this->Label->get('general.contacts') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffContact'),
				'selected' => 'StaffContact'
			),
			$this->Label->get('StaffIdentity.name') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffIdentity'),
				'selected' => 'StaffIdentity'
			),
			$this->Label->get('general.timetable') => array(
				'url' => array('controller' => 'Staff', 'action' => 'Timetable'),
				'acoName' => 'StaffTimetable',
				'selected' => 'Timetable'
			),
			$this->Label->get('StaffEmployment.name') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffEmployment')
			),
			$this->Label->get('Attendance.title') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffAttendanceDay/staff_list')
			),
			$this->Label->get('StaffBehaviour.name') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffBehaviour'),
				'selected' => 'StaffBehaviour'
			),
			$this->Label->get('StaffAttachment.name') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffAttachment'),
				'selected' => 'StaffAttachment'
			),
			$this->Label->get('general.account') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffPassword'),
				'selected' => 'StaffPassword'
			)
		);
		break;
	default:
		$tabs = array(
			$this->Label->get('general.general') => array(
				'url' => array('controller' => 'Staff', 'action' => 'view'),
				'acoName' => 'StaffProfile',
				'selected' => array('view', 'edit')
			),
			$this->Label->get('general.contacts') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffContact'),
				'selected' => 'StaffContact'
			),
			$this->Label->get('StaffIdentity.name') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffIdentity'),
				'selected' => 'StaffIdentity'
			),
			$this->Label->get('general.timetable') => array(
				'url' => array('controller' => 'Staff', 'action' => 'Timetable'),
				'acoName' => 'StaffTimetable',
				'selected' => 'Timetable'
			),
			$this->Label->get('StaffEmployment.name') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffEmployment')
			),
			$this->Label->get('StaffBehaviour.name') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffBehaviour'),
				'selected' => 'StaffBehaviour'
			),
			$this->Label->get('StaffAttachment.name') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffAttachment'),
				'selected' => 'StaffAttachment'
			),
			$this->Label->get('general.account') => array(
				'url' => array('controller' => 'Staff', 'action' => 'StaffPassword'),
				'selected' => 'StaffPassword'
			)
		);
		break;

}

echo $this->element('layout/tabs', array('tabs' => $tabs));
?>
