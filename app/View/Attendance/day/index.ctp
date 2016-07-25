<?php echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false)); ?>
<?php echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));?>
<?php echo $this->Html->css('form', array('inline' => false));?>
<?php echo $this->Html->script('app.table', array('inline' => false)); ?>
<?php echo $this->Html->script('app.attendance', array('inline' => false)); ?>
<?php //$obj = $data['SecurityUser']; ?>

<?php echo $this->element('classes/header'); ?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('attendance.attendance'); ?></span>
	</h4>
	<?php
    echo $this->element('layout/alert');
    echo $this->element('attendance/datepicker');
    ?>
	<div class="table-responsive">
    	<table class="table table-striped table-hover table-highlight table-bordered">
                <div class="form-group option-filter">
                	<?php 
						foreach($attendanceType as $item){ 
                    		echo $item['StudentAttendanceType']['short_form']. " = ". $item['StudentAttendanceType']['name']."; ";
                   		}
				   	?>
                </div>
                <thead>
                    <tr class="multiple-line">
                        <th rowspan="2"><?php echo $this->Label->get('SecurityUser.openemisid');  ?></th>
                        <th rowspan="2"><?php echo $this->Label->get('first_name'); ?></th>
                        <th rowspan="2"><?php echo $this->Label->get('last_name'); ?></th>
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
									$link = $this->Html->link('<i class="fa fa-edit"></i>', array('controller'=> $this->params['controller'], 'action'=> 'attendance_class_edit', $classId, $attendanceSession, $tableDate), array('target' => '_self','escape' => false));
									echo '<th>'.$link.'</th>';
								}
							}
						?>
                    </tr>
                </thead>
                <tbody>
                
                	<?php 
					
						foreach($data as $student){ 
							$studentAttendanceData = !empty($attendanceData)?$attendanceData['StudentAttendanceDay'][$student['Student']['id']]: array();
					?>
                	<tr>
                        <td><?php echo $this->Html->link($student['SecurityUser']['openemisid'], '/Students/view/'.$student['Student']['id'], array('target' => '_blank'));?></td>
                        <td><?php echo $student['SecurityUser']['first_name']?></td>
                        <td><?php echo $student['SecurityUser']['last_name'] ?></td>
                        
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
</div>
<?php echo $this->element('classes/footer'); ?>
<?php /*
<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->css('bootstrap-datetimepicker.min', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('app.attendance', array('inline' => false)); ?>
<?php echo $this->Html->script('bootstrap-datetimepicker.min', array('inline' => false)); ?>

<?php echo $this->element('attendance/header', array('inline' => false)); ?>
<?php echo $this->element('layout/headerbar', array('inline' => false)); ?>

<div class="content">
	<?php echo $this->element('attendance/students/profile'); ?>
    
    <div class="details non-fix">
    	<div class="action-bar">
        	<?php 
				$urlPath = $this->params['controller']."/".$this->params['action'].'/'.$classId;
				echo $this->element('attendance/datepicker', array('urlPath'=>$urlPath)); 
			?>
        </div>
        
        <div class="overflow-scroll">
        	<?php echo $this->element('alert'); ?>
            <table class="table table-striped table-hover table-bordered">
                <caption>
                	<?php 
						foreach($attendanceType as $item){ 
                    		echo $item['StudentAttendanceType']['short_form']. " = ". $item['StudentAttendanceType']['name']."; ";
                   		}
				   	?>
                </caption>
                <thead>
                    <tr class="multiple-line">
                        <td rowspan="2"><?php echo __('Student ID'); ?></td>
                        <td rowspan="2"><?php echo __('First name'); ?></td>
                        <td rowspan="2"><?php echo __('Last name'); ?></td>
                     	<?php
							for($i = 0; $i < $dateDiff; $i ++){
								echo '<td colspan="'.$numOfSegment.'">'.date('D '."<b\\r/>". 'j/n', strtotime($startDate." +".$i." day"))."</td>";
							}
						?>
                    </tr>
                    <tr>
                        <?php
							for($i = 0; $i < $dateDiff; $i ++){
								for($d = 0; $d < $numOfSegment; $d ++){
									$attendanceSession = $d+1;
									$tableDate = date( 'Y-m-d', strtotime($startDate." +".$i." day"));
									$link = $this->Html->link('<i class="icon-edit icon-large"></i>', array('controller'=> $this->params['controller'], 'action'=> 'day_edit', $classId, $attendanceSession, $tableDate), array('target' => '_self','escape' => false));
									echo '<td>'.$link.'</td>';
								}
							}
						?>
                    </tr>
                </thead>
                <tbody>
                
                	<?php 
					
						foreach($data as $student){ 
							$studentAttendanceData = !empty($attendanceData)?$attendanceData['StudentAttendanceDay'][$student['Student']['id']]: array();
					?>
SecurityUser   	
                        <td><?php echo $student['SecurityUser']['openemisid']?></td>
                        <td><?php echo $this->Html->link($student['SecurityUser']['first_name'], '/Students/view/'.$student['Student']['id'], array('target' => '_blank'));

 ?></td>
                        <td><?php echo $student['SecurityUser']['last_name'] ?></td>
                        
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
    </div>
</div>

*/ ?>