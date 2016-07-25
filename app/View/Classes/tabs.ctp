<?php
$controller = 'Classes';
switch ($attendance_view) {
	case 'Day':
		$attendanceModel = 'ClassAttendanceDay';
		break;
	case 'Lesson':
		$attendanceModel = 'ClassAttendanceLesson';
		break;
	default:
		$attendanceModel = 'ClassAttendanceDay';
		break;
}
$tabs = array(
	$this->Label->get('general.general') => array(
		'url' => array('controller' => $controller, 'action' => 'view'),
		'acoName' => 'StaffProfile',
		'selected' => array('view', 'edit')
	),
	$this->Label->get('ClassStudent.title') => array(
		'url' => array('controller' => $controller, 'action' => 'ClassStudent'),
		'selected' => 'ClassStudent'
	),
	$this->Label->get('ClassTeacher.title') => array(
		'url' => array('controller' => $controller, 'action' => 'ClassTeacher'),
		'selected' => 'ClassTeacher'
	),
	$this->Label->get('ClassSubject.title') => array(
		'url' => array('controller' => $controller, 'action' => 'ClassSubject'),
		'selected' => 'subject'
	),
	$this->Label->get('ClassAssignment.title') => array(
		'url' => array('controller' => $controller, 'action' => 'ClassAssignment'),
		'selected' => 'ClassAssignment'
	),
	$this->Label->get('ClassResult.title') => array(
		'url' => array('controller' => $controller, 'action' => 'ClassResult'),
		'selected' => 'ClassResult'
	),
	$this->Label->get('ClassLesson.title') => array(
		'url' => array('controller' => $controller, 'action' => 'ClassLesson/index'),
		'selected' => 'ClassLesson'
	),
	$this->Label->get('general.timetable') => array(
		'url' => array('controller' => $controller, 'action' => 'Timetable'),
		'acoName' => 'ClassTimetable',
		'selected' => 'Timetable'
	),
	$this->Label->get('Attendance.title') => array(
		'url' => array('controller' => $controller, 'action' => $attendanceModel.'/index'),
		'selected' => $attendanceModel
	),
	$this->Label->get('general.attachments') => array(
		'url' => array('controller' => $controller, 'action' => 'ClassAttachment')
	)
);

echo $this->element('layout/tabs', array('tabs' => $tabs));
?>
