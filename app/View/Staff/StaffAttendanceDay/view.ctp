<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->css('bootstrap-datetimepicker.min', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('app.table', array('inline' => false)); ?>
<?php echo $this->Html->script('app.attendance', array('inline' => false)); ?>
<?php echo $this->Html->script('bootstrap-datetimepicker.min', array('inline' => false)); ?>
<?php $obj = $data['SecurityUser']; ?>

<?php echo $this->element('staffs/header'); ?>
<?php echo $this->element('layout/headerbar'); ?>

<div class="content">
	<?php echo $this->element('staffs/profile', array('obj' => $obj)); ?>
	<?php echo $this->element('staffs/navigations', array('action' => $this->action)); ?>
	
	<div class="details container-fluid">
		<div class="action-bar">
			<b><?php echo __('Attendance'); ?></b><a href="#"><i class="icon-print"></i> Print attendance</a>
		</div>
		
        <?php 
			echo $this->element('alert');
			$urlPath = $this->params['controller'].'/'.$this->params['action'];
		
		?>
            
        	<div class="action-bar">
             <?php
			 	/*$classLessonDM = $this->Form->input('SubjectList', array(
                    'options' => $subjectsOptions,
                    'selected' => $selectedGradeSubject,
                    'div' => false,
                    'class' => 'input-large',
                    'label' => false,
                   // 'empty' => 'Subjects',
                    'onchange' => 'Attendance.searchAttendanceType("lesson")'
                    )
                );*/
				
                $attendanceTypeDM = $this->Form->input('StudentAttendanceType', array(
                    'options' => $attendanceTypeOptions,
                    'selected' => $selectedAttendanceType,
                    'div' => false,
                    'class' => 'input-medium',
                    'label' => false,
                    'empty' => 'All',
                    'onchange' => 'Attendance.searchAttendanceType(this,"staff")'
                    )
                );
		 
				//$urlPath .= '/'.$studentId;
				echo $this->element('attendance/datepicker', array('urlPath'=>$urlPath, 'formElements' => array($attendanceTypeDM))); 
			?>
            </div>
            <div class="overflow-scroll">
                <table class="table table-striped table-hover table-bordered">
                    <caption>
                        <?php 
                            foreach($attendanceType as $item){ 
								if($item != '-- No Data --'){
                              		echo $item['StaffAttendanceType']['short_form']. " = ". $item['StaffAttendanceType']['name']."; ";
								}
                            }
                        ?>
                    </caption>
                    <thead>
                        <tr>
                            <td><?php echo $this->Label->get('date');  ?></td>
                            <td><?php echo $this->Label->get('time');  ?></td>
                            <td><?php echo $this->Label->get('attendance.attendance');  ?></td>
                            <td><?php echo $this->Label->get('attendance.remark');  ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php  foreach ($attendancesList as $attendanceData) {  ?>
                        <tr>
                            <td><?php echo date('j M',strtotime($attendanceData['attendance_date'])) ?></td>
                            <td><?php echo date('H:ia',strtotime($attendanceData['start_time'])) ?> - <?php echo date('H:ia',strtotime($attendanceData['end_time'])) ?></td>
                            <td><?php echo $attendanceData['attendance_type_name'] ?></td>
                            <td><?php echo $attendanceData['remarks'] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
           	</div>

	</div>
</div>

