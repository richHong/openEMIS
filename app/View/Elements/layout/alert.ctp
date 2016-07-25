<?php
if($this->Session->check('_alert')) :
	$_alert = $this->Session->read('_alert');
	$alertTypes = $_alert['types'];
	$class = 'alert ' . $alertTypes[$_alert['type']];
	unset($_SESSION['_alert']);
?>

<div class="<?php echo $class; ?>">
	<?php
	if($_alert['dismissOnClick']) {
		echo '<a class="close" aria-hidden="true" href="#" data-dismiss="alert">&times;</a>';
	}
	echo $_alert['message'];
	?>
</div>

<?php endif; ?>