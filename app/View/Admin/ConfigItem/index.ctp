<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabBody');
?>
	<div class="form-group option-filter">
		<div class="col-md-3">
		<?php
			echo $this->Form->input('options', array(
				'label' => false,
				'options' => $options,
				'default' => $selectedOption,
				'class' => 'form-control',
				'url' => sprintf('%s/%s/%s', $this->params['controller'], $model, 'index'),
				'onchange' => 'Form.change(this)'
			));
		?>
		</div>
	</div>

	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('general.name') ?></th>
					<th><?php echo $this->Label->get('general.description') ?></th>
					<th><?php echo $this->Label->get('general.value') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj[$model]['label'], array('action' => $model, 'view', $obj[$model]['id'], 'type' => $selectedOption)) ?></td>
					<td><?php echo $obj[$model]['description'] ?></td>
					<td>
						<?php
						if ($obj[$model]['value_type'] == 'toggleVal') {
								$toggleBool  = strtok($obj[$model]['value'], ',');
								$toggleVal = strtok(',');
								$str = '';
								$str .= ($toggleBool)? $this->Label->get('general.enabled'): $this->Label->get('general.disabled');
								$str .= ' ('.$toggleVal.')';
								echo $str;
						} else if ($obj[$model]['value_type'] == 'dropdown') {
							if (array_key_exists($obj[$model]['value'], $obj['ConfigItemOption'])) {
								echo $obj['ConfigItemOption'][$obj[$model]['value']];
							} else {
								echo $this->Label->get('ConfigItemOption.notFound');
							}
						} else {
							echo $obj[$model]['value'];
						}
						?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php } ?>

<?php $this->end(); ?>
