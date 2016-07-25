<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('add', array('action' => $model, 'add'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Guardians/profile');
$this->end();

$this->start('tabBody');
?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('SecurityUser.openemisid'); ?></th>
					<th><?php echo $this->Label->get('SecurityUser.full_name'); ?></th>
					<th><?php echo $this->Label->get('general.gender'); ?></th>
					<th><?php echo $this->Label->get('SecurityUser.dateOfBirth'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj['StudentData']['SecurityUser']['openemisid'], array('action' => '../Students/view', $obj['StudentData']['Student']['id'])) ?></td>
					<td><?php echo $obj['StudentData']['SecurityUser']['full_name']; ?></td>
					<td><?php echo $obj['StudentData']['SecurityUser']['gender']; ?></td>
					<td><?php echo $obj['StudentData']['SecurityUser']['date_of_birth']; ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
<?php
$this->end();
?>
