<div class="form-group">
	<label class="col-md-2 control-label" for="ClassAttachmentFile"><?php echo $this->Label->get('general.file'); ?></label>
	<div class="col-md-6 attachment">
	<?php 
	$attachment_data = $this->data[$model];
	echo $this->Html->link($attachment_data['file_name'], array('action' => $model, 'attachment_download', $attachment_data['id']));
	?>
	</div>
</div>