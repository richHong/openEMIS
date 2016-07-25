<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>

<div class="title">
	<h1>Students</h1>
	<?php echo $this->Html->link($this->Label->get('general.new') . ' +', array('controller' => 'Students', 'action' => 'add')); ?>
	<div class="clearfix"></div>
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
	<div class="suject-right-a"><a href="#"><?php echo $this->Label->get('student.current'); ?> <i class="icon-edit"></i></a></div>
	<a class="btn btn-inverse" href="#"><i class="icon-search"></i></a>
	
	<?php echo $this->element('layout/breadcrumbs'); ?>
</div>

<?php $obj = $data['SecurityUser']; ?>

<div class="content">
	<?php echo $this->element('students/profile', array('obj' => $obj)); ?>
	
	<?php echo $this->element('students/navigations', array('action' => $this->action)); ?>
	
	<div class="details container-fluid">
		<div class="action-bar">
			<b><?php echo $this->Label->get('general.details'); ?></b>
			<?php echo $this->Html->link('<i class="icon-edit"></i> ' . $this->Label->get('general.edit');, array('action' => 'academic_edit', $studentId), array('escape' => false)); ?>
		</div>
		<?php echo $this->element('alert'); ?>
		
		<div class="row-fluid">
			<div class="span4"><?php echo $this->Label->get('general.programmes'); ?></div>
			<div class="span8">
				<?php
					foreach ($classGradeOptions as $classGrade) {
						echo $classGrade['EducationProgramme']['name'];
					}
				?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4"><?php echo $this->Label->get('general.grades'); ?></div>
			<div class="span8">
				<?php
					foreach ($classGradeOptions as $classGrade) {
						echo $classGrade['EducationGrade']['name'];
					}
				?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4"><?php echo $this->Label->get('general.classes'); ?></div>
			<div class="span8">
				<?php
					foreach ($classStudentOptions as $key => $value) {
						echo $value . "<br/>";
					}
				?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4"><?php echo $this->Label->get('general.subjects'); ?></div>
			<div class="span8">
				<?php
					foreach ($classSubjectOptions as $classSubject) {
						echo $classSubject['EducationSubject']['name'] ." (" . $classSubject['EducationSubject']['code'] . ")" . "<br/>";
					}
				?>
			</div>
		</div>
		
	</div>
</div>