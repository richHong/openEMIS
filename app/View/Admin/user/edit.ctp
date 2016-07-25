<?php 
echo $this->Html->script('app.form', false);
echo $this->element('admin/header');
?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $contentHeader; ?></span>
		<?php echo $this->FormUtility->link('back', array('action' => !empty($id) ? $page.'_view' : $page, $id)); ?>
	</h4>
	<?php
	echo $this->element('layout/alert');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => $page.'_edit', $id));
	echo $this->Form->create($model, $formOptions);
	if(!empty($id)) {
		echo $this->Form->hidden('id');
	}
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons($this->Form);
	echo $this->Form->end();
	?>
</div>

<?php echo $this->element('admin/footer'); ?>
