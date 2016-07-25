<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model, 'view', $id));
$this->end();

$this->start('tabBody');
	$inputOptions = array('div' => false, 'label' => false, 'before' => false, 'between' => false);
	$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, 'edit', $id));
	echo $this->Form->create($model, $formOptions);
?>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-checkable table-input table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('SecurityUser.openemisid') ?></th>
					<th><?php echo $this->Label->get('general.name') ?></th>
					<th><?php echo $this->Label->get('ClassResult.marks') ?></th>
					<th><?php echo $this->Label->get('ClassResult.grading') ?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach ($data as $i => $obj) : ?>
				<tr>
					<?php
					if (array_key_exists('id', $obj[$model])) {
						echo $this->Form->hidden($i.'.id', array('value' => $obj[$model]['id']));
					}
					echo $this->Form->hidden($i.'.student_id', array('value' => $obj['Student']['id']));
					echo $this->Form->hidden($i.'.school_year_id', array('value' => $obj['SClass']['school_year_id']));
					echo $this->Form->hidden($i.'.assessment_item_id', array('value' => $id));
					?>
					<td><?php echo $obj['SecurityUser']['openemisid'] ?></td>
					<td><?php echo trim($obj['SecurityUser']['first_name'] . ' ' . $obj['SecurityUser']['last_name']) ?></td>
					<td><?php echo $this->Form->input($i.'.marks', array_merge($inputOptions, array('value' => $obj[$model]['marks']))) ?></td>
					<td>
						<?php 
						echo $this->Form->input($i.'.assessment_result_type_id', 
							array_merge($inputOptions, array('value' => $obj[$model]['assessment_result_type_id'], 'options' => $resultTypeOptions))
						)

						
						?>
					</td>
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
