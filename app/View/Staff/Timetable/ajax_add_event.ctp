<?php 
	$popup_id = $_POST['popup_id'];//"timetable_event_".$_POST['row']."_". $_POST['column'];
	$cell_id = $_POST['cell_id'];//"rc_".$_POST['row']."_". $_POST['column'];
	
	$editable = filter_var($_POST['editable'], FILTER_VALIDATE_BOOLEAN);
?>

<div id="<?php echo $popup_id?>" class="modal-table" > 
	<div class='modal-dialog'>
    <div class="modal-content">
        
        <h3 class="modal-header"><!--<a class="pull-right" onclick = "<?php echo 'timetable.close_event_popup(\''.$popup_id.'\',\''.$cell_id.'\')' ?>">x</a> --><?php echo $this->Label->get('class.lesson'); ?></h3>
        
        <?php echo $this->Form->create('Timetables', array(
                'default' => false, 
                'url' => array('controller' => 'Classes' , 'action' => 'Timetable/ajax_save_event'),
                'class' => 'form-horizontal pop-form',
                'novalidate' => true,
                'inputDefaults' => array(
                    'div' => 'form-group',
                    'label' => array('class' => 'col-md-3 control-label'),
					'between' => '<div class="col-md-8">',
					'after' => '</div>',
					'class' => 'form-control',
                )
            ));
        ?>
        <div class='modal-body'>
        <?php //echo $this->element('alert'); ?>
        <?php
            if(isset($_POST['entry_id'])) {
                echo $this->Form->hidden('TimetableEntry.id', array('value'=>!empty($data['TimetableEntry']['id'])? $data['TimetableEntry']['id'] : $_POST['entry_id']));
            }
            if(isset($_POST['timetable_id'])) {
                echo $this->Form->hidden('TimetableEntry.timetable_id', array('value'=>!empty($data['TimetableEntry']['timetable_id'])? $data['TimetableEntry']['timetable_id'] : $_POST['timetable_id']));
            }
            echo $this->Form->hidden('TimetableEntry.editable', array('value'=>json_encode($editable)));
            echo $this->Form->hidden('TimetableEntry.day_of_week', array('value'=>$_POST['date']['day_of_week']));
            echo $this->Form->hidden('TimetableEntry.start_time', array('value'=>$this->Timetable->minutesToTime($_POST['date']['start_time'], true)));
            echo $this->Form->hidden('TimetableEntry.end_time', array('value'=>$this->Timetable->minutesToTime($_POST['date']['end_time'], true)));

            $subject_options = array(
                                    'options' => $subjectOptions,
                                    'selected' => !empty($data['EducationSubject']['id'])? $data['EducationSubject']['id'] : '0',
                                    'disabled'=> $editable ? '':'disabled',
                                    'label' => array('text' => $this->Label->get('general.subject'),'class' => 'col-md-3 control-label')
                                    );
            $staff_options = array(
                                    'options' => $teacherOptions,
                                    'disabled'=> $editable ? '':'disabled',
                                    'selected' => !empty($data['TimetableEntry']['staff_id'])? $data['TimetableEntry']['staff_id'] : '0',
                                    'label' => array('text'=> $this->Label->get('general.teacher'),'class' => 'col-md-3 control-label')
                                    );
            $room_options = array(
                                    'options' => $locationOptions,
                                    'disabled'=> $editable ? '':'disabled',
                                    'selected' => !empty($data['TimetableEntry']['room_id'])? $data['TimetableEntry']['room_id'] : '0',
                                    'label' => array('text'=> $this->Label->get('general.location'),'class' => 'col-md-3 control-label')
                                    );

            if(!isset($_POST['entry_id'])) {
                $subject_options['empty'] = ' - '.$this->Label->get('general.optionChooseOne').' - ';
                $staff_options['empty'] = ' - '.$this->Label->get('general.optionChooseOne').' - ';
                $room_options['empty'] = ' - '.$this->Label->get('general.optionChooseOne').' - ';
            }
            
            echo $this->Form->input('TimetableEntry.education_subject_id', $subject_options);
            echo $this->Form->input('TimetableEntry.staff_id', $staff_options);
            echo $this->Form->input('TimetableEntry.room_id', $room_options);
        ?>
        
        
        </div>
        
        <div class='modal-footer'>
                    
                    <?php echo $this->Form->button($this->Label->get('general.close'), array('type' => 'reset', 'class' => 'btn btn-default', 'onclick' => 'timetable.close_event_popup(\''.$popup_id.'\',\''.$cell_id.'\')')); ?>

                    <?php if($editable){ ?>
                    <?php echo $this->Form->button($this->Label->get('general.save'), array('type' => 'submit', 'class' => 'btn btn-primary', 'onclick' => 'timetable.save_event(\''.$popup_id.'\',\''.$cell_id.'\')')); ?>
                    
                    <?php }?>            
        </div>
        
        <?php echo $this->Form->end(); ?>
    </div>
</div>
</div>


