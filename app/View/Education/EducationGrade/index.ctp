<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	echo $this->FormUtility->link('add', array('controller' => $this->params['controller'], 'action' => $model, 'add'));
	if(count($data) > 1) {
		echo $this->FormUtility->link('reorder', array('controller' => $this->params['controller'], 'action' => 'edit', $model, 'education_programme_id' => $selectedSecondary));
	}
$this->end();

$this->start('tabBody');
	echo $this->element('../Education/controls');
?>
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-hover table-striped table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('general.code'); ?></th>
					<th><?php echo $this->Label->get($model . '.name'); ?></th>
					<th><?php echo $this->Label->get('general.status'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr row-id="<?php echo $obj[$model]['id']; ?>">
					<td><?php echo $obj[$model]['code']; ?></td>
					<td><?php echo $this->Html->link($obj[$model]['name'], array('action' => $model, 'view', $obj[$model]['id'])); ?></td>
					<td><?php echo $this->FormUtility->getStatus($obj[$model]['visible']); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php } ?>

<?php $this->end(); ?>
