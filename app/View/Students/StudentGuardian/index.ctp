<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
?>
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('SecurityUser.openemisid'); ?></th>
					<th><?php echo $this->Label->get('SecurityUser.full_name'); ?></th>
					<th><?php echo $this->Label->get('general.relationship'); ?></th>
					<th><?php echo $this->Label->get('general.contacts'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj['SecurityUser']['openemisid'], array('action' => '../Guardians/view', $obj['SecurityUser']['id'])) ?></td>
					<td><?php echo $obj['SecurityUser']['full_name']; ?></td>
					<td><?php echo $obj['RelationshipCategory']['name']; ?></td>
					<td>
						<?php 
						foreach ($obj['Contacts'] as $key => $value) {
							echo '<p>'.$value['ContactType']['name'].": ".$value['GuardianContact']['value'].'<p>';
						}
						 ?>
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
