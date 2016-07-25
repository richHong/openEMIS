<?php 
echo $this->Html->script('plugins/tableCheckable/jquery.tableCheckable', array('inline' => false));
echo $this->Html->script('plugins/icheck/jquery.icheck.min', array('inline' => false));
echo $this->Html->css('../js/plugins/icheck/skins/minimal/blue', array('inline' => false));
echo $this->Html->script('app.form', array('inline' => false));
echo $this->element('classes/header');
?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('student.title'); ?></span>
			<?php if(!empty($grades['EducationProgramme']) ||!empty($grades['EducationGrade'])) { echo '(' . $grades['EducationProgramme']['name']. ' - '. $grades['EducationGrade']['name'] . ')';}?></b>
		<?php echo $this->FormUtility->link('back', array('action' => 'student')); ?>
	</h4>

    <?php echo $this->element('layout/alert');?>
	<div class="form-group option-filter">
		<div class="col-md-3">
		<?php
		echo $this->Form->input('class', array(
			'label' => false,
			'options' => $classOptions,
			'default' => $newClassId,
			'url' => sprintf('%s/%s/%d', $this->params['controller'], $this->params['action'], $gradeId),
			'onchange' => 'Form.change(this)',
			'class' => 'form-control',
		));
		?>
		</div>
	</div>	
	<?php
	echo $this->Form->create('ClassStudent', array(
		'url' => array('controller' => 'Classes', 'action' => 'student_select', $gradeId)
	));
	?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-checkable">
			<thead>
				<tr>
					<th class="checkbox-column"><input type="checkbox" class="icheck-input" /></th>
					<th><?php echo $this->Label->get('SecurityUser.openemisid'); ?></th>
					<th><?php echo $this->Label->get('general.firstName'); ?></th>
					<th><?php echo $this->Label->get('general.lastName'); ?></th>
					<th><?php echo $this->Label->get('general.gender'); ?></th>
					<th><?php echo $this->Label->get('general.category'); ?></th>
					<th><?php echo $this->Label->get('general.status'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				if(!empty($students)){ 
				foreach($students as $value){ ?>
					<tr>
						<?php echo $this->Form->input('ClassStudent.'. $i.'.student_id', array('type'=>'hidden', 'value'=> $value['Student']['id'])); ?>
						<?php echo $this->Form->input('ClassStudent.'. $i.'.student_category_id', array('type'=>'hidden', 'value'=> $value['ClassStudent']['student_category_id'])); ?>
						<?php echo $this->Form->input('ClassStudent.'. $i.'.education_grade_id', array('type'=>'hidden', 'value'=> $gradeId)); ?>
						
						<td class="checkbox-column">
							<input type="checkbox" class="icheck-input" name="data[ClassStudent][<?php echo $i; ?>][checked]" />
						</td>
						<td><?php echo $value['SecurityUser']['openemisid'];  ?></td>
						<td><?php echo $value['SecurityUser']['first_name']; ?></td>
						<td><?php echo $value['SecurityUser']['last_name']; ?></td>
						<td><?php echo $value['SecurityUser']['gender']; ?></td>
						<td><?php echo $value['StudentCategory']['name']; ?></td>
						<td><?php echo $value['StudentStatus']['name']; ?></td>
					</tr>
				<?php $i++;} ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php
	echo $this->Form->button($this->Label->get('general.save'), array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo $this->Form->button($this->Label->get('general.cancel'), array('type' => 'reset', 'class' => 'btn btn-primary btn-back'));
	echo $this->Form->end();
	?>
</div>

<?php echo $this->element('classes/footer'); ?>
