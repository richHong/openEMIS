<?php echo $this->Html->css('form', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('class.attendance', array('inline' => false)); ?>

<div class="title">
	<h1><?php echo $this->Label->get('class.title'); ?></h1>
	<?php echo $this->Html->link($this->Label->get('general.new') . ' +', array('controller' => 'Classes', 'action' => 'add')); ?>
	<div class="clearfix"></div>
    <?php echo $this->element('layout/breadcrumbs'); ?>
</div>

<div class="subject">
	<div class="suject-right">
		<div class="btn-group">
			<a class="btn btn-inverse pull-right dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-angle-down"></i></a>
			<ul class="dropdown-menu pull-right">
				<li><a href="#"><?php $this->Label->get('general.print'); ?></a></li>
				<li><a href="#"><?php echo $this->Label->get('general.saveAsPDF'); ?></a></li>
			</ul>
		</div>
	</div>
	<!--<a class="btn btn-inverse" href="#"><i class="icon-search"></i></a>-->
	<h1><span><?php echo $class_name?></span></h1>
</div>

<div class="content">
	<?php echo $this->element('classes/profile'); ?>
	<?php echo $this->element('classes/navigations', array('action' => 'attendance')); ?>
	
	<div class="details container-fluid">
		<div class="action-bar">
			<b><?php echo $this->Label->get('general.attendance'); ?></b>
			<?php 
			$action = array('action' => 'attendance', $type);
			if($type==0) {
				$action[] = $selectedDate;
			} else {
				$action[] = $selectedSubject;
				$action[] = $selectedPeriod;
			}
			echo $this->Html->link('<i class="icon-arrow-left"></i> ' . $this->Label->get('general.back'), $action, array('escape' => false));
			?>
		</div>
		<?php echo $this->element('alert'); ?>
		
		<?php
		$model = $type==0 ? 'AttendanceDay' : 'AttendanceLesson';
		$action['controller'] = 'Classes';
		$action['action'] = $this->params['action'];
		echo $this->Form->create($model, array(
			'url' => $action, 
			'class' => 'form-horizontal',
			'novalidate' => true
		));
		?>
		<div class="control-wrapper">
			<div class="control-group">
				<label class="control-label control-label-left"><?php echo $this->Label->get('general.type'); ?></label>
				<div class="controls">
					<?php 
						echo $this->Form->input('type', array(
							'label' => false,
							'options' => $this->Utility->getAttendanceTypes(),
							'default' => $type,
							'url' => $this->params['controller'] . '/' . $this->params['action'],
							'onchange' => 'Form.change(this)'
						));
					?>
				</div>
				<?php if($type==0) { ?>
				<label class="control-label control-label-left control-break"><?php echo $this->Label->get('general.date'); ?></label>
				<div class="controls control-break">
					<?php 
					$btn = $this->Form->button('<i class="icon-search"></i>', array(
						'type' => 'button',
						'class' => 'btn btn-inverse',
						'url' => sprintf('%s/%s/%s/', $this->params['controller'], $this->params['action'], $type),
						'onclick' => 'ClassAttendance.search(this)'
					));
					echo $this->Form->input('attendance_date', array('type' => 'date', 'label' => false, 'selected' => $selectedDate, 'after' => $btn));
					?>
				</div>
				
				<?php } else { ?>
				<label class="control-label control-label-left control-break"><?php echo $this->Label->get('general.subject'); ?></label>
				<div class="controls control-break">
					<?php 
						echo $this->Form->input('subjects', array(
							'label' => false,
							'options' => $subjects,
							'selected' => $selectedSubject,
							'onchange' => 'ClassAttendance.getLessonPriod()',
							'id' => 'subjects'
						));
					?>
				</div>
				<label class="control-label control-label-left control-break"><?php echo $this->Label->get('general.period'); ?></label>
				<div class="controls control-break">
					<?php 
						$btn = $this->Form->button('<i class="icon-search"></i>', array(
							'type' => 'button',
							'class' => 'btn btn-inverse',
							'url' => sprintf('%s/%s/%s/', $this->params['controller'], $this->params['action'], $type),
							'onclick' => 'ClassAttendance.searchPeriod(this)'
						));
						
						echo $this->Form->input('period', array(
							'label' => false,
							'url' => sprintf('%s/%s', $this->params['controller'], 'getLessonPriod'),
							'options' => $period,
							'selected' => $selectedPeriod,
							'id' => 'period',
							'after' => $btn
						));
					?>
				</div>
				<?php } ?>
			</div>
		</div>
		<div class="break"></div>
		
		<div class="table-content">
			<table class="table table-striped table-bordered">
				<thead class="sort">
					<tr>
						<td><i class="icon-sort"></i><?php echo $this->Label->get('general.no'); ?></td>
						<td><i class="icon-sort-up"></i><?php echo $this->Label->get('general.idNo'); ?></td>
						<td><i class="icon-sort-down"></i><?php echo $this->Label->get('general.firstName'); ?></td>
						<td><i class="icon-sort-down"></i><?php echo $this->Label->get('general.lastName'); ?></td>
						<td><i class="icon-sort-down"></i><?php echo $this->Label->get('general.category'); ?></td>
						<td><i class="icon-sort-down"></i><?php echo $this->Label->get('general.remarks'); ?></td>
					</tr>
				</thead>
				<tbody>
					<?php 
						$num = 0;
						
						if(!empty($data)){
							//pr($data);
							foreach($data as $obj) {  
							
					?>
                            <tr>
                                <td><?php echo $num+1; ?></td>
                                <td><?php echo $obj['SecurityUser']['openemisid']; ?></td>
                                <td><?php echo $obj['SecurityUser']['first_name']; ?></td>
                                <td><?php echo $obj['SecurityUser']['last_name']; ?></td>
                                <td>
                                <?php 
                                   	echo $this->Form->hidden(NULL, array('value'=> $obj['ClassStudent']['class_id'], 'name' => 'data['.$num.']['.$model.'][class_id]'));
									if(!empty($obj[$model]['id']))
                                    	echo $this->Form->hidden(NULL, array('value'=> $obj[$model]['id'], 'name' => 'data['.$num.']['.$model.'][id]'));
                                    echo $this->Form->hidden(NULL, array('value'=> $obj['Student']['id'], 'name' => 'data['.$num.']['.$model.'][student_id]'));
                                    echo $this->Form->input(NULL, array(
                                        'label' => false, 
                                        'options' => $attendanceOptions,
                                        'name' => 'data['.$num.']['.$model.'][attendance_type_id]', 
                                        'selected' => !empty($obj[$model]['attendance_type_id'])? $obj[$model]['attendance_type_id'] : '',
                                        )
                                    ); 
                                ?>
                                </td>
                                <td>
                                <?php
                                     echo $this->Form->input('remarks', array(
                                            'label' => false, 
                                            'id' => 'remarks_'.$num,
                                            //'value'=> $obj['AttendanceDay']['remarks'],
                                            'name' => 'data['.$num.']['.$model.'][remarks]'
                                        )
                                    ); 
                                ?>
                                </td>
                            </tr>
					<?php
								$num++;
							} 
						}
					?>
				</tbody>
			</table>
		</div>
		
		<div class="break"></div>
		<div class="btn-group">
			<?php echo $this->Form->button($this->Label->get('general.save'), array('type' => 'submit', 'class' => 'btn btn-inverse', 'div' => false)); ?>
			<?php echo $this->Form->button($this->Label->get('general.cancel'), array('type' => 'reset', 'class' => 'btn btn-inverse', 'div' => false)); ?>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
