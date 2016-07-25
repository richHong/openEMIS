<?php echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false)); ?>
<?php echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));?>
<?php echo $this->Html->css('form', array('inline' => false));?>
<?php echo $this->Html->script('app.table', array('inline' => false)); ?>
<?php echo $this->Html->script('app.attendance', array('inline' => false)); ?>
<?php $obj = $data['SecurityUser']; ?>

<?php echo $this->element('students/header'); ?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $this->Label->get('attendance.attendance'); ?></span>
	</h4>
		
        <?php 
			$urlPath = $this->params['controller'].'/'.$this->params['action'].'/'.$selectedGradeSubject;
		
		?>
            
        	<div class="action-bar">
             <?php
			 	$classLessonDM = $this->Form->input('SubjectList', array(
                    'options' => $subjectsOptions,
                    //'selected' => $selectedGradeSubject,
                    'div' => 'col-md-2',
           			'class' => "form-control",
                    'label' => false,
					//'empty' => 'All',
                   // 'empty' => 'Subjects',
                    'onchange' => 'Attendance.searchAttendanceType(this,"lesson")'
                    )
                );
				
                $attendanceTypeDM = $this->Form->input('StudentAttendanceType', array(
                    'options' => $attendanceTypeOptions,
                   // 'selected' => $selectedAttendanceType,
                    'div' => 'col-md-2',
           			'class' => "form-control",
                    'label' => false,
					//'empty' => 'All',
                    'onchange' => 'Attendance.searchAttendanceType(this,"lesson")'
                    )
                );
		 
				//$urlPath .= '/'.$studentId;
				echo $this->element('attendance/datepicker', array('urlPath'=>$urlPath, 'formElements' => array($classLessonDM, $attendanceTypeDM))); 
			?>
            </div>
            <div class="table-responsive">
            	<table class="table table-striped table-hover table-bordered">
                    <caption>
                        <?php 
						if(!empty($attendanceType)){
                            foreach($attendanceType as $key => $item){ 
								if(!empty($key)){
								    echo $item['StudentAttendanceType']['short_form']. " = ". $item['StudentAttendanceType']['name']."; ";
								}
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
                    	<?php foreach ($attendancesList as $attendanceData) {?>
                        <tr>
                            <td><?php echo date('j M',strtotime($attendanceData['start_time'])) ?></td>
                            <td><?php echo date('H:ia',strtotime($attendanceData['start_time'])) ?> - <?php echo date('H:ia',strtotime($attendanceData['end_time'])) ?></td>
                            <td><?php echo $attendanceData['short_form'] ?></td>
                            <td><?php echo $attendanceData['remarks'] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
              <?php echo $this->element('layout/alert'); ?>
        </div> <!-- here -->
</div>

<?php echo $this->element('classes/footer'); ?>
