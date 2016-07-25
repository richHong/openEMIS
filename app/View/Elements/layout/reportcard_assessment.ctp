<div class="row">
	<div class="col-md-3"><?php echo $this->Label->get('student.assessmentResult') ?></div>
	<div class="col-md-6">
		<?php
		if (array_key_exists('assessmentData', $data) && !empty($data['assessmentData'])) {
		?>
		<table class="table table-striped table-hover table-bordered table-highlight" style="margin-bottom:2em">
			<thead>
				<tr class="multiple-line">
					<th rowspan="2">
						<?php echo $this->Label->get('general.subjects'); ?>
					</th>
					<?php foreach($data['uniqueAssessmentItemType'] as $key => $row) { ?>
						<th rowspan = "2"> 
							<?php echo $row ?>
						</th>
					<?php } ?>
					<!-- <th colspan="2">
						<?php echo $this->Label->get('StudentReportCard.overall'); ?>
					</th>
				</tr>
				<tr>
					<td><?php echo $this->Label->get('general.marks') ?></td>
					<td><?php echo $this->Label->get('general.grade') ?></td>
				</tr> -->
			</thead>
			<tbody>
				<?php foreach($data['assessmentData'] as $educationSubjectName => $educationSubjectData) { ?>
					<tr>
						<td><?php echo $educationSubjectName; ?></td>
						
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
						<!-- <td></td>
						<td></td> -->
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>
	</div>

</div>

