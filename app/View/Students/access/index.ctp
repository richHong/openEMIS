<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('security.user', array('inline' => false)); ?>

<?php
$obj = $data[$model];
$id = $obj['id'];
?>

<?php echo $this->element('students/header'); ?>
<?php echo $this->element('layout/headerbar'); ?>

<div class="content">
	<?php echo $this->element('students/profile'); ?>
	<?php echo $this->element('students/navigations', array('action' => $page)); ?>
	
	<div class="details container-fluid">
		<div class="action-bar">
			<b><?php echo __($title . ' Details'); ?></b>
			<?php echo $this->Html->link('<i class="icon-edit"></i> ' . $this->Label->get('general.edit');, array('action' => $page.'_edit', $id), array('escape' => false)); ?>
		</div>
		<?php echo $this->element('alert'); ?>
		<div class="row-fluid">
			<div class="span4"><?php echo $this->Label->get('general.status');; ?></div>
			<div class="span8"><?php  ?></div>
		</div>
		<div class="row-fluid">
			<div class="span4"><?php echo $this->Label->get('general.username');; ?></div>
			<div class="span8"><?php  ?></div>
		</div>
		<div class="row-fluid">
			<div class="span4"><?php echo $this->Label->get('general.password'); ?></div>
			<div class="span8"><?php echo '************'; ?></div>
		</div>
	</div>
</div>