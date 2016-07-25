<?php
if ($this->Session->check('Security.accessViewType')) {
	$accessViewType = $this->Session->read('Security.accessViewType');
} else {
	// maybe want to kill the operation as the person is an unidentified user
}

echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->css('form', array('inline' => false)
);

echo $this->Html->script('app.attendance', array('inline' => false));

$this->extend('/Layouts/tabs');
$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);

$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
$this->end();

$this->prepend('portletBody');
	if ($accessViewType==2)echo $this->element('../Staff/profile'); /****/
$this->end();

$this->start('tabBody');
	echo $this->element('attendance/datepicker');
	?>
	<div class="form-group option-filter">
			<?php
			foreach ($attendanceType as $item) {
				echo $item['StaffAttendanceType']['short_form'] . " = " . $item['StaffAttendanceType']['name'] . "; ";
			}
			?>
		</div>
	<?php 
	echo $this->element('attendance/staff/attendancetable');
$this->end();
?>