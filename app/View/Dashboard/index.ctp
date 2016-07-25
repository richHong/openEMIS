<?php 
$this->Html->script('highcharts/highcharts', false);
$this->Html->script('app.dashboard', false);

$attendanceLineChartName = 'attendanceLineChart';
$enrollmentBarChartName = 'enrollmentBarChart';
?>

<div id="content">
	<div id="content-header">
		<h1>Dashboard</h1>
	</div>

	<div id="content-container">
		<div class="row">
			<div class="col-md-12">
				<div class="profile-user">
					<div class="thumbnail pic">
						<?php 
						echo $this->Image->getBase64Image($data['InstitutionSite']['photo_name'], $data['InstitutionSite']['photo_content']);
						?>
					</div>
					<div class="info" style='margin-top:15px'>
						<div><h3><?php echo $data['InstitutionSite']['name']; ?></h3></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row" style='margin-top:10px'>
			<div class="col-md-6">
				<div class="portlet">
					<div class="portlet-header">
						<h3>School Information</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tbody>
								<?php 
								foreach ($institutionSiteDataFields as $key => $value) {
									echo '<tr>';
									echo '<td class="dashboard-datafield">';
									$checkVal = $this->Label->get($value[0].'.'.$value[1]);
									echo (!empty($checkVal))? $checkVal: Inflector::humanize($value[1]);
									echo '</td>';
									// echo '<td class="dashboard-spacer"></td>';
									echo '<td class="data">';
									echo $data[$value[0]][$value[1]];
									echo '</td>';
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				</div> 
			</div>
			<div class="col-md-6">
				<span id="gmap"></span>
				<script>
					$('#gmap').load(getRootURL() + 'Dashboard/viewMap/');
				</script>
			</div>
		</div>

		<div class="row" style='margin-top:10px'>
			<div class="col-md-6">
				<div class="portlet">
					<div class="portlet-header"><h3><?php echo $enrollmentOptions['title']; ?></h3></div>
					<div class="portlet-content">
						<div id='<?php echo $enrollmentBarChartName; ?>'>
							<div class="alert alert-info" role="alert"><?php echo $this->Label->get('general.noData'); ?></div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="portlet">
					<div class="portlet-header"><h3><?php echo $attendanceOptions['title']; ?></h3></div>
					<div class="portlet-content">
						<div id='<?php echo $attendanceLineChartName; ?>' style='margin-top:10px'>

							<?php 
							switch ($attendanceView) {
								case 'Day':
								echo '<div class="alert alert-info" role="alert">'.$this->Label->get('general.noData').'</div>';
								break;

								case 'Lesson':
								echo '<div class="alert alert-info" role="alert">'.$this->Label->get('StudentAttendanceLesson.featureNotAvailable').'</div>';
								break;
								
								default:
								echo '<div class="alert alert-info" role="alert">'.$this->Label->get('general.noData').'</div>';
								break;
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	$(function () {
		var enrollmentGrades = <?php echo json_encode($enrollmentGrades); ?>;
		var enrollmentData = <?php echo json_encode($enrollmentData); ?>;
		var options = <?php echo json_encode($enrollmentOptions); ?>;
		Dashboard.showChartEnrollment(enrollmentGrades,enrollmentData,options);

		var attendanceLineChartId = <?php echo $attendanceLineChartName; ?>;
		var attendanceLineChartData = <?php echo json_encode($attendanceData); ?>;
		var options = <?php echo json_encode($attendanceOptions); ?>;
		Dashboard.showChartAttendance(attendanceLineChartId,attendanceLineChartData,options);
	});
</script>



<?php 
// echo $this->Html->script('plugins/icheck/jquery.icheck.min array('inline' => false));
// echo $this->Html->script('plugins/select2/select2 array('inline' => false));
// echo $this->Html->script('plugins/tableCheckable/jquery.tableCheckable array('inline' => false));
// echo $this->Html->script('default/App array('inline' => false));
// echo $this->Html->script('default/raphael-2.1.2.min array('inline' => false));
// echo $this->Html->script('plugins/morris/morris.min array('inline' => false));
// echo $this->Html->script('default/demo/area array('inline' => false));
// echo $this->Html->script('default/demo/donut array('inline' => false));
// echo $this->Html->script('plugins/sparkline/jquery.sparkline.min array('inline' => false));
// echo $this->Html->script('plugins/fullcalendar/fullcalendar.min array('inline' => false));
// echo $this->Html->script('default/demo/calendar array('inline' => false));
// echo $this->Html->script('default/demo/dashboard array('inline' => false));
?>