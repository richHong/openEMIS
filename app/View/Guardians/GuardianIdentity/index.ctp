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
	<?php if (!empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('StudentIdentity.identity_type_id'); ?></th>
					<th><?php echo $this->Label->get('StudentIdentity.number'); ?></th>
					<th><?php echo $this->Label->get('StudentIdentity.issue_date'); ?></th>
					<th><?php echo $this->Label->get('StudentIdentity.expiry_date'); ?></th>
					<th><?php echo $this->Label->get('StudentIdentity.country_id'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $obj['IdentityType']['name']; ?></td>
					<td><?php echo $this->Html->link($obj[$model]['number'], array('action' => $model, 'view', $obj[$model]['id'])); ?></td>
					<td><?php echo $obj[$model]['issue_date']; ?></td>
					<td><?php echo $obj[$model]['expiry_date']; ?></td>
					<td><?php echo $obj['Country']['name']; ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
