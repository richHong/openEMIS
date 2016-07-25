<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $this->Label->get('general.attachments'));

$this->start('tabActions');
	echo $this->FormUtility->link('add', array('action' => $model, 'add'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabBody');
?>
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('general.name'); ?></th>
					<th><?php echo $this->Label->get('general.type'); ?></th>
					<th><?php echo $this->Label->get('general.description'); ?></th>
					<th><?php echo $this->Label->get('date.date'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj[$model]['name'], array('action' => $model, 'view', $obj[$model]['id'])); ?></td>
					<td><?php echo $obj[$model]['file_name']; ?></td>
					<td><?php echo $obj[$model]['description']; ?></td>
					<td><?php echo $obj[$model]['created']; ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
