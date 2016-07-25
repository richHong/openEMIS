<?php
$userData = $this->Session->read('InstitutionSite.data');
?>
<div class="row">
	<div class="profile-user">
		<div class="thumbnail pic">
			<?php
			if(empty($userData['photo_name'])) {
				echo $this->Html->image('profile/school.jpg');
			} else {
				echo $this->Image->getBase64Image($userData['photo_name'], $userData['photo_content']);
			}
			?>
		</div>
	</div>
</div>