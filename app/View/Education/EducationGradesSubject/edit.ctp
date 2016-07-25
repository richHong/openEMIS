<?php
echo $this->Html->script('plugins/tableCheckable/jquery.tableCheckable', array('inline' => false));
echo $this->Html->script('plugins/icheck/jquery.icheck.min', array('inline' => false));
echo $this->Html->css('../js/plugins/icheck/skins/minimal/blue', array('inline' => false));
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	$params = array('action' => $model, 'index', $selectedSecondary);
	echo $this->FormUtility->link('back', $params);
$this->end();

$this->start('tabBody');
	echo $this->element('../Education/controls');
	$params = array('controller' => $this->params['controller'], 'action' => $model, 'edit', $selectedSecondary);
	$formOptions = $this->FormUtility->getFormOptions($params);
	echo $this->Form->create($model, $formOptions);
	?>
	
	<div class="table-responsive">
		<table class="table table-hover table-striped table-bordered table-highlight table-checkable">
			<thead>
				<tr>
					<th class="checkbox-column"><input type="checkbox" class="icheck-input" /></th>
					<th><?php echo $this->Label->get('general.code'); ?></th>
					<th><?php echo $this->Label->get('EducationSubject.title'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach($data as $i => $obj) :
					$checked = !empty($obj[$model]['education_grade_id']) ? 'checked="checked"' : '';
					$subjectId = $obj['EducationSubject']['id'];
					if($obj['EducationSubject']['visible']==1 || !empty($checked)) :
				?>
				<tr row-id="<?php echo $obj[$model]['id']; ?>">
					<td class="checkbox-column">
						<input type="checkbox" class="icheck-input" name="data[EducationGradesSubject][<?php echo $i; ?>][education_subject_id]" value="<?php echo $subjectId; ?>" <?php echo $checked; ?> />
					</td>
					<td><?php echo $obj['EducationSubject']['code']; ?></td>
					<td><?php echo $obj['EducationSubject']['name']; ?></td>
				</tr>
				<?php 
					endif;
				endforeach;
				?>
			</tbody>
		</table>
	</div>
	
	<?php
	echo $this->Form->button($this->Label->get('general.save'), array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo $this->Form->button($this->Label->get('general.cancel'), array('type' => 'reset', 'class' => 'btn btn-primary btn-back'));
	echo $this->Form->end();
$this->end();
?>

