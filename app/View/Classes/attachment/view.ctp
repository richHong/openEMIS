<?php echo $this->element('classes/header'); ?>
				
<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('general.attachment'); ?></span>
		<?php echo $this->FormUtility->link('back', array('action' => 'attachment')); ?>
		<?php echo $this->FormUtility->link('edit', array('action' => 'attachment_edit', $data['ClassAttachment']['id'])); ?>
	</h4>
	<?php echo $this->element('layout/alert'); ?>
	<?php echo $this->element('layout/view'); ?>

</div>

<?php echo $this->element('classes/footer'); ?>
