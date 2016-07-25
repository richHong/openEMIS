<?php 
echo $this->Html->script('app.table', array('inline' => false));
?>

<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('add', array('action' => $model, 'add'));
$this->end();

$this->prepend('portletBody');
$this->end();

$this->start('tabBody');
?>

<div class="table-responsive">
	<table class="table table-striped table-hover table-bordered table-highlight table-clickable">
		<thead>
			<tr>
				<th><?php echo $this->Label->get('general.title'); ?></th>
				<th><?php echo $this->Label->get('admin.activeDuration'); ?></th>
			</tr>
		</thead>
		<tbody action="<?php echo $this->params['controller'] . '/user_view/'; ?>">
			<?php foreach($data as $obj) : ?>
			<tr row-id="<?php echo $obj[$model]['id']; ?>">
				<td><?php echo $this->Html->link($obj[$model]['title'], array('action' => $model, 'view', $obj[$model]['id'])); ?></td>
				<td><?php echo $obj[$model]['active_from_date']; ?> - <?php echo $obj[$model]['active_to_date']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php //echo $this->element('layout/pagination'); ?>
</div>
<?php
$this->end();
?>