        <?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->css('jquery-ui.min', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('app.attendance', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-ui.min', array('inline' => false)); ?>

<div class="title">
	<h1><?php echo __('Attendance'); ?></h1>
	<div class="clearfix"></div>
    <?php echo $this->element('layout/breadcrumbs'); ?>
</div>

<div class="subject">
	<div class="suject-right">
		<div class="btn-group">
			<a class="btn btn-inverse pull-right dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-angle-down"></i>
			</a>
			<ul class="dropdown-menu pull-right">
				<li><a href="#">Print</a></li>
				<li><a href="#">Save as PDF</a></li>
			</ul>
		</div>
	</div>
	<div class="visible-phone"><br /><br /></div>
    <?php 
		echo $this->Html->link('Staff Attendance', '/Attendance/staff_index', array('target' => '_self', 'class' => 'btn btn-inverse'));
	?>
</div>

<div class="content">
<?php echo $this->element('alert'); ?>
	<?php
		echo $this->Form->create('Attendance', array(
			
			//'url' => '/'.$this->request->params['controller'].'/view/'.$type,
			'class' => 'form-horizontal',
			'novalidate' => true,
			'inputDefaults' => array(
				'div' => 'control-group',
				'label' => array('class' => 'control-label control-label-left'),
				'between' => '<div class="controls">',
				'after' => '</div>',
				'class' => 'input-xlarge'
			)
		));
	?>
    
    <?php 
		/*echo $this->Form->input('attendaceType', array(
			'url' => $this->request->params['controller'].'/index', 
			'label'=>array('text'=>'Student attendance type', 
			'class' => 'control-label control-label-left'), 
			'options' => array('1'=>'Day attendance', '2'=>'Lesson attendance'),
			'default' => $type,
			'onchange' => 'Form.change(this)'
		)); */
		
		echo $this->Form->hidden('attendaceType', array('value' => $attendanceView));
	?>
    <?php 
    if($attendanceView == 'day'){
    	echo $this->Form->input('name', array(
			'label'=>array('text'=>'Class name', 'class' => 'control-label control-label-left'), 
			'url' => $this->request->params['controller'].'/ajax_find_class')
		);
    	//echo $this->Form->input('AttendanceDay.classId');
  	}
    else{
	//	pr($classOptions);
    	echo $this->Form->input('subjectId', array(
			'label'=>array('text'=>'Subject name', 'class' => 'control-label control-label-left'), 
			'options' => $subjectOptions, 
			'empty' => $subjectEmptyDisplay,
			'selected' => !empty($selectedSubjectId)? $selectedSubjectId : "",
			'onchange' => 'Attendance.getClassList(this, "AttendanceClassId")',
			'url' => $this->request->params['controller'].'/getClassBySubjectId'
			
		));
		echo $this->Form->input('classId', array(
			'label'=>array('text'=>'Class name', 'class' => 'control-label control-label-left'), 
			'options' =>  !empty($classOptions)? $classOptions : array(), 
			'disabled' => empty($classOptions)?'disabled': false,
			'empty' => '-- Please select --',
			'selected' => !empty($selectedClassId)? $selectedClassId : "",
		));
    } 
	?>
    <div class="control-group">
    	<div class="controls">
		<?php 
        	echo $this->Form->button('Search', array(
                'type' => 'submit',
                'class' => 'btn btn-inverse',
            ));
        ?>
    	</div>
    </div>
    
    <?php echo $this->Form->end(); ?>
</div>