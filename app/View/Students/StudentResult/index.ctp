<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	// echo $this->FormUtility->link('export', array('action' => $model,'export'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
?>
	<?php if (!empty($yearOptions)) { ?>
	<div class="row">
		<div class="col-md-3">
		<?php
		echo $this->Form->input('school_year_id', array(
			'label' => false,
			'div' => false,
			'options' => $yearOptions,
			'default' => $selectedYear,
			'class' => 'form-control',
			'url' => $this->params['controller'] . '/' . $model . '/index',
			'onchange' => 'Form.change(this)',
			'autocomplete' => 'off'
		));
		?>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('Assessment.title').'/'.$this->Label->get('ClassAssignment.title'); ?></th>
					<th><?php echo $this->Label->get('EducationGrade.title'); ?></th>
					<th><?php echo $this->Label->get('EducationSubject.title'); ?></th>
					<th><?php echo $this->Label->get('general.marks'); ?></th>
					<th><?php echo $this->Label->get('general.grade'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($data)) : ?>
				<?php foreach($data as $key => $obj) { ?>
				<tr>
					<td><?php echo $obj['AssessmentItemType']['name']; ?></td>
					<td><?php echo $obj['EducationGradesSubject']['name']; ?></td>
					<td><?php echo $obj['EducationSubject']['name']; ?></td>
					<td><?php echo $obj['AssessmentItemResult']['marks']; ?></td>
					<td><?php echo $obj['AssessmentResultType']['name']; ?></td>
				</tr>
				<?php } ?>
                <?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php } ?>





<?php
$this->end();
?>
