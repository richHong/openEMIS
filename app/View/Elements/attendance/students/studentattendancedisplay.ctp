
<div class="row">
	<div class="col-md-3"><?php echo $this->Label->get('general.attendance') ?></div>
	<div class="col-md-6">
		<table class="table table-striped table-hover table-bordered table-highlight" style="margin-bottom:2em">
			<thead>
					<tr>
						<?php foreach ($studentAttendance as $key => $row) { ?>	
							<th><?php echo $row['StudentAttendanceType']['name']; ?></th>
						<?php } ?>
					</tr>
			</thead>
			<tbody>
				<tr>
					<?php foreach ($studentAttendance as $key => $row) { ?>	
					<td><?php echo $row[0]['count']; ?></td>
					<?php } ?>
				</tr>
			</tbody>
		</table>
	</div>
</div>