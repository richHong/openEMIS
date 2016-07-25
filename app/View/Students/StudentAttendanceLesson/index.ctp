<?php 
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->css('form', array('inline' => false));
echo $this->Html->script('app.table', array('inline' => false));
echo $this->Html->script('app.attendance', array('inline' => false));
$obj = $data['SecurityUser'];

$urlPath = $this->params['controller'].'/'.$this->params['action'].'/index';
$attendanceTypeDM = $this->Form->input('StudentAttendanceType', array(
	'options' => $attendanceTypeOptions,
	'selected' => $selectedAttendanceType,
	'div' => 'col-md-2',
	'class' => "form-control",
	'label' => false,
	'empty' => 'All',
	'onchange' => 'Attendance.searchAttendanceType(this,"day")'
	)
);

$this->extend('/Layouts/tabs'); // must be student tab
$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	// echo $this->FormUtility->link('export', array('action' => $model,'export'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
	// pr($attendanceType);
	// pr($data);
	// pr($attendancesList);
	// pr($attendanceTypeOptions);
	// pr($subjectsOptions);
	// pr($selectedGradeSubject);
	// pr($isEdit);
	// pr($selectedAttendanceType);
	// pr($startDate);
	// pr($endDate);
	// pr($header);

	// echo $this->element('attendance/datepicker', array('urlPath'=>$urlPath, 'formElements' => array($attendanceTypeDM))); 
	// echo $this->element('attendance/students/attendancetable');
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
			// 'onchange' => 'Attendance.searchSubjectList(this)'
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
 
		$urlPath = $this->params['controller'] . "/" . $this->params['action'];
		echo $this->element('attendance/datepicker', array('urlPath'=>$urlPath, 'formElements' => array($classLessonDM, $attendanceTypeDM))); 
	?>
	</div>
	<div class="table-responsive">
		<div class="form-group option-filter" style="margin-left: 0px;">
			<?php 
				foreach($attendanceType as $item){ 
					echo $item['StudentAttendanceType']['short_form']. " = ". $item['StudentAttendanceType']['name']."; ";
				}
			?>
		</div>
		<table class="table table-striped table-hover table-highlight table-bordered">
			<thead>
				<tr> 
					<th><?php echo $this->Label->get('date.date');  ?></th>
					<th><?php echo $this->Label->get('date.time');  ?></th>
					<th><?php echo $this->Label->get('general.attendance');  ?></th>
					<th><?php echo $this->Label->get('general.remark');  ?></th>
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
		<?php 
$this->end();
?>
