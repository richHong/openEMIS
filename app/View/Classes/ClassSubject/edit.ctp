<?php
echo $this->Html->script('plugins/tableCheckable/jquery.tableCheckable', array('inline' => false));
echo $this->Html->script('plugins/icheck/jquery.icheck.min', array('inline' => false));
echo $this->Html->css('../js/plugins/icheck/skins/minimal/blue', array('inline' => false));
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabBody');

	$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, 'edit'));
	echo $this->Form->create($model, $formOptions);
?>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-checkable table-input table-highlight">
			<thead>
				<tr>
					<th class="checkbox-column"><input type="checkbox" class="icheck-input" /></th>
					<th><?php echo $this->Label->get('general.code'); ?></th>
					<th><?php echo $this->Label->get('EducationGrade.title'); ?></th>
					<th><?php echo $this->Label->get('general.code'); ?></th>
					<th><?php echo $this->Label->get('EducationSubject.title'); ?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				foreach ($data as $i => $obj) :
					$checked = $obj['ClassSubject']['visible'] == 1 ? 'checked' : '';
				?>
				<tr>
					<?php 
					echo $this->Form->hidden("ClassSubject.$i.id", array('value' => $obj['ClassSubject']['id']));
					echo $this->Form->hidden("ClassSubject.$i.class_id", array('value' => $classId));
					echo $this->Form->hidden("ClassSubject.$i.education_grade_subject_id", array('value' => $obj['EducationGradesSubject']['id']));
					?>
					<td class="checkbox-column">
						<?php echo $this->Form->checkbox("ClassSubject.$i.visible", array('class' => 'icheck-input', 'value' => $obj['ClassSubject']['visible'], 'checked' => $checked)); ?>
					</td>
					<td><?php echo $obj['EducationGrade']['code'] ?></td>
					<td><?php echo $obj['EducationGrade']['name'] ?></td>
					<td><?php echo $obj['EducationSubject']['code'] ?></td>
					<td><?php echo $obj['EducationSubject']['name'] ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>

<?php
	echo $this->Form->button($this->Label->get('general.save'), array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo $this->Form->button($this->Label->get('general.cancel'), array('type' => 'reset', 'class' => 'btn btn-primary btn-back'));
	echo $this->Form->end();
$this->end();
?>
