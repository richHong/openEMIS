<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('add', array('action' => $model, 'add'));
	echo $this->FormUtility->link('export', array('action' => $model,'export'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Staff/profile');
$this->end();

$this->start('tabBody');
?>
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('date.date'); ?></th>
					<th><?php echo $this->Label->get('date.time'); ?></th>
					<th><?php echo $this->Label->get('general.title'); ?></th>
					<th><?php echo $this->Label->get('general.category'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $obj[$model]['date_of_behaviour']; ?></td>
					<td><?php echo $obj[$model]['time_of_behaviour']; ?></td>
					<td><?php echo $this->Html->link($obj[$model]['title'], array('action' => $model, 'view', $obj[$model]['id'])); ?></td>
					<td><?php echo $obj['BehaviourCategory']['name']; ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
