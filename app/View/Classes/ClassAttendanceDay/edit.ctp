<?php 
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->css('form', array('inline' => false));
echo $this->Html->script('app.table', array('inline' => false)); 
echo $this->Html->script('app.attendance', array('inline' => false)); 

$this->extend('/Layouts/tabs');
$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $this->Label->get('Attendance.title'));

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabBody');
?>
	
	<?php echo $this->Form->create('StudentAttendanceDay'); ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-highlight table-bordered">
			<?php echo $this->element('../Classes/ClassAttendanceDay/legend') ?>
			<thead>
				 <tr class="multiple-line">
					<th rowspan="2"><?php echo $this->Label->get('SecurityUser.openemisid');  ?></th>
					<th rowspan="2"><?php echo $this->Label->get('SecurityUser.full_name'); ?></th>
					<th><?php echo date('D '."<b\\r/>". 'j/n', strtotime($selectedDate)) ?></th>
					<th><?php echo $attendanceSessionSuffix ?> attendance</th>
				</tr>
				<tr>
					<th colspan="2">
						<div class="btn-group">
							<a class="pull-left dropdown-toggle btn-gray" data-toggle="dropdown" href="#">Mark all as <i class="icon-angle-down"></i></a>
							<ul class="dropdown-menu pull-left">
							<?php 
								foreach($attendanceType as $item){ 
									//echo $item['AttendanceType']['short_form']. " = ". $item['AttendanceType']['name']."; ";
									echo "<li><a onclick='Attendance.markAllAs(".$item['StudentAttendanceType']['id'].")'>".__($item['StudentAttendanceType']['name'])."</a></li>";
								}
							?>
							</ul>
						</div>
					</th>
				</tr>
			 
			</thead>
			<tbody>
			
				<?php 
				
					foreach($data as $key=>$student){ 
						$studentAttendanceData = (!empty($attendanceData['StudentAttendanceDay'])&&array_key_exists($student['Student']['id'], $attendanceData['StudentAttendanceDay']))?$attendanceData['StudentAttendanceDay'][$student['Student']['id']]: array();
				?>
				<tr>
					<td><?php echo $this->Html->link($student['SecurityUser']['openemisid'], '/Students/view/'.$student['Student']['id'], array('target' => '_blank'));?></td>
					<td><?php echo $student['SecurityUser']['full_name']?></td>
					<td colspan="2">
						<?php 
							$selectedAttendance = (!empty($attendanceData['StudentAttendanceDay'])&&array_key_exists($student['Student']['id'], $attendanceData['StudentAttendanceDay']))?$attendanceData['StudentAttendanceDay'][$student['Student']['id']][0]:'';
							echo $this->Form->hidden($key.'.StudentAttendanceDay.id', array('value'=>!empty($selectedAttendance['id'])?$selectedAttendance['id']:""));
							echo $this->Form->hidden($key.'.StudentAttendanceDay.attendance_date', array('value'=> $selectedDate));
							echo $this->Form->hidden($key.'.StudentAttendanceDay.student_id', array('value'=> $student['Student']['id']));
							echo $this->Form->hidden($key.'.StudentAttendanceDay.session', array('value'=> $attendanceSession));
							
							echo $this->Form->input($key.'.StudentAttendanceDay.student_attendance_type_id', array(
								'options' => $attendanceTypeOptions,
								'selected' => !empty($selectedAttendance['student_attendance_type_id'])? $selectedAttendance['student_attendance_type_id']: '0',
								'div' => 'col-md-3',
								'class' => 'form-control AttendanceTypeDM',
								'label' => false)
							 );
						?>
						
						<?php
							 echo $this->Form->input($key.'.StudentAttendanceDay.remarks',array(
								'class'=> 'form-control', 
								'placeholder' => 'Remark',
								'value' => !empty($selectedAttendance['remarks'])?$selectedAttendance['remarks']:"",
								'div' => 'col-md-4',
								'label' => false,
								'escape' => false
							));
							
						?>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="3"></td>
					<td colspan="2">
					 <?php echo $this->FormUtility->getFormButtons(array('div'=> 'form-buttons')); ?>
					</td>
				 </tr>
			</tbody>
			
		</table>
   </div>
   <?php echo $this->Form->end(); ?>
<?php $this->end() ?>
