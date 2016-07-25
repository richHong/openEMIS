<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	// echo $this->FormUtility->link('export', array('action' => $model,'export'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabBody');
?>

<?php if (!empty($data['Assessment'])) : ?>
<div class="table-responsive">
	<table class="table table-highlight table-bordered table-striped">
		<thead>
			<tr>
				<th><?php echo $this->Label->get('Assessment.title') ?></th>
				<th><?php echo $this->Label->get('EducationGrade.title') ?></th>
				<th><?php echo $this->Label->get('EducationSubject.title') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data['Assessment'] as $obj) : ?>
			<tr>
				<td><?php echo $obj['AssessmentItemType']['name'] ?></td>
				<td><?php echo $grades[$obj['EducationGradesSubject']['education_grade_id']] ?></td>
				<td><?php echo $this->Html->link($subjects[$obj['EducationGradesSubject']['education_subject_id']], array('action' => $model, 'view', $obj['AssessmentItem']['id'])) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<br />
<?php endif ?>

<?php if (!empty($data['Assignment'])) : ?>
<div class="table-responsive">
	<table class="table table-highlight table-bordered table-striped">
		<thead>
			<tr>
				<th><?php echo $this->Label->get('ClassAssignment.title') ?></th>
				<th><?php echo $this->Label->get('EducationGrade.title') ?></th>
				<th><?php echo $this->Label->get('EducationSubject.title') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data['Assignment'] as $obj) : ?>
			<tr>
				<td><?php echo $obj['AssessmentItemType']['name'] ?></td>
				<td><?php echo $grades[$obj['EducationGradesSubject']['education_grade_id']] ?></td>
				<td><?php echo $this->Html->link($subjects[$obj['EducationGradesSubject']['education_subject_id']], array('action' => $model, 'view', $obj['AssessmentItem']['id'])) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif ?>

<?php
$this->end();
?>
