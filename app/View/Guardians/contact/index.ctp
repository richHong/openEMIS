<?php 
echo $this->Html->script('app.table', false);
echo $this->element('guardians/header');
?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('contact.title'); ?></span>
		<?php echo $this->FormUtility->link('add', array('action' => $page.'_add')); ?>
	</h4>
	<?php echo $this->element('layout/alert'); ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight table-clickable">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('contact.type'); ?></th>
					<th><?php echo $this->Label->get('contact.desc'); ?></th>
					<th><?php echo $this->Label->get('contact.value'); ?></th>
				</tr>
			</thead>
			<tbody action="<?php echo sprintf('%s/%s_view/', $this->params['controller'], $page); ?>">
				<?php foreach($data as $obj) { ?>
				<tr row-id="<?php echo $obj['Contact']['id']; ?>">
					<td><?php echo $obj['ContactType']['type']; ?></td>
					<td><?php echo $obj['ContactType']['name']; ?></td>
					<td><?php echo $obj['Contact']['value']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo $this->element('guardians/footer'); ?>
