<?php
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->css('../js/plugins/timepicker/bootstrap-timepicker.css', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->script('plugins/timepicker/bootstrap-timepicker', array('inline' => false));
echo $this->Html->script('app.form', array('inline' => false));
echo $this->Html->script('classes', array('inline' => false));
echo $this->element('classes/header');
?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('class.lessons'); ?></span>
		<?php echo $this->FormUtility->link('back', array('action' => 'lesson')); ?>
	</h4>
	<?php
	echo $this->element('layout/alert');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => $this->action));
	echo $this->Form->create('ClassLesson', $formOptions);
	echo $this->Form->hidden('class_id', array('value'=>$classId));
    echo $this->Form->hidden('timetable_entry_id', array('value' => 0));
	
	echo $this->FormUtility->datepicker($this->Form, 'start_date', array('id' => 'startDate'));
	echo $this->FormUtility->timepicker($this->Form, 'start_time', array('id' => 'startTime'));
	echo $this->FormUtility->timepicker($this->Form, 'end_time', array('id' => 'endTime'));
	echo $this->Form->input('education_grade_subject_id', array('options'=>$educationGradeSubjectOptions));
	echo $this->Form->input('staff_id', array('options'=>$staffOptions));
	echo $this->Form->input('room_id', array('options'=>$roomOptions));
	echo $this->Form->input('lesson_status_id', array('options'=>$statusOptions));
	echo $this->FormUtility->getFormButtons($this->Form);
	echo $this->Form->end();
	?>
</div>

<?php echo $this->element('classes/footer'); ?>

<script type="text/javascript">
$(function () {
	$('#startDate').datepicker();
	$('#startTime, #endTime').timepicker({minuteStep: 1});
});
</script>
