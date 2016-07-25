<?php
echo $this->Html->css('../js/plugins/fileupload/bootstrap-fileupload', array('inline' => false));
echo $this->Html->script('plugins/fileupload/bootstrap-fileupload', array('inline' => false));
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
						<?php 
						echo $this->Form->input($model.'.'.$field, array(
							'type' => 'file',
							'class' => false, 
							'div' => false, 
							'label' => false, 
							'before' => false, 
							'after' => false, 
							'between' => false,
							'error' => false
						)); 
						?>
					</span>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->Form->error($model.'.'.$field, null, array('class' => 'alert alert-danger form-error')); ?>
</div>

<div class="form-group">
	<label class="col-md-2 control-label">&nbsp;</label>
	<div class="col-md-6">
		<em><?php echo $this->Label->get('general.2mbLimit');?></em>
	</div>
</div>
