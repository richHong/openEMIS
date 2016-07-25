<?php
$picture = $this->Session->read('StudentGuardian.picture');
?>

<div class="row">
	<div class="col-md-2">
		<div class="thumbnail">
			<?php
			if(empty($picture)) {
				echo $this->Html->image('profile/staff.jpg');
			} else {
				$image = sprintf('<img src="data:image/%s;base64,%s" />', $picture['type'], $picture['content']);
				echo $image;
			}
			?>
		</div>
	</div>
</div>