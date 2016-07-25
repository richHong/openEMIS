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
	echo $this->element('../Guardians/profile');
$this->end();

$this->start('tabBody');
?>
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('Contact.main'); ?></th>
					<th><?php echo $this->Label->get('Contact.contact_type_id'); ?></th>
					<th><?php echo $this->Label->get('Contact.cname'); ?></th>
					<th><?php echo $this->Label->get('Contact.value'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->FormUtility->checkOrCrossMarker($obj[$model]['main'], array('hideCrosses' => true)); ?></td>
					<td><?php echo $obj['ContactType']['name']; ?></td>
					<td><?php echo $this->Html->link($obj[$model]['name'], array('action' => $model, 'view', $obj[$model]['id'])); ?></td>
					<td><?php echo $obj[$model]['value']; ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
