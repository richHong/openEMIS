<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabActions');
	echo $this->FormUtility->link('add', array('action' => $model, 'add'));
	echo $this->FormUtility->link('export', array('action' => $model,'export'));
$this->end();

$this->start('tabBody');
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
					?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj['SecurityUser']['openemisid'], array('controller' => 'Staff', 'action' => 'view', $obj['Staff']['id'])); ?></td>
					<td><?php echo $obj['SecurityUser']['full_name']; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
	
	<?php echo $this->Form->end() ?>

<?php $this->end(); ?>
