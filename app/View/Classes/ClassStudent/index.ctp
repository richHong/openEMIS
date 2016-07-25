<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabActions');
	if (!empty($gradeOptions)) echo $this->FormUtility->link('add', array('action' => $model, 'add'));
	if (!empty($gradeOptions)) echo $this->FormUtility->link('export', array('action' => $model,'export'));
$this->end();

$this->start('tabBody');
?>
	<?php if (isset($gradeOptions) && !empty($gradeOptions)) { ?>
	<div class="form-group option-filter">
		<div class="col-md-3">
		<?php
			echo $this->Form->input('education_grade_id', array(
				'label' => false,
				'options' => $gradeOptions,
				'default' => $gradeId,
				'class' => 'form-control',
				'url' => sprintf('%s/%s/%s', $this->params['controller'], $model, 'index'),
				'onchange' => 'Form.change(this)'
			));
		?>
		</div>
	</div>	
	<?php } ?>
	<?php
	echo $this->Form->create($model, array('url' => array('controller' => $this->params['controller'], 'action' => $model)));
	echo $this->Form->hidden('sortBy', array('class' => 'sortBy'));
	echo $this->Form->hidden('sortOrder', array('class' => 'sortOrder'));
	$sort = $this->Session->read($model.'.sort');
	?>
	
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<?php
					echo $this->FormUtility->getSort($sort, 'SecurityUser.openemisid');
					echo $this->FormUtility->getSort($sort, $this->Session->read('name_display_format'), $this->Label->get('SecurityUser.full_name'));
					echo '<th>' . $this->Label->get('general.category') . '</th>';
					echo '<th>' . $this->Label->get('StudentFee.fee') . '</th>';
					echo '<th>' . $this->Label->get('StudentFee.paid') . '</th>';
					echo '<th>' . $this->Label->get('StudentFee.outstanding') . '</th>';
					?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj['SecurityUser']['openemisid'], array('controller' => 'Students', 'action' => 'view', $obj['Student']['id'])); ?></td>
					<td><?php echo $obj['SecurityUser']['full_name']; ?></td>
					<td><?php echo $obj['StudentCategory']['name']; ?></td>
					<td><?php echo $this->Label->getCurrencyFormat($obj['StudentFee']['fee']); ?></td>
					<td><?php echo $this->Label->getCurrencyFormat($obj['StudentFee']['amount_paid']);
					; ?></td>
					<td><?php echo $this->Label->getCurrencyFormat($obj['StudentFee']['fee']-$obj['StudentFee']['amount_paid']);
					; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>	
	<?php } ?>
	
	<?php echo $this->Form->end() ?>

<?php $this->end(); ?>
