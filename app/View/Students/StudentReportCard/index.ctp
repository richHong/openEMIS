<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $this->Label->get('general.reportCard'));

$this->start('tabActions');
	if (!empty($yearOptions)) {
		echo $this->FormUtility->link('printable', array('action' => $model, 'printable',$selectedYear), array('target' => '_blank'));// open in new window 
	}
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
?>		
	<?php if (!empty($yearOptions)) { ?>
	<div class="row">
		<div class="col-md-3">
		<?php
		echo $this->Form->input('school_year_id', array(
			'label' => false,
			'div' => false,
			'options' => $yearOptions,
			'default' => $selectedYear,
			'class' => 'form-control',
			'url' => $this->params['controller'] . '/' . $model . '/index',
			'onchange' => 'Form.change(this)',
			'autocomplete' => 'off'
		));
		?>
		</div>
	</div>
	<?php
		if (array_key_exists('headers', $data)) 
			echo $this->FormUtility->displayDataBlock($data['headers']);
		if (array_key_exists('datablock1', $data)) 
			echo $this->FormUtility->displayDataBlock($data['datablock1']);
		if (array_key_exists('assessmentData', $data)) 
			echo $this->element('layout/reportcard_assessment', array('assessmentData' => $data['assessmentData']));
		if (isset($studentAttendance)) {
			echo $this->element('attendance/students/studentattendancedisplay', $studentAttendance);
		}

		if (array_key_exists('datablock2', $data)) 
			echo $this->FormUtility->displayDataBlock($data['datablock2']);
	?>
	<?php } ?>

<?php
$this->end();
?>