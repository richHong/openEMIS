<?php
$picture = $this->Session->read('StudentGuardian.picture');
if (!isset($mainPhone)) {
	$mainPhone = $this->Session->read('StudentGuardian.data.mainPhone');
}
if (!isset($mainEmail)) {
	$mainEmail = $this->Session->read('StudentGuardian.data.mainEmail');
}
if (!isset($roleStatus)) {
	$roleStatus = $this->Session->read('StudentGuardian.data.status');
}
?>

<div class="row">
	<div class="profile-user">
		<div class="thumbnail pic">
			<?php
			if(empty($picture)) {
				echo $this->Html->image('profile/staff.jpg');
			} else {
				$image = sprintf('<img src="data:image/%s;base64,%s" />', $picture['type'], $picture['content']);
				echo $image;
			}
			?>
		</div>
		<div class="info">
			<div><?php echo (isset($mainPhone)) ? '<i class="fa fa-phone"></i> '.$mainPhone: ''; ?></div>
			<div><?php echo (isset($mainEmail)) ? '<i class="fa fa-envelope"></i> '.$mainEmail: ''; ?></div>
			<div><?php echo (isset($roleStatus)) ? '<i class="fa fa-tag"></i> '.$roleStatus: ''; ?></div>
		</div>
	</div>
</div>