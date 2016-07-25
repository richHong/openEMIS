<?php 
echo $this->Html->css('../js/plugins/fileupload/bootstrap-fileupload', array('inline' => false));
echo $this->Html->script('plugins/fileupload/bootstrap-fileupload', array('inline' => false));

$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => $this->action));
	$formOptions['type'] = 'file';
	echo $this->Form->create('SecurityUser', $formOptions);
	echo $this->Form->hidden('maxFileSize', array('name'=> 'MAX_FILE_SIZE','value'=>(4*1024*1024)));
	echo $this->Form->hidden('id', array('value'=> $data['SecurityUser']['id']));
	?>
<div class="form-group">
	<label class="col-md-2 control-label"><?php echo $this->Label->get('general.file'); ?></label>
	<div class="col-md-5">
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div class="input-group">
				<div class="form-control">
					<i class="fa fa-file fileupload-exists"></i> <span class="fileupload-preview"></span>
				</div>
				<div class="input-group-btn">
					<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload"><?php echo $this->Label->get('general.remove'); ?></a>
					<span class="btn btn-default btn-file">
						<span class="fileupload-new"><?php echo $this->Label->get('general.selectFile'); ?></span>
						<span class="fileupload-exists"><?php echo $this->Label->get('general.change'); ?></span>
						<?php echo $this->Form->input('file', array('type' => 'file', 'class' => false, 'div' => false, 'label' => false, 'before' => false, 'after' => false, 'between' => false)); ?>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-md-2 control-label">&nbsp;</label>
	<div class="col-md-6">
		<em><?php echo $this->Label->get('general.2mbLimit');?></em>
	</div>
</div>
<?php
	echo '<div class="col-md-offset-2 form-buttons">';
	echo $this->Form->button($this->Label->get('general.save'), array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo '</div>';
	echo $this->Form->end();
$this->end();
?>
