<?php 
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->css('form', array('inline' => false));
echo $this->Html->script('app.datepicker', array('inline' => false));

$this->extend('/Layouts/tabs');
$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabBody');
	
?>
	<?php echo $this->element('../Classes/ClassAttendanceDay/datepicker'); ?>
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-highlight table-bordered">
			<?php echo $this->element('../Classes/ClassAttendanceDay/legend') ?>
			<thead>
				<tr class="multiple-line">
					<th rowspan="2"><?php echo $this->Label->get('SecurityUser.openemisid');  ?></th>
					<th rowspan="2"><?php echo $this->Label->get('SecurityUser.full_name'); ?></th>
					<?php
						for($i = 0; $i < $dateDiff; $i ++){
							echo '<th colspan="'.$numOfSegment.'">'.date('D '."<b\\r/>". 'j/n', strtotime($startDate." +".$i." day"))."</th>";
						}
					?>
				</tr>
				<tr>
					<?php
						for($i = 0; $i < $dateDiff; $i ++){
							for($d = 0; $d < $numOfSegment; $d ++){
								$attendanceSession = $d+1;
								$tableDate = date( 'Y-m-d', strtotime($startDate." +".$i." day"));
								$link = $this->Html->link('<i class="fa fa-edit"></i>', array('controller'=> $this->params['controller'], 'action'=> 'ClassAttendanceDay/edit',$tableDate,$attendanceSession), array('target' => '_self','escape' => false));
								echo '<th>'.$link.'</th>';
							}
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($data as $student){ 
						$studentAttendanceData = array();
						if (array_key_exists('ClassAttendanceDay', $attendanceData)) {
							$studentAttendanceData = array_key_exists($student['Student']['id'], $attendanceData['ClassAttendanceDay'])?$attendanceData['ClassAttendanceDay'][$student['Student']['id']]: array();
						}
				?>
				<tr>
					<td><?php echo $this->Html->link($student['SecurityUser']['openemisid'], '/Students/view/'.$student['Student']['id'], array('target' => '_blank'));?></td>
					<td><?php echo trim($student['SecurityUser']['full_name']) ?></td>
					
					<?php
						for($i = 0; $i < $dateDiff; $i ++){
							$curDate = date('Y-m-d', strtotime($startDate." +".$i." day"));
							
							for($s = 0; $s < $numOfSegment; $s ++){
								$displayData = "-";
								foreach($studentAttendanceData as $selectedAttendance){
									if($selectedAttendance['attendance_date'] == $curDate && $selectedAttendance['session'] == ($s+1)){
										$displayData = $selectedAttendance['short_form'].(!empty($selectedAttendance['remarks'])?'<br /><span class="less-imp">'.$selectedAttendance['remarks'].'</span>':"");
										break;  
									}
								}
								
								echo "<td>".$displayData."</td>";
							}
						}   
					?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php } ?>

<?php $this->end() ?>
