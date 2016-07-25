<?php
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->css('form', array('inline' => false));
echo $this->Html->script('app.table', array('inline' => false));
echo $this->Html->script('class.lesson', array('inline' => false));

$urlPath = $this->params['controller'].'/'.$this->params['action'].'/index';

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


$this->extend('/Layouts/tabs'); // must be student tab
$this->assign('contentHeader', $header);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $this->Label->get($model.'.name'));

$this->start('tabActions');
	echo $this->FormUtility->link('add', array('action' => $model, 'add'));
$this->end();


$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

//pr($data);die();
$this->start('tabBody');
echo $this->element('attendance/datepicker', array('urlPath'=>$urlPath, 'formElements' => array($subjectSelect, $staffSelect)));
?>
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight table-clickable">
			<thead>
				<tr>
					<?php
					echo '<th>' . $this->Label->get('date.day') . '</th>';
					echo '<th>' . $this->Label->get('date.date') . '</th>';
					echo '<th>' . $this->Label->get('date.time') . '</th>';
					echo '<th>' . $this->Label->get('general.subject') . '</th>';
					echo '<th>' . $this->Label->get('general.teacher') . '</th>';
					echo '<th>' . $this->Label->get('ClassLesson.room') . '</th>';
					?>
				</tr>
			</thead>
			<tbody action="<?php echo $this->params['controller'] . '/lesson_edit/'; ?>">
				<?php foreach($data as $key => $obj) :  ?>
				<tr row-id="<?php echo $key."/".$obj['education_grade_subject_id']."/".$obj['staff_id'];//$obj['id']; ?>">
					<td><?php echo date('l', strtotime($obj['date']));  ?></td>
					<td><?php echo $obj['date']; ?></td>
					<td><?php echo $obj['time']; ?></td>
					<td>
					<?php 
					if (array_key_exists('id', $obj)) {
						echo $this->Html->link($educationGradeSubjectOptions[$obj['education_grade_subject_id']], array('action' => $model, 'view', $obj['id']));
					} else {
						echo $educationGradeSubjectOptions[$obj['education_grade_subject_id']];
					}
					 ?>
					</td>
					<td><?php echo $staffOptions[$obj['staff_id']]; ?></td>
					<td><?php echo $roomOptions[$obj['room_id']]; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
?>
