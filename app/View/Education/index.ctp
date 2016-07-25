<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabBody');
	echo $this->element('../Education/controls');
?>	
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-highlight table-bordered">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('EducationProgramme.title'); ?></th>
					<th><?php echo $this->Label->get('class.educationGrades'); ?></th>
					<th><?php echo $this->Label->get('class.educationSubjects'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($data as $obj) : 
					$programme = $obj['EducationProgramme'];
					$programmeName = $programme['name'];
					$grades = $obj['EducationGrade'];
				?>
				<tr>
					<td rowspan="<?php echo $programme['rowspan']; ?>"><?php echo sprintf('%s<br />(%d %s)', $programme['name'], $programme['duration'], $this->Label->get('general.years')); ?></td>
					<?php
					if(count($grades) > 0) {
						for($i=0; $i<count($grades); $i++) {
							$gradeName = $grades[$i]['name'];
							$subjects = $grades[$i]['EducationSubject'];
							$rowspan = count($subjects) > 0 ? count($subjects) : 1;
							if($i==0) {
								echo '<td rowspan="' . $rowspan . '">' . $gradeName . '</td>';
							} else {
								echo '<tr><td rowspan="' . $rowspan . '">' . $gradeName . '</td>';
							}
							if(count($subjects) > 0) {
								for($j=0; $j<count($subjects); $j++) {
									$subj = $subjects[$j]['EducationSubject'];
									$subjName = $subj['name'];
									if(!empty($subj['code'])) {
										$subjName .= ' (' . $subj['code'] . ')';
									}
									if($j==0) {
										echo '<td>' . $subjName . '</td></tr>';
									} else {
										echo '</tr><tr><td>' . $subjName . '</td></tr>';
									}
								}
							} else {
								echo '<td></td></tr>';
							}
						}
					} else {
						echo '<td></td><td></td>';
					}
					?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php } ?>

<?php $this->end(); ?>
