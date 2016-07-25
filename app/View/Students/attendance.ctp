<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->css('bootstrap-datetimepicker.min', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('app.table', array('inline' => false)); ?>
<?php echo $this->Html->script('class.attendance', array('inline' => false)); ?>
<?php echo $this->Html->script('bootstrap-datetimepicker.min', array('inline' => false)); ?>
<?php $obj = $data['SecurityUser']; ?>
<div class="title">
	<h1><?php echo $this->Label->get('student.title'); ?></h1>
	<?php echo $this->Html->link($this->Label->get('general.new') . ' +', array('controller' => 'Students', 'action' => 'add')); ?>
	<div class="clearfix"></div>
	<?php echo $this->element('layout/breadcrumbs'); ?>
</div>
<div class="subject">
	<div class="suject-right">
		<div class="btn-group">
			<a class="btn btn-inverse pull-right dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-angle-down"></i></a>
			<ul class="dropdown-menu pull-right">
				<li><a href="#"><?php echo $this->Label->get('general.print'); ?></a></li>
				<li><a href="#"><?php echo $this->Label->get('general.saveAsPDF'); ?></a></li>
			</ul>
		</div>
	</div>
	<!--<a class="btn btn-inverse" href="#"><i class="icon-search"></i></a>-->
	<h1><span><?php echo $studentName?></span> (<?php echo $studentNRIC; ?>)</h1>
</div>

<div class="content">
	<?php echo $this->element('students/profile', array('obj' => $obj)); ?>
	<?php echo $this->element('students/navigations', array('action' => $this->action)); ?>
	
	<div class="details container-fluid">
		<div class="action-bar">
			<b><?php echo $this->Label->get('general.attendance'); ?></b> &nbsp; | <a href="#"><i class="icon-print"></i> Print attendance</a>
		</div>
		<div class="btn-group">
            <a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#">
                <?php echo __(($type == 2)? $this->Label->get('student.lessonAttendance') : $this->Label->get('general.dayAttendance')); ?>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li>
				<?php 
					echo $this->Html->link($this->Label->get('student.dayAttendance'),
						array('controller'=> $this->params['controller'], 'action'=> $this->params['action'], 1), 
						array('target' => '_self','escape' => false));
				?>
                </li>
                <li>
				<?php 
					echo $this->Html->link($this->Label->get('student.lessonAttendance'),
						array('controller'=> $this->params['controller'], 'action'=> $this->params['action'], 2), 
						array('target' => '_self','escape' => false));
				?>
                </li>
            </ul>
        </div>
        <div class="break-section"></div>
        <?php 
			echo $this->element('alert');
			$urlPath = $this->params['controller'].'/'.$this->params['action'].'/'.$type;
			
			if($type == 1) {
		?>
            <div class="action-bar">
             <?php
                $attendanceTypeDM = $this->Form->input('AttendanceType', array(
                    'options' => $attendanceTypeOptions,
                    'selected' => $selectedAttendanceType,
                    'div' => false,
                    'class' => 'input-medium',
                    'label' => false,
                    'empty' => 'All',
                    'onchange' => 'Attendance.searchAttendanceType("day")'
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
                                echo $item['AttendanceType']['short_form']. " = ". $item['AttendanceType']['name']."; ";
                            }
                        ?>
                    </caption>
                   <thead>
                   		<tr  class="multiple-line">
                        	<td colspan="2" rowspan="2"><?php echo $this->Label->get('general.date'); ?></td>
                            <?php for($i = 0; $i < $attendanceSession; $i++ ){ ?>
                            <td colspan="2">
								<?php 
									$num = ($i + 1);
									switch($num)
									{ 
										case 1: $num.="st"; break;
										case 2: $num.="nd"; break;
										case 3: $num.="rd"; break;
										default: $num.="th"; break;
									}
									echo __($num. " session"); 
								?>
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <?php for($i = 0; $i < $attendanceSession; $i++ ){ ?>
                            <td><?php echo $this->Label->get('general.attendance'); ?></td>
                            <td><?php echo $this->Label->get('general.remark'); ?></td>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        foreach($attendancesList as $attendanceData){
                            $content = '<td>'.date('l',strtotime($attendanceData['attendance_date'])).'</td>';
                            $content .= '<td>'.date('j M',strtotime($attendanceData['attendance_date'])).'</td>';
                            $attendanceInfo = $attendanceData['session'];
                            
                            $num = 0;
                            for($i = 0; $i < $attendanceSession; $i++ ){
                                if(!empty($attendanceInfo[$num]) && $attendanceInfo[$num]['session'] == $i+1){
                                    $content .= '<td>'.$attendanceInfo[$num]['short_form'].'</td>';
                                    $content .= '<td>'.$attendanceInfo[$num]['remarks'].'</td>';
                                    $num++;
                                }
                                else{
                                    $content .= '<td>-</td>';
                                    $content .= '<td></td>';
                                }
                            }
                            echo "<tr>".$content."</tr>";
                        }
                    ?>
                   </tbody>
                </table>
            </div>
        <?php 
			} //End of type == 1 
			else {
		?>
        	<div class="action-bar">
             <?php
			 	$classLessonDM = $this->Form->input('SubjectList', array(
                    'options' => $subjectsOptions,
                    'selected' => $selectedGradeSubject,
                    'div' => false,
                    'class' => 'input-large',
                    'label' => false,
                   // 'empty' => 'Subjects',
                    'onchange' => 'Attendance.searchAttendanceType("lesson")'
                    )
                );
				
                $attendanceTypeDM = $this->Form->input('AttendanceType', array(
                    'options' => $attendanceTypeOptions,
                    'selected' => $selectedAttendanceType,
                    'div' => false,
                    'class' => 'input-medium',
                    'label' => false,
                    'empty' => 'All',
                    'onchange' => 'Attendance.searchAttendanceType("lesson")'
                    )
                );
		 
				//$urlPath .= '/'.$studentId;
				echo $this->element('attendance/datepicker', array('urlPath'=>$urlPath, 'formElements' => array($classLessonDM, $attendanceTypeDM))); 
			?>
            </div>
            <div class="overflow-scroll">
                <table class="table table-striped table-hover table-bordered">
                    <caption>
                        <?php 
                            foreach($attendanceType as $item){ 
                                echo $item['AttendanceType']['short_form']. " = ". $item['AttendanceType']['name']."; ";
                            }
                        ?>
                    </caption>
                    <thead>
                        <tr>
                            <td><?php echo $this->Label->get('general.date'); ?></td>
                            <td><?php echo $this->Label->get('general.time'); ?></td>
                            <td><?php echo $this->Label->get('general.attendance'); ?></td>
                            <td><?php echo $this->Label->get('general.remark'); ?></td>
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
           	</div>
        <?php } ?>
	</div>
</div>

