<div id="content">
	<?php echo $this->element('layout/content_header'); ?>
	<div id="content-container">
		<?php echo $this->element('layout/breadcrumbs'); ?>
		<div class="portlet">
			<div class="portlet-header">
				<h3><?php echo $header; ?></h3>
			</div>
			<div class="portlet-content">
				<?php
				echo $this->element('guardians/profile');
				echo $this->element('guardians/navigations');
				?>
				