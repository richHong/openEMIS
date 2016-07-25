<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model, 'index'));
	echo $this->FormUtility->link('edit', array('action' => $model, 'edit', $data[$model]['id']));
$this->end();

$this->start('tabBody');

echo $this->element('layout/view');
?>

<div class="row">
	<div class="col-md-3"><?php echo $this->Label->get('EducationSubject.title'); ?></div>
	<div class="col-md-9">
		<?php if (!empty($items)) : ?>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-checkable table-input table-highlight">
				<thead>
					<tr>
						<th><?php echo $this->Label->get('general.code'); ?></th>
						<th><?php echo $this->Label->get('EducationSubject.title'); ?></th>
						<th class="cell-marks"><?php echo $this->Label->get('AssessmentResultType.min'); ?></th>
						<th class="cell-marks"><?php echo $this->Label->get('AssessmentResultType.max'); ?></th>
						<th class="cell-marks"><?php echo $this->Label->get('AssessmentItem.weighting'); ?></th>
					</tr>
				</thead>
				
				<tbody>
					<?php foreach ($items as $i => $obj) : ?>
					<tr>
						<td><?php echo $obj['EducationSubject']['code'] ?></td>
						<td><?php echo $obj['EducationSubject']['name'] ?></td>
						<td><?php echo $obj['AssessmentItem']['min'] ?></td>
						<td><?php echo $obj['AssessmentItem']['max'] ?></td>
						<td><?php echo $obj['AssessmentItem']['weighting'] ?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php 
		else :
			echo $this->Label->get('AssessmentItem.noSubjects');
		endif;
		?>
	</div>
</div>

<?php $this->end(); ?>
