<?php
switch ($attendance_view) {
	case 'Day':
		$attendanceModel = 'StudentAttendanceDay';
		break;
	case 'Lesson':
		$attendanceModel = 'StudentAttendanceLesson';
		break;
	default:
		$attendanceModel = 'StudentAttendanceDay';
		break;
}
$tabs = array(
	$this->Label->get('general.general') => array(
		'url' => array('controller' => 'Students', 'action' => 'view'),
		'acoName' => 'StudentProfile',
		'selected' => array('view', 'edit')
	),
	$this->Label->get('general.contacts') => array(
		'url' => array('controller' => 'Students', 'action' => 'StudentContact'),
		'selected' => 'StudentContact'
	),
	$this->Label->get('StudentIdentity.name') => array(
		'url' => array('controller' => 'Students', 'action' => 'StudentIdentity'),
		'selected' => 'StudentIdentity'
	),
	$this->Label->get('general.guardians') => array(
		'url' => array('controller' => 'Students', 'action' => 'StudentGuardian'),
		'selected' => 'StudentGuardian'
	),
	$this->Label->get('general.timetable') => array(
		'url' => array('controller' => 'Students', 'action' => 'Timetable'),
		'acoName' => 'StudentTimetable',
		'selected' => 'Timetable'
	),
	$this->Label->get('general.results') => array(
		'url' => array('controller' => 'Students', 'action' => 'StudentResult'),
		'selected' => 'StudentResult'
	),
	
	$this->Label->get('general.attendance') => array(
		'url' => array('controller' => 'Students', 'action' => $attendanceModel.'/index'),
		'selected' => 'Attendance'
	),

	$this->Label->get('StudentFee.name') => array(
		'url' => array('controller' => 'Students', 'action' => 'StudentFee/listing'),
		'selected' => 'StudentFee'
	),
	$this->Label->get('StudentBehaviour.name') => array(
		'url' => array('controller' => 'Students', 'action' => 'StudentBehaviour'),
		'selected' => 'StudentBehaviour'
	),
	$this->Label->get('general.attachments') => array(
		'url' => array('controller' => 'Students', 'action' => 'StudentAttachment'),
		'selected' => 'StudentAttachment'
	),
	$this->Label->get('general.reportCard') => array(
		'url' => array('controller' => 'Students', 'action' => 'StudentReportCard'),
		'selected' => 'StudentReportCard'
	),
	$this->Label->get('general.account') => array(
		'url' => array('controller' => 'Students', 'action' => 'StudentPassword'),
		'selected' => 'StudentPassword'
	)
);

echo $this->element('layout/tabs', array('tabs' => $tabs));
?>
