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
    ?>
    
    <?php echo $this->Form->create('StudentAttendanceDay'); ?>
	<div class="table-responsive">
   		<table class="table table-striped table-hover table-bordered">
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
                        $studentAttendanceData = !empty($attendanceData)?$attendanceData['StudentAttendanceDay'][$student['Student']['id']]: array();
                ?>
                <tr>
                    <td><?php echo $this->Html->link($student['SecurityUser']['openemisid'], '/Students/view/'.$student['Student']['id'], array('target' => '_blank'));?></td>
                    <td><?php echo $student['SecurityUser']['first_name']?></td>
                    <td><?php echo $student['SecurityUser']['last_name'] ?></td>
                    <td colspan="2">
                        <?php 
                            $selectedAttendance = !empty($attendanceData['StudentAttendanceDay'])?$attendanceData['StudentAttendanceDay'][$student['Student']['id']][0]:'';
                            //pr($selectedAttendance);
                            echo $this->Form->hidden($key.'.StudentAttendanceDay.id', array('value'=>!empty($selectedAttendance['id'])?$selectedAttendance['id']:""));
                            echo $this->Form->hidden($key.'.StudentAttendanceDay.attendance_date', array('value'=> $selectedDate));
                            echo $this->Form->hidden($key.'.StudentAttendanceDay.student_id', array('value'=> $student['Student']['id']));
                            echo $this->Form->hidden($key.'.StudentAttendanceDay.session', array('value'=> $attendanceSession));
                            
                            echo $this->Form->input($key.'.StudentAttendanceDay.student_attendance_type_id', array(
                                'options' => $attendanceTypeOptions,
                                'selected' => !empty($selectedAttendance['attendance_type_id'])? $selectedAttendance['attendance_type_id']: '0',
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
                     <?php echo $this->FormUtility->getFormButtons($this->Form, array('div'=> 'form-buttons')); ?>
                    </td>
                 </tr>
            </tbody>
            
        </table>
   </div>
   <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('classes/footer'); ?>

<?php /*

<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->css('bootstrap-datetimepicker.min', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('app.attendance', array('inline' => false)); ?>
<?php echo $this->Html->script('bootstrap-datetimepicker.min', array('inline' => false)); ?>

<?php //pr($attendanceData)?>

<?php echo $this->element('attendance/header'); ?>
<?php echo $this->element('layout/headerbar'); ?>

<div class="content">
	<?php echo $this->element('attendance/students/profile'); ?>
    
    <div class="details non-fix">
    	<div class="action-bar">
            <b>Edit Attendance</b>
            <?php echo $this->Html->link('<i class="icon-arrow-left"></i> ' . __('Back'), array('action' => 'day_', $classId, $selectedDate), array('escape' => false)); ?>
        </div>
        
        <!--<form> -->
        <?php echo $this->Form->create('StudentAttendanceDay'); ?>
            <div class="overflow-scroll">
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
                            <td><?php echo date('D '."<b\\r/>". 'j/n', strtotime($selectedDate)) ?></td>
                            <td><?php echo $attendanceSessionSuffix ?> attendance</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="btn-group">
                                    <a class="pull-left dropdown-toggle btn-gray" data-toggle="dropdown" href="#">Mark all as <i class="icon-angle-down"></i></a>
                                    <ul class="dropdown-menu pull-left">
                                    <?php 
                                        foreach($attendanceType as $item){ 
                                            //echo $item['AttendanceType']['short_form']. " = ". $item['AttendanceType']['name']."; ";
                                            echo "<li><a onclick='Attendance.markAllAs(".$item['StudentAttendanceType']['id'].")'>".$item['StudentAttendanceType']['name']."</a></li>";
                                        }
                                    ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                     
                    </thead>
                    <tbody>
                    
                        <?php 
                        
                            foreach($data as $key=>$student){ 
                                $studentAttendanceData = !empty($attendanceData)?$attendanceData['StudentAttendanceDay'][$student['Student']['id']]: array();
                        ?>
                        <tr>
                            <td><?php echo $student['SecurityUser']['openemisid']?></td>
                            <td><?php echo $this->Html->link($student['SecurityUser']['first_name'], '/Students/view/'.$student['Student']['id'], array('target' => '_blank'));
    
     ?></td>
                            <td><?php echo $student['SecurityUser']['last_name'] ?></td>
                            <td colspan="2">
                            	<?php 
									$selectedAttendance = !empty($attendanceData['StudentAttendanceDay'])?$attendanceData['StudentAttendanceDay'][$student['Student']['id']][0]:'';
									//pr($selectedAttendance);
									echo $this->Form->hidden($key.'.StudentAttendanceDay.id', array('value'=>!empty($selectedAttendance['id'])?$selectedAttendance['id']:""));
									echo $this->Form->hidden($key.'.StudentAttendanceDay.attendance_date', array('value'=> $selectedDate));
									echo $this->Form->hidden($key.'.StudentAttendanceDay.student_id', array('value'=> $student['Student']['id']));
									echo $this->Form->hidden($key.'.StudentAttendanceDay.session', array('value'=> $attendanceSession));
									
									echo $this->Form->input($key.'.StudentAttendanceDay.student_attendance_type_id', array(
										'options' => $attendanceTypeOptions,
										'selected' => !empty($selectedAttendance['attendance_type_id'])? $selectedAttendance['attendance_type_id']: '0',
										'div' => false,
										'class' => 'input-mini AttendanceTypeDM',
										'label' => false)
									 );
								?>
                                
                                <?php
									 echo $this->Form->input($key.'.StudentAttendanceDay.remarks',array(
									 	'class'=> 'input-large', 
										'placeholder' => 'Remark',
										'value' => !empty($selectedAttendance['remarks'])?$selectedAttendance['remarks']:"",
										'div' => false,
										'label' => false,
										'escape' => false
									));
									
							  	?>
                           	</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="break"></div>
            <?php
				echo $this->Form->button('Save', array(
					'type' => 'submit', 
					'class'=> 'btn btn-inverse'
					)
				);
			?>
            
            <?php
				echo $this->Form->button('Cancel', array(
					'type' => 'reset', 
					'class'=> 'btn btn-inverse',
					'onclick' => 'Form.back()'
					)
				);
			?>
        <?php echo $this->Form->end(); ?>
       <!-- </form> -->
    </div>
</div>

*/ ?>