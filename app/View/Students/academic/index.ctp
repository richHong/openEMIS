<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->css('table', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('app.table', array('inline' => false)); ?>

<?php echo $this->element('students/header'); ?>
<?php echo $this->element('layout/headerbar'); ?>

<div class="content">
	<?php echo $this->element('students/profile'); ?>
	<?php echo $this->element('students/navigations', array('action' => $this->action)); ?>
	
	<div class="details container-fluid">
		<div class="action-bar">
			<b><?php echo $this->Label->get('general.academic'); ?></b>
			<?php
				echo $this->Html->link('<i class="icon-plus"></i> ' . $this->Label->get('general.add'), array('action' => 'academic_edit'), array('escape' => false));
			?>
		</div>
		<?php echo $this->element('alert'); ?>
		
		<div class="overflow-scroll">
			<table class="table table-striped table-bordered table-hover">
            	<thead class="sort">
                	<tr>
                    	<td><i class="icon-sort-down"></i><?php echo $this->Label->get('student.progressDate'); ?></td>
                        <td><i class="icon-sort"></i><?php echo $this->Label->get('student.acadProgress'); ?></td>
                        <td><i class="icon-sort"></i>Student state</td>
                        <td>&nbsp;</td>
					</tr>
				</thead>
                <tbody>
                	<tr>
                    	<td>1 Jan 2012</td>
                        <td>New enrollment to Grade 1</td>
                        <td>Current student</td>
                        <td class="edit-function"><a><i class="icon-edit icon-large"></i></a></td>
					</tr>
                    <tr>
                    	<td>1 Jan 2013</td>
                        <td>Transfer from Grade 1 to Grade 2</td>
                        <td>Current student</td>
                        <td class="edit-function"><a><i class="icon-edit icon-large"></i></a></td>
					</tr>
                    <tr>
                    	<td>27 May 2013</td>
                        <td>Drop out</td>
                        <td>Pass student</td>
                        <td class="edit-function"><a><i class="icon-edit icon-large"></i></a></td>
					</tr>
                    <!-- last record will be highlight as <b> -->
                    <tr>
                    	<td><b>31 Dec 2014</b></td>
                        <td><b>Graduate</b></td>
						<td><b>Alumni</b></td>
                        <td class="edit-function"><a><i class="icon-edit icon-large"></i></a></td>
					</tr>
				</tbody>
			</table>
		</div>

        <div class="btn-group">
        	<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#">2013 <!-- school year here, default current school year --><span class="caret"></span></a>
			<ul class="dropdown-menu">
            	<li><a href="#">2012</a></li>
                <li><a href="#">2013</a></li>
                <li><a href="#">2014</a></li>
			</ul>
		</div>
        
        <div class="break-section"></div>

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
			<div class="span4"><?php echo $this->Label->get('general.classes');; ?></div>
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