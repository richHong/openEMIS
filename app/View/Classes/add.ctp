<?php
echo $this->Html->script('plugins/tableCheckable/jquery.tableCheckable', array('inline' => false));
echo $this->Html->script('plugins/icheck/jquery.icheck.min', array('inline' => false));
echo $this->Html->css('../js/plugins/icheck/skins/minimal/blue', array('inline' => false));
$this->extend('/Layouts/portlet');

$this->assign('contentHeader', $header);
$this->start('portletHeader');
	echo $this->Icon->get('add');
	echo $this->Label->get('SClass.add');
$this->end();

$this->start('portletBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => 'add'));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
?>

<div class="form-group">
	<label class="col-md-2 control-label"><?php echo $this->Label->get('EducationGrade.title'); ?></label>
	<div class="col-md-10">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-checkable table-input table-highlight">
				<thead>
					<tr>
						<th class="checkbox-column"><input type="checkbox" class="icheck-input" /></th>
						<th><?php echo $this->Label->get('EducationProgramme.title'); ?></th>
						<th><?php echo $this->Label->get('EducationGrade.title'); ?></th>
					</tr>
				</thead>
				
				<tbody>
					<?php foreach ($grades as $i => $obj) : ?>
					<tr>
						<?php
						echo $this->Form->hidden("ClassGrade.$i.education_grade_id", array('value' => $obj['EducationGrade']['id']));
						?>
						<td class="checkbox-column">
							<?php echo $this->Form->checkbox("ClassGrade.$i.visible", array('class' => 'icheck-input')); ?>
						</td>
						<td><?php echo $obj['EducationProgramme']['name'] ?></td>
						<td><?php echo $obj['EducationGrade']['name'] ?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
	echo '<div class="col-md-offset-2 form-buttons">';
	echo $this->Form->button($this->Label->get('general.add'), array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo '</div>';
	echo $this->Form->end();
$this->end();
?>
