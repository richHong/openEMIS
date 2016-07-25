<?php 
echo $this->Html->script('app.table', array('inline' => false));
echo $this->element('classes/header');
?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('general.attachment'); ?></span>
		<?php echo $this->FormUtility->link('add', array('action' => $page.'_add')); ?>
	</h4>
	<?php echo $this->element('layout/alert'); ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight table-clickable">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('general.name'); ?></th>
					<th><?php echo $this->Label->get('general.type'); ?></th>
					<th><?php echo $this->Label->get('general.description'); ?></th>
					<th><?php echo $this->Label->get('general.date'); ?></th>
				</tr>
			</thead>
			<tbody action="<?php echo sprintf('%s/%s_view/', $this->params['controller'], $page); ?>">
				<?php foreach($data as $obj) { ?>
					<tr row-id="<?php echo $obj[$modelName]['id']; ?>">
					<td><?php echo $this->Html->link($obj[$modelName]['name'], array("controller" =>$this->params['controller'], "action" => "attachment_download", $obj[$modelName]['id']), array('target' => '_self','escape' => false)); ?></td>
					<td><?php 
					$fileType = explode('.',$obj[$modelName]['file_name']);
					echo $fileType[count($fileType) -1];  
					?></td>
					<td><?php echo $obj[$modelName]['description']; ?></td>
					<td><?php echo $obj[$modelName]['created']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo $this->element('classes/footer'); ?>
