<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
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
	<div class="suject-right-a"><a href="#"><?php echo $this->Label->get('student.current'); ?> <i class="icon-edit"></i></a></div>
	<!--<a class="btn btn-inverse" href="#"><i class="icon-search"></i></a>-->
	<h1><span><?php echo $studentName?></span> (<?php echo $studentNRIC; ?>)</h1>
</div>


<div class="content">
	<?php echo $this->element('students/profile', array('obj' => $obj)); ?>
	<?php echo $this->element('students/navigations', array('action' => $this->action)); ?>
	
	<div class="details container-fluid">
		<div class="action-bar">
			<b><?php echo $this->Label->get('general.details'); ?></b> <?php // &nbsp; |echo $this->Html->link('<i class="icon-edit"></i> ' . $this->Label->get('student.addAcadProgress'), array('action' => 'edit', $studentId), array('escape' => false)); ?>
		</div>
		<?php echo $this->element('alert'); ?>
		
		<div class="overflow-scroll">
			<table class="table table-striped table-bordered table-hover">
            	<thead class="sort">
                	<tr>
                    	<td><i class="icon-sort-down"></i><?php echo $this->Label->get('student.progressDate'); ?></td>
                        <td><i class="icon-sort"></i><?php echo $this->Label->get('student.acadProgress'); ?></td>
                        <td><i class="icon-sort"></i><?php echo $this->Label->get('student.state'); ?></td>
                        <td>&nbsp;</td>
					</tr>
				</thead>
                <tbody>
                	<tr>
                    	<td>1 Jan 2012</td>
                        <td>New enrollment to Grade 1</td>
                        <td><?php echo $this->Label->get('student.current'); ?></td>
                        <td class="edit-function"><a><i class="icon-edit icon-large"></i></a></td>
					</tr>
                    <tr>
                    	<td>1 Jan 2013</td>
                        <td>Transfer from Grade 1 to Grade 2</td>
                        <td><?php echo $this->Label->get('student.current'); ?></td>
                        <td class="edit-function"><a><i class="icon-edit icon-large"></i></a></td>
					</tr>
                    <tr>
                    	<td>27 May 2013</td>
                        <td><?php echo $this->Label->get('student.dropOut'); ?></td>
                        <td><?php echo $this->Label->get('student.pass'); ?></td>
                        <td class="edit-function"><a><i class="icon-edit icon-large"></i></a></td>
					</tr>
                    <!-- last record will be highlight as <b> -->
                    <tr>
                    	<td><b>31 Dec 2014</b></td>
                        <td><b><?php echo $this->Label->get('student.grad'); ?></b></td>
						<td><b><?php echo $this->Label->get('student.alumni'); ?></b></td>
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