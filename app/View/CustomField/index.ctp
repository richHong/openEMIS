<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('add', array('action' => 'add'));
	if(count($data) > 1) {
		$params = array('action' => 'indexEdit', $selectedOption);
		if(isset($conditionId)) {
			$params = array_merge($params, array($conditionId => $selectedSubOption));
		}
		echo $this->FormUtility->link('reorder', $params);
	}
$this->end();

$this->start('tabBody');
?>
	<div class="row">
		<div class="col-md-3">
			<?php
			echo $this->Form->input('school_year_id', array(
				'label' => false,
				'div' => false,
				'options' => $moduleOptions,
				'default' => $selectedOption,
				'class' => 'form-control',
				'url' => $this->params['controller'] . '/index',
				'onchange' => 'Form.change(this)',
				'autocomplete' => 'off'
			));
			?>
		</div>
	</div>
	
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('CustomField.name') ?></th>
					<th><?php echo $this->Label->get('CustomField.dataType') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj[$model]['name'], array('action' => 'view', $obj[$model]['id'])) ?></td>
					<td><?php echo $obj[$model]['typeName'] ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
