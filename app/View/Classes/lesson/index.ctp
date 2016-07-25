<?php
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->css('form', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->script('app.table', array('inline' => false));
echo $this->Html->script('class.lesson', array('inline' => false));
echo $this->element('classes/header', array('inline' => false));
?>
<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('class.lessons'); ?></span>
		<?php echo $this->FormUtility->link('add', array('action' => 'lesson_add')); ?>
	</h4>

	<?php
	echo $this->element('layout/alert');
	$urlPath = $this->params['controller'].'/'.$this->params['action'];
	$subjectSelect = $this->Form->input('subject_id', array(
		'label' => false,
		'options' => $educationGradeSubjectOptions,
		'empty' => $this->Label->get('class.allSubjects'),
		'div' => 'col-md-2',
		'class' => "form-control",
		'onchange' => 'ClassLesson.filterChange(this)'
	));

	$staffSelect = $this->Form->input('staff_id', array(
		'label' => false,
		'options' => $staffOptions,
		'empty' => $this->Label->get('class.allTeachers'),
		'div' => 'col-md-2',
		'class' => "form-control",
		'onchange' => 'ClassLesson.filterChange(this)'
	));
	echo $this->element('attendance/datepicker', array('urlPath'=>$urlPath, 'formElements' => array($subjectSelect, $staffSelect)));
    ?>

	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight table-clickable">
			<thead>
				<tr>
					<?php
					echo '<th>' . $this->Label->get('general.day') . '</th>';
					echo '<th>' . $this->Label->get('general.date') . '</th>';
					echo '<th>' . $this->Label->get('general.time') . '</th>';
					echo '<th>' . $this->Label->get('general.subject') . '</th>';
					echo '<th>' . $this->Label->get('general.teacher') . '</th>';
					echo '<th>' . $this->Label->get('class.room') . '</th>';
					?>
				</tr>
			</thead>
			<tbody action="<?php echo $this->params['controller'] . '/lesson_edit/'; ?>">
				<?php foreach($data as $key => $obj) :  ?>
				<tr row-id="<?php echo $key."/".$obj['education_grade_subject_id']."/".$obj['staff_id'];//$obj['id']; ?>">
					<td><?php echo date('l', strtotime($obj['date']));  ?></td>
					<td><?php echo $obj['date']; ?></td>
					<td><?php echo $obj['time']; ?></td>
					<td><?php echo $educationGradeSubjectOptions[$obj['education_grade_subject_id']]; ?></td>
					<td><?php echo $staffOptions[$obj['staff_id']]; ?></td>
					<td><?php echo $roomOptions[$obj['room_id']]; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo $this->element('classes/footer'); ?>
