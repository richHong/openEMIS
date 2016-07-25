<?php
echo $this->Html->script('app.form', array('inline' => false));
echo $this->element('classes/header');
?>
				
<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('general.attachment'); ?></span>
		<?php 
		echo $this->FormUtility->link('back', array('action' => 'attachment_view', $this->data[$modelName]['id']));
		echo $this->FormUtility->link('deleteModal');
		?>
	</h4>
	<?php
	echo $this->element('layout/alert');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => 'attachment_edit'));
	$formOptions['type'] = 'file';
	echo $this->Form->create($modelName, $formOptions);
	echo $this->Form->hidden('class_id', array('value' => $classId));
	echo $this->Form->hidden('id');
	echo $this->Form->input('name');
	echo $this->Form->input('description', array('type' => 'textarea'));
	?>
	<div class="form-group">
		<label class="col-md-2 control-label" for="ClassAttachmentFile"><?php echo $this->Label->get('general.file'); ?></label>
		<div class="col-md-6 attachment">
		<?php echo $this->Html->link($this->data[$modelName]['file_name'], array("controller" => $this->params['controller'], "action" => "attachment_download", $this->data[$modelName]['id']), array('target' => '_self','escape' => false)); ?>
		</div>
	</div>
	<?php
	echo $this->FormUtility->getFormButtons($this->Form);
	echo $this->Form->end();
	?>
</div>

<?php echo $this->element('classes/footer'); ?>

<?php
$url = array('controller' => $this->params['controller'], 'action' => 'attachment_delete', $this->data[$modelName]['id']);
echo $this->element('layout/deleteModal', array('url' => $url, 'model' => $modelName));
?>
