<div id="header">
	<div class="col-md-12">
		<div class="logo">
			<a href="<?php echo $this->Html->url(array('controller' => 'Dashboard', 'action' => 'index')) ?>"><?php echo $this->Html->image('logo.png', array('title' => $_productName, 'alt' => $_productName)) ?></a>    
		</div>
		<h1><?php echo $_productName ?></h1>
		<?php echo $this->element('layout/header_side_nav'); ?>
	</div>
</div>
