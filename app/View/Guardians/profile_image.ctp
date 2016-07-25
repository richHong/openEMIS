<?php 
echo $this->Html->css('../js/plugins/fileupload/bootstrap-fileupload', array('inline' => false));
echo $this->Html->script('plugins/fileupload/bootstrap-fileupload', array('inline' => false));
echo $this->element('guardians/header'); 
?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('general.profileImage'); ?></span>
	</h4>
	<?php
	echo $this->element('layout/alert');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => $this->action));
	$formOptions['type'] = 'file';
	echo $this->Form->create('SecurityUser', $formOptions);
	echo $this->Form->hidden('maxFileSize', array('name'=> 'MAX_FILE_SIZE','value'=>(4*1024*1024)));
	echo $this->Form->hidden('id', array('value'=> $data['SecurityUser']['id']));
	echo $this->element('layout/attachment');
	echo '<div class="col-md-offset-2 form-buttons">';
	echo $this->Form->button($this->Label->get('general.save'), array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo '</div>';
	echo $this->Form->end();
	?>
</div>

<?php echo $this->element('guardians/footer'); ?>
