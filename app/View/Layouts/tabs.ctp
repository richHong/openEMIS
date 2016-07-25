<?php
$this->extend('/Layouts/portlet');

$this->start('portletBody');

	if (isset($tabElement)) {
		echo $this->element($tabElement);
	}
	?>
	<div class="tab-content">
		<h4 class="heading">
			<span><?php echo $this->fetch('tabHeader') ?></span>
			<?php echo $this->fetch('tabActions') ?>
		</h4>
		<?php echo $this->fetch('tabBody') ?>
	</div>

<?php $this->end() ?>
