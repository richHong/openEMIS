<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>OpenEmis: <?php echo $data['datablock1']['studentName']['data']; ?></title>
</head>
<?php // mai put css here 
echo $this->Html->css('studentReportCard');
?> 
<body>
<div class="copyright">
	<div class="dont-print">
		<?php 
		echo $this->Form->button($this->Label->get('general.print'), array('type' => 'submit', 'name' => 'submitType', 'value' => 'print', 'class' => 'dont-print', 'onclick' => 'printPage()'));
		$formOptions = $this->FormUtility->getFormOptions(array('action' => $model.'/printable/'.$selectedYear));
			echo $this->Form->create($model, $formOptions);
			// echo $this->Form->button($this->Label->get('general.saveAsPDF'), array('type' => 'submit', 'name' => 'submitType', 'value' => 'generatePDF', 'class' => 'dont-print'));
			echo $this->Form->end();
		$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, 'index', $selectedYear));
			echo $this->Form->create($model, $formOptions);
			echo $this->Form->button($this->Label->get('general.back'), array('type' => 'submit', 'name' => 'submitType', 'value' => 'back', 'class' => 'dont-print'));
			echo $this->Form->end();
		?>
	</div>
	<br>
</div><!-- end copyright -->

<div class="container">
	<div class="header-1">
		<div class="school-logo">
				<!-- school logo -->
				<?php echo $this->Html->image('openemisschool.png', array('width' => '40')); ?>
			</div>
		<h1><?php echo $data['headers']['schoolName']['data']; ?></h1>
	</div><!-- end header-1 -->

	<div class="header-2">
		<h2><?php echo $data['headers']['reportTitle']['data']; ?></h2>
		<h2><?php echo $data['headers']['reportSubtitle']['data']; ?></h2>
	</div><!-- end header-2-->

	<div class="info">
		<p>
			<!-- Page: <b>1</b>/<b>1</b><br /> -->
			<?php echo $data['headers']['reportDate']['data']; ?>
		</p>
	</div><!-- end info -->

	<div class="basic-detail">
		<table>
			<?php 
			$count = 0;
			foreach ($data['datablock1'] as $key => $row) {
			?>
				<tr>
					<td width="15%"><?php echo $row['title']; ?></td>
					<td>: <b><?php echo $row['data']; ?><b></td> <!-- width="35%" -->
				</tr>
			<?php 
				$count++; 
			} 
			?>
		</table>
	</div><!-- end basic-detail -->

	<?php
	if (array_key_exists('assessmentData', $data) && !empty($data['assessmentData'])) {
	?>
	<div class="report">
		<table>
			<thead>
				<tr>
				    <td class="sb">Subjects</td>
					<?php foreach($data['uniqueAssessmentItemType'] as $key => $row) { ?>
					<td> 
						<?php echo $row ?>
					</td>
					<?php } ?>
				    <!-- <td colspan="2" class="align-c"><?php echo $this->Label->get('StudentReportCard.overall'); ?></td> -->
				</tr>
				<tr>
				    <!-- <td><?php echo $this->Label->get('general.marks') ?></td>
					<td><?php echo $this->Label->get('general.grade') ?></td> -->
				</tr>
				</thead>
				<tbody>
				<?php foreach($data['assessmentData'] as $educationSubjectName => $educationSubjectData) { ?>
				<tr>
					<td class="sb"><?php echo $educationSubjectName; ?></td>
					<?php foreach($data['uniqueAssessmentItemType'] as $key => $assessmentItemTypeName) { ?>
						<?php 
						$currentMarks = '';
						$currentGrades = '';
						foreach($educationSubjectData as $key => $assessmentResultItem) { 
							if ($assessmentResultItem['AssessmentItemType']['name'] == $assessmentItemTypeName) {
								$currentMarks = $assessmentResultItem['AssessmentItemResult']['marks'];
								$currentGrades = $assessmentResultItem['AssessmentResultType_Item']['name'];
							}
						} ?>
						<td>
							<?php 
							if ($currentGrades != '') {
								echo $currentGrades;
							}
							if ($currentMarks != '') {
								echo ' ('.$currentMarks.')';
							}
							?>
						</td>
					<?php } ?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div><!-- end report -->
	<?php } ?>

	<!-- Attendance Table -->
	<?php
	if (!empty($studentAttendance)) {
	?>
	<div class="report">
		<table>
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
					<td style="text-align:center"><?php echo $row[0]['count']; ?></td>
					<?php } ?>
				</tr>
			</tbody>
		</table>
	</div>
	<?php } ?>
</div>

<div class="sign">
	<div class="sign-c">
		Teacher's Signature
	</div>
	<div class="sign-c">
		Principal's Signature
	</div>
	<div class="sign-c">
		Parent's Signature
	</div>
</div><!-- end sign -->

<div class="pdf-footer">Created by OpenSMIS</div>
</body>
</html>

<script>
	function printPage() {
		window.print();
	}
</script>