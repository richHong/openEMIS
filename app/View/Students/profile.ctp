<?php
$userData = $this->Session->read('Student.data.SecurityUser');
if (!isset($mainPhone)) {
	$mainPhone = $this->Session->read('Student.data.mainPhone');
}
if (!isset($mainEmail)) {
	$mainEmail = $this->Session->read('Student.data.mainEmail');
}
if (!isset($roleStatus)) {
	$roleStatus = $this->Session->read('Student.data.status');
}
?>
<div class="row">
	<div class="profile-user">
		<div class="thumbnail pic">
			<?php
			if(empty($userData['photo_name'])) {
				echo $this->Html->image('profile/student.jpg');
			} else {
				echo $this->Image->getBase64Image($userData['photo_name'], $userData['photo_content']);
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