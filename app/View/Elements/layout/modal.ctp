<div id="<?php echo $modalId; ?>" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title"><?php echo $modalTitle; ?></h3>
			</div>
			<div class="modal-body"><?php echo $modalBody; ?></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->Label->get('general.close'); ?></button>
				<?php echo $modalBtn; ?>
			</div>
		</div>
	</div>
</div>
