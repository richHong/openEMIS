<?php
echo $this->Html->css('../js/plugins/fileupload/bootstrap-fileupload', array('inline' => false));
echo $this->Html->script('plugins/fileupload/bootstrap-fileupload', array('inline' => false));
echo $this->Html->script('app.form', array('inline' => false));
echo $this->element('classes/header');
?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('general.attachment'); ?></span>
		<?php echo $this->FormUtility->link('back', array('action' => $page)); ?>
	</h4>
	<?php
	echo $this->element('layout/alert');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => $page.'_add'));
	$formOptions['type'] = 'file';
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->hidden('class_id', array('value' => $classId));
	echo $this->Form->hidden('file_name', array('value' => ''));
	echo $this->Form->hidden('file_content', array('value' => ''));
	echo $this->Form->input('name');
	echo $this->Form->input('description',array('type'=>'textarea'));
	echo $this->Form->hidden('maxFileSize', array('name'=> 'MAX_FILE_SIZE','value'=>(2*1024*1024)));
	echo $this->element('layout/attachment');
	echo $this->FormUtility->getFormButtons($this->Form);
	echo $this->Form->end();
	?>
</div>

<?php echo $this->element('classes/footer'); ?>
