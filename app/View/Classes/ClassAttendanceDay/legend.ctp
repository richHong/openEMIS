<div class="form-group">
	<?php 
		foreach($attendanceType as $item) {
			echo $item['StudentAttendanceType']['short_form']. " = ". $item['StudentAttendanceType']['name']."; ";
		}
	?>
</div>
