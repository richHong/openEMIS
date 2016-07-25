<?php
echo $this->Html->css('../js/plugins/autocomplete/jquery-ui', array('inline' => false));
echo $this->Html->css('../js/plugins/autocomplete/autocomplete', array('inline' => false));
echo $this->Html->script('plugins/autocomplete/jquery-ui', array('inline' => false));
echo $this->Html->script('app.autocomplete', array('inline' => false));

$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model));
$this->end();

$this->start('tabBody');

	$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, 'add'));
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->hidden('student_id', array('class' => 'student-id'));
	echo $this->Form->input('search', array(
		'class' => 'autocomplete form-control',
		'placeholder' => $this->Label->get('general.idPlaceholder2'),
		'url' => 'autocomplete'
	));
	echo $this->Form->input('Student.first_name', array('disabled'=>'disabled', 'class'=>'first-name form-control'));
	echo $this->Form->input('Student.last_name', array('disabled'=>'disabled', 'class'=>'last-name form-control'));
	echo $this->Form->input('education_grade_id', array('options' => $gradeOptions));
	echo $this->Form->input('student_category_id', array('options' => $studentCategoryOptions));
	echo $this->FormUtility->getFormButtons();
	echo $this->Form->end();

$this->end();
?>
