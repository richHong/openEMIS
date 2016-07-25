<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => 'index'));
	echo $this->FormUtility->link('edit', array('action' => 'edit', $data[$model]['id']));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabBody');

echo $this->element('layout/view');
?>

<div class="row">
	<div class="col-md-3"><?php echo $this->Label->get('EducationGrade.title'); ?></div>
	<div class="col-md-9">
		<?php if (!empty($grades)) : ?>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-checkable table-input table-highlight">
				<thead>
					<tr>
						<th><?php echo $this->Label->get('EducationProgramme.title'); ?></th>
						<th><?php echo $this->Label->get('EducationGrade.title'); ?></th>
					</tr>
				</thead>
				
				<tbody>
					<?php foreach ($grades as $i => $obj) : ?>
					<tr>
						<td><?php echo $programmes[$obj['EducationGrade']['education_programme_id']] ?></td>
						<td><?php echo $obj['EducationGrade']['name'] ?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php 
		else :
			echo $this->Label->get('ClassGrade.noGrades');
		endif;
		?>
	</div>
</div>

<?php $this->end(); ?>
