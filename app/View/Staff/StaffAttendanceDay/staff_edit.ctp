<?php
echo $this->Html->css('../js/plugins/timepicker/bootstrap-timepicker', array('inline' => false));
echo $this->Html->script('plugins/timepicker/bootstrap-timepicker', array('inline' => false));
echo $this->Html->script('app.form', array('inline' => false));
echo $this->Html->script('app.attendance', array('inline' => false));

$this->extend('/Layouts/tabs');
$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);

$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
$this->end();

$this->prepend('portletBody');
    //echo $this->element('attendance/datepicker');
$this->end();

$this->start('tabBody');
	?>
	<div class="form-group option-filter">
			<?php
			foreach ($attendanceType as $item) {
				echo $item['StaffAttendanceType']['short_form'] . " = " . $item['StaffAttendanceType']['name'] . "; ";
			}
			?>
		</div>
	<?php 
	echo $this->element('attendance/staff/edittable');
$this->end();
?>