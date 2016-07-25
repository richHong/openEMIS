<?php
echo $this->Html->css('table', 'stylesheet', array('inline' => false));
?>

<div class="content_wrapper">
	<div class="controls">
		<?php echo $this->Html->link(__('Add'), array('controller' => 'Users', 'action' => 'add')); ?>
	</div>
	<div class="table">
		<div class="table_head">
			<div class="table_cell"><?php echo __('id'); ?></div>
			<div class="table_cell"><?php echo __('username'); ?></div>
			<div class="table_cell"><?php echo __('first name'); ?></div>
			<div class="table_cell"><?php echo __('last name'); ?></div>
			<div class="table_cell"><?php echo __('super admin'); ?></div>
			<div class="table_cell"><?php echo __('status'); ?></div>
			<div class="table_cell"><?php echo __('last login'); ?></div>
		</div>
		
		<?php foreach($data as $obj) { ?>
		<div class="table_body">
			<div class="table_cell"><?php echo $obj['SecurityUser']['id']; ?></div>
			<div class="table_cell"><?php echo $obj['SecurityUser']['username']; ?></div>
			<div class="table_cell"><?php echo $obj['SecurityUser']['first_name']; ?></div>
			<div class="table_cell"><?php echo $obj['SecurityUser']['last_name']; ?></div>
			<div class="table_cell"><?php echo $obj['SecurityUser']['super_admin']; ?></div>
			<div class="table_cell"><?php echo $obj['SecurityUser']['status']; ?></div>
			<div class="table_cell"><?php echo $obj['SecurityUser']['last_login']; ?></div>
		</div>
		<?php } ?>
	</div>
</div>
