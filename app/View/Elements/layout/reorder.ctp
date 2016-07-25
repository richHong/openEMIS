<?php
if ($index!=$size) {
	echo '<i class="fa fa-level-down" move="last" onclick="Reorder.move(this)"></i>&nbsp;';
}
if ($index!=$size) {
	echo '<i class="fa fa-long-arrow-down" move="down" onclick="Reorder.move(this)"></i>&nbsp;';
}
if ($index>1) {
	echo '<i class="fa fa-long-arrow-up" move="up" onclick="Reorder.move(this)"></i>&nbsp;';
}
if ($index>1) {
	echo '<i class="fa fa-level-up" move="first" onclick="Reorder.move(this)"></i>';
}
?>
