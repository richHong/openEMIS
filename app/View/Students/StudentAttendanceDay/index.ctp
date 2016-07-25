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
	echo $this->FormUtility->link('export', array('action' => $model,'export'));
$this->end();

$this->prepend('portletBody');
    echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
    echo $this->element('attendance/datepicker', array('urlPath'=>$urlPath, 'formElements' => array($attendanceTypeDM))); 
    echo $this->element('attendance/students/attendancetable');
$this->end();
?>
