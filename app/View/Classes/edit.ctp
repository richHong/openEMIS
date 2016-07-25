<?php
echo $this->Html->script('plugins/tableCheckable/jquery.tableCheckable', array('inline' => false));
echo $this->Html->script('plugins/icheck/jquery.icheck.min', array('inline' => false));
echo $this->Html->css('../js/plugins/icheck/skins/minimal/blue', array('inline' => false));
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => 'view', $this->data[$model]['id']));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => 'edit'));
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
						<?php
						foreach ($grades as $i => $obj) :
							if (empty($obj['ClassGrade']['visible']) && $obj['EducationGrade']['visible'] == 0) {
								continue;
							}
							$checked = $obj['ClassGrade']['visible'] == 1 ? 'checked' : '';
							$inputOptions = array('div' => false, 'label' => false, 'before' => false, 'between' => false, 'type' => 'number');
						?>
						<tr>
							<?php
							echo $this->Form->hidden("ClassGrade.$i.id", array('value' => $obj['ClassGrade']['id']));
							echo $this->Form->hidden("ClassGrade.$i.education_grade_id", array('value' => $obj['EducationGrade']['id']));
							?>
							<td class="checkbox-column">
								<?php echo $this->Form->checkbox("ClassGrade.$i.visible", array('class' => 'icheck-input', 'value' => $obj['ClassGrade']['visible'], 'checked' => $checked)); ?>
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
