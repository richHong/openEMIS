<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('classes', array('inline' => false)); ?>
<?php echo $this->Html->script('security.user', array('inline' => false)); ?>

<?php echo $this->element('students/header'); ?>
<?php echo $this->element('layout/headerbar'); ?>

<div class="content">
	<?php echo $this->element('students/profile'); ?>
	<?php echo $this->element('students/navigations', array('action' => 'guardian')); ?>
	
	<div class="details container-fluid">
		<div class="action-bar">
			<b><?php echo $this->Label->get('general.academicDetails'); ?></b>
			<?php 
				echo $this->Html->link('<i class="icon-arrow-left"></i> ' . $this->Label->get('general.back'), array('action' => 'academic_index'), array('escape' => false));
			//if(!empty($studentId)) :
			//	echo $this->Html->link('<i class="icon-trash"></i> ' . $this->Label->get('general.delete'), array('action' => 'academic_delete', $studentId), array('escape' => false));
			//endif;

			echo !empty($studentId) ? $this->Html->link('<i class="icon-trash"></i> ' . $this->Label->get('general.delete'), array('action' => 'academic_delete', $studentId), array('escape' => false)) : "";
			?>
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