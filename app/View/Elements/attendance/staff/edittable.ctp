<!-- start -->
	<div class="portlet-content">
    
    <?php 	
		$formOptions = $this->FormUtility->getFormOptions(array('controller' =>  $this->params['controller'], 'action' => $this->params['action'] . '/staff_edit',$selectedDate));
		//$formOptions['inputDefaults']['div'] = false;
		$formOptions['inputDefaults']['label'] = array('class' => 'col-md-3 control-label');
		$formOptions['inputDefaults']['between'] = '<div class="col-md-4">';
		//$formOptions['id'] = 'datepickerForm';
		//$formOptions['link'] = $this->params['controller']."/".$this->params['action'];
		//pr($formOptions);
		echo $this->Form->create($model, $formOptions);

	
	/*echo $this->Form->create('StaffAttendanceDay', array(
	'class' => 'form-horizontal',
	'novalidate' => true,
	'inputDefaults' => array(
		'div' => 'control-group',
		'label' => array('class' => 'control-label'),
		'between' => '<div class="controls">',
		'after' => '</div>',
		'class' => 'input-large'
	)
));*/ ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered table-highlight">
            <thead>
                 <tr class="multiple-line">
                    <th rowspan="2"><?php echo $this->Label->get('SecurityUser.openemisid');  ?></th>
                    <th rowspan="2"><?php echo $this->Label->get('SecurityUser.full_name'); ?></th>
                    <th><?php echo date('D '."<b\\r/>". 'j/n', strtotime($selectedDate)) ?></th>
                </tr>
                <tr>
                    <th colspan="1">
                        <div class="btn-group">
                            <a class="pull-left dropdown-toggle btn-gray" data-toggle="dropdown" href="#">Apply first staff option to all other staffs <i class="icon-angle-down"></i></a>
                            <ul class="dropdown-menu pull-left">
                            <?php 
                                foreach($staffAttendanceTypeOptions as $key => $item){ 
                                    //echo $item['AttendanceType']['short_form']. " = ". $item['AttendanceType']['name']."; ";
                                    echo "<li><a onclick='Attendance.markAllAs(".$key.")'>".$item."</a></li>";
                                }
                            ?>
                            </ul>
                        </div>
                    </th>
                </tr> 
            </thead>

			

			<tbody>
                <?php 
                    foreach($data as $staff) { 
						$key = $staff['Staff']['id'];	
                ?>
                <tr>
                	<td><?php echo $this->Html->link($staff['SecurityUser']['openemisid'], '/Staff/view/'.$staff['Staff']['id'], array('target' => '_blank'));?></td>
                	<td><?php echo $staff['SecurityUser']['full_name']?></td>

                    <td>
                    	<?php 
					
							if(!empty($this->data[$key]['StaffAttendanceDay']['id'])){
								echo $this->Form->hidden($key.'.StaffAttendanceDay.id');//, array('value'=>!empty($staffAttendanceData['id'])?$staffAttendanceData['id']:""));
							}
							echo $this->Form->hidden($key.'.StaffAttendanceDay.attendance_date', array('value'=> $selectedDate));
							echo $this->Form->hidden($key.'.StaffAttendanceDay.staff_id', array('value'=> $key));
							//echo $this->Form->hidden($key.'.StaffAttendanceDay.session', array('value'=> $attendanceSession));
						?>
						<?php 
						// not a required feature
							// $classTimeCSS = $formOptions['inputDefaults']['class'].' timepicker'; 
							// $labelTimeCSS = array('class'=> $formOptions['inputDefaults']['label'], 'text'=> 'In time'); 
							// echo $this->FormUtility->timepicker($key.'.StaffAttendanceDay.start_time', array('id' => 'time', 'class' => $classTimeCSS, 'label'=> $labelTimeCSS, 'default' => '08:00 AM')); 	
						?>
						<?php 
						// not a required feature
						 // 	$labelTimeCSS = array('class'=> $formOptions['inputDefaults']['label'], 'text'=> 'Out time'); 
							// echo $this->FormUtility->timepicker($key.'.StaffAttendanceDay.end_time', array('id' => 'time', 'class' => $classTimeCSS, 'label'=> $labelTimeCSS , 'default' => '06:00 PM')); 	
						?>
                        <?php
							echo $this->Form->input($key.'.StaffAttendanceDay.staff_attendance_type_id', array(
								'options' => $staffAttendanceTypeOptions,
								'class' => 'form-control AttendanceTypeDM',
								'label' => array('text'=>'Attendance Category','class' => 'col-md-3 control-label'),
								)
							 );
						?>
                        <?php
							 echo $this->Form->input($key.'.StaffAttendanceDay.remarks',array(
								'label' => array('text'=>'Remark','class' => 'col-md-3 control-label'),
								'escape' => false
							));
							
					  	?>

                    </td>
				</tr>

                
                <?php  }  //end for loop?>
                  <tr>
                    <td colspan="2"></td>
                    <td>
                     <?php echo $this->FormUtility->getFormButtons(array('div'=> 'col-md-offset-3 form-buttons')); ?>
                    </td>
                 </tr>
                    
            </tbody>	




        </table>
    </div>
    <div class="break"></div>
 
<?php echo $this->Form->end(); ?>
    
	</div>
	<!-- start -->

<script type="text/javascript">
$(function() {
	if($('.timepicker').length > 0){
		$('.timepicker').timepicker();
	}
	
});
</script>

<?php /*echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->css('bootstrap-datetimepicker.min', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('app.attendance', array('inline' => false)); ?>
<?php echo $this->Html->script('bootstrap-datetimepicker.min', array('inline' => false)); ?>

<?php //pr($attendanceData)?>

<?php echo $this->element('attendance/header'); ?>
<?php echo $this->element('layout/headerbar'); ?>

<div class="content">
	<?php echo $this->element('attendance/staff/profile'); ?>
    
    <div class="details non-fix">
    	<div class="action-bar">
            <b>Attendance</b>
            <?php echo $this->Html->link('<i class="icon-arrow-left"></i> ' . __('Back'), array('action' => 'staff_index', $selectedDate), array('escape' => false)); ?>
        </div>
        
        <!--<form> -->
        <?php echo $this->Form->create('StaffAttendanceDay', array(
			'class' => 'form-horizontal',
			'novalidate' => true,
			'inputDefaults' => array(
				'div' => 'control-group',
				'label' => array('class' => 'control-label'),
				'between' => '<div class="controls">',
				'after' => '</div>',
				'class' => 'input-large'
			)
		)); ?>
            <div class="overflow-scroll">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                         <tr class="multiple-line">
                            <td rowspan="2"><?php echo __('Staff ID'); ?></td>
                            <td rowspan="2"><?php echo __('First name'); ?></td>
                            <td rowspan="2"><?php echo __('Last name'); ?></td>
                            <td><?php echo date('D '."<b\\r/>". 'j/n', strtotime($selectedDate)) ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="btn-group">
                                    <a class="pull-left dropdown-toggle btn-gray" data-toggle="dropdown" href="#">Apply first staff option to all other staffs <i class="icon-angle-down"></i></a>
                                    <ul class="dropdown-menu pull-left">
                                    <?php 
                                        foreach($staffAttendanceTypeOptions as $key => $item){ 
                                            //echo $item['AttendanceType']['short_form']. " = ". $item['AttendanceType']['name']."; ";
                                            echo "<li><a onclick='Attendance.markAllAs(".$key.")'>".$item."</a></li>";
                                        }
                                    ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                     
                    </thead>
                    <tbody>
                    
                        <?php 
                        
                            foreach($staffListData as $key=>$staff){ 
                                $staffAttendanceData = !empty($attendanceData)?$attendanceData['StaffAttendanceDay'][$staff['Staff']['id']]: array();
                        ?>
                        <tr>
                            <td><?php echo $staff['SecurityUser']['openemisid']?></td>
                            <td><?php echo $this->Html->link($staff['SecurityUser']['first_name'], '/Staffs/view/'.$staff['Staff']['id'], array('target' => '_blank'));
    
     ?></td>
                            <td><?php echo $staff['SecurityUser']['last_name'] ?></td>
                            <td>
                            	<?php 
									$selectedAttendance = !empty($attendanceData['StaffAttendanceDay'])?$attendanceData['StaffAttendanceDay'][$staff['Staff']['id']][0]:'';
									//pr($selectedAttendance);
									echo $this->Form->hidden($key.'.StaffAttendanceDay.id', array('value'=>!empty($selectedAttendance['id'])?$selectedAttendance['id']:""));
									echo $this->Form->hidden($key.'.StaffAttendanceDay.attendance_date', array('value'=> $selectedDate));
									echo $this->Form->hidden($key.'.StaffAttendanceDay.staff_id', array('value'=> $staff['Staff']['id']));
									//echo $this->Form->hidden($key.'.StaffAttendanceDay.session', array('value'=> $attendanceSession));
								?>
                                <?php 
									echo $this->Form->input($key.'.StaffAttendanceDay.start_time', array(
										'type'=> 'time', 
										'label'=> array('text'=> 'In time','class' => 'control-label'),
										'selected' => '08:00:00'
									)); 
								?>
                                <?php 
									echo $this->Form->input($key.'.StaffAttendanceDay.end_time', array(
										'type'=> 'time',
										'label'=> array('text'=> 'Out time','class' => 'control-label'),
										'selected' => '18:00:00'
									));
								?>
                                <?php
									echo $this->Form->input($key.'.StaffAttendanceDay.staff_attendance_type_id', array(
										'options' => $staffAttendanceTypeOptions,
										'selected' => !empty($selectedAttendance['staff_attendance_type_id'])? $selectedAttendance['staff_attendance_type_id']: '0',
										'class' => 'input-large AttendanceTypeDM',
										'label' => array('text'=>'Attendance Category','class' => 'control-label'),
										)
									 );
								?>
                                <?php
									 echo $this->Form->input($key.'.StaffAttendanceDay.remarks',array(
										'value' => !empty($selectedAttendance['remarks'])?$selectedAttendance['remarks']:"",
										'label' => array('text'=>'Remark','class' => 'control-label'),
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
</div> */ ?>