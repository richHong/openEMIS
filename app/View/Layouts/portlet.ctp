<?php
$this->extend('/Layouts/container');

$this->start('contentBody');
?>

	<div class="portlet">
		<div class="portlet-header">
			<h3><?php echo $this->fetch('portletHeader') ?></h3>
		</div>
		<div class="portlet-content">
			<?php
			echo $this->element('layout/alert');
			echo $this->fetch('portletBody');
			?>
		</div>
	</div>

<?php $this->end() ?>
