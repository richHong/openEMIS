<?php
$tabs = array(
	$this->Label->get('general.general') => array(
		'url' => array('controller' => 'Admin', 'action' => 'view'),
		'acoName' => 'AdminProfile',
		'selected' => array('view', 'edit')
	),
	$this->Label->get('Education.structure') => array(
		'url' => array('controller' => 'Education', 'action' => 'index'),
		'acoName' => 'Education',
		'selected' => array('index', 'edit', 'EducationProgramme', 'EducationGrade', 'EducationGradesSubject', 'EducationSubject')
	),
	$this->Label->get('Finance.title') => array(
		'url' => array('controller' => 'Finance', 'action' => 'index'),
		'selected' => array('index', 'EducationFee')
	),
	$this->Label->get('Assessment.title') => array(
		'url' => array('controller' => 'Assessment', 'action' => 'index'),
		'acoName' => 'Assessment',
		'selected' => array('index', 'add', 'view', 'edit')
	),
	$this->Label->get('CustomField.title') => array(
		'url' => array('controller' => 'CustomField', 'action' => 'index'),
		'acoName' => 'CustomField',
		'selected' => array('index', 'indexEdit', 'add', 'view', 'edit')
	),
	$this->Label->get('FieldOption.title') => array(
		'url' => array('controller' => 'FieldOptions', 'action' => 'index'),
		'acoName' => 'FieldOptions',
		'selected' => array('index', 'indexEdit', 'add', 'view', 'edit')
	),
	$this->Label->get('Translation.title') => array(
		'url' => array('controller' => 'Translations', 'action' => 'index'),
		'acoName' => 'Translations',
		'selected' => array('index', 'add', 'view', 'edit')
	),
	$this->Label->get('ConfigItem.title') => array(
		'url' => array('controller' => 'Admin', 'action' => 'ConfigItem')
	)

	// ,
	// $this->Label->get('general.reportCard') => array(
	// 	'url' => array('controller' => 'Admin', 'action' => 'ReportCardTemplate'),
	// 	'selected' => 'ReportCardTemplate'
	// )

	// 	$this->Label->get('general.reportCard') => array('controller' => $controller, 'action' => 'report_card_template', 'selected' => '^report_card_template')


	// ,
	// $this->Label->get('admin.userManagement') => array(
	// 	'url' => array('controller' => 'Students', 'action' => 'result')
	// )
	// ,
	// $this->Label->get('general.reportCard') => array(
	// 	'url' => array('controller' => 'Students', 'action' => 'attendance_view'),
	// 	'selected' => '^attendance'
	// )
);

echo $this->element('layout/tabs', array('tabs' => $tabs));



?>
