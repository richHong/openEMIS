<?php 
$data = $this->Session->read('Administrator.data');
 ?>

<div class="row">
	<div class="col-md-2">
		<div class="thumbnail">
			<?php
			if(empty($data['SecurityUser']['photo_name'])) {
				echo $this->Html->image('profile/staff.jpg');
			} else {
				echo $this->Image->getBase64Image($data['SecurityUser']['photo_name'], $data['SecurityUser']['photo_content']);
			}
			?>
		</div>
	</div>
</div>
