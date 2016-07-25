<?php
	$data = $setupData['data'];
	$weekStart = $timetableStartEndTime['Timetable']['start_date']; 
?>

<!--  <div class='timetable-container'url="Classes/timetableAjaxAddEvent"> -->
<div class='timetable-container'url="<?php echo $setupData['addEditURL'] ?>">
<!-- Timetable Header -->
    <div class='timetable-header'>
        <div class='timetable-header-cell first'></div>
    <?php 
        for($t = 1; $t <= count($setupData['days_of_week']); $t++){
			
			//When showDate - true
			if($setupData['showDate']){
				$fullDay = date('Y-m-d', strtotime('+' . $t-1 . ' day', strtotime($weekStart)));
			}
    ?>
        <div class='timetable-header-cell <?php if($t == count($setupData['days_of_week'])){ echo 'last'; } ?>' id='<?php echo 'col_'.$t?>' dayOfWeek='<?php echo $t?>' day='<?php echo $setupData['days_of_week'][$t]?>' <?php if(!empty($fullDay)){echo 'full-day='. $fullDay;}?> >
        <?php echo $this->Label->get('date.' . strtolower($setupData['days_of_week'][$t])); ?>
        </div>
    <?php
        }; 
    ?>
    </div>
    
<!-- Timetable Contents -->  
    <?php 
        $columnStartTime = $setupData['start_time_of_day'];
        
        for($t = 0; $t < $setupData['num_of_row']; $t++){
            $columnEndTime = $columnStartTime + $setupData['lesson_duration'];
    ?>
        <div class='timetable-row'>
            <?php
                echo $this->Form->hidden('row_'.($t+1).'_start_time', array('value'=>$columnStartTime));
                echo $this->Form->hidden('row_'.($t+1).'_end_time', array('value'=>$columnEndTime));
            ?>
            <div class='timetable-cell first' ><?php echo $this->Timetable->minutesToTime($columnStartTime) ?> ~ <?php echo $this->Timetable->minutesToTime($columnEndTime) ?></div>
            
            <?php 
                $i = 0;
                foreach(array_keys($setupData['days_of_week']) as $key){	
                
                    $cell_id = 'rc_'.($t + 1).'_'.($i+1);
					
					$addFlag = true;
					if(!is_null($setupData['editFuture'])){
						//Check if the current date is smaller than the timetable date, 
						//the entry will not will selectable.
						$fullDay = date('Y-m-d', strtotime('+' . ($i + 1) . ' day', strtotime($weekStart)));
						if($fullDay > date('Y-m-d')){
							$addFlag = false;
						}
					}
            ?>
                <div id='<?php echo $cell_id ?>'  class='timetable-cell'>
                    <div class='container'>
                    
                        <?php if($setupData['addable']){ ?>
                        <div class='hotzone'  onclick='timetable.cell_click("<?php echo $cell_id ?>","<?php echo $setupData['timetable_id']?>","<?php echo json_encode($setupData['editable']); ?>")'></div>
                        <?php } ?>
                        
                        <div class='entry-wrapper'>
                            <?php
                            //check for the event on the date and time
                                $showAddEnrtyButton = false;
                            
                                for($p = 0 ; $p < count($data); $p++){
                                   if($data[$p]['TimetableEntry']['day_of_week'] == $key && 
                                        $this->Timetable->timeToMinutes($data[$p]['TimetableEntry']['start_time']) == $columnStartTime){
                                           
                                        $showAddEnrtyButton = true;
                            ?>
                                        <div id='entry_<?php echo $data[$p]['TimetableEntry']['id'] ?>' class='entry-holder' <?php if($addFlag) { ?>onclick='timetable.entry_click(this, <?php echo json_encode($setupData['editable']); ?>)' <?php } ?> >
									   <?php
                                            $subject_title = $data[$p]['EducationSubject']['name'];
                                            $teacher_inCharge = $data[$p]['SecurityUser']['full_name'];
                                            $subject_code = $data[$p]['EducationSubject']['code'];
                                            $room_name = $data[$p]['Room']['name'];
                                            $newTime = NULL;
                                            if(!empty($class_lessons)){
												
                                                foreach($class_lessons as $class_lesson){
                                                    if($class_lesson['ClassLesson']['timetable_entry_id'] == $data[$p]['TimetableEntry']['id']){
                                                        $start_time = strtotime($class_lesson['ClassLesson']['start_time']);
                                                        $end_time = strtotime($class_lesson['ClassLesson']['end_time']);
                                                        
                                                        $newTime = date('g:ia', $start_time) . ' - ' . date('g:ia', $end_time);
                                                        
                                                        $subjectObj = explode('-',$educationGradeSubjectOptions[$class_lesson['ClassLesson']['education_grade_subject_id']]);
                                                        $subject_title = trim($subjectObj[1]);
                                                        $subject_code = trim($subjectObj[0]);
                                                        //echo $subject_title;
                                                        
                                                        $teacherObj = explode('-',$staffOptions[$class_lesson['ClassLesson']['staff_id']]);
                                                        $teacher_inCharge = trim($teacherObj[1]);
                                                        $teacher_staffno = trim($teacherObj[0]);
                                                        break;
                                                    }
                                                }
                                            }
                                        ?>
                                        
                                        <?php
											if(!empty($newTime)){
												echo "<div class='entry-new-date'>".$newTime."</div>";
											}
										?>
                                            <div class='entry-title'><?php echo $subject_title ?></div>
                                            <div class='entry-code'><?php echo "(".$subject_code.")" ?></div>
                                            <div class='entry-pic'><?php echo $teacher_inCharge ?></div>
                                            <div class='entry-pic'><?php echo '('.$room_name.')'; ?></div>
                                        </div>
                            <?php
                                    }
                                }
                            ?>
                        </div>
                        <?php if(false/*$setupData['editable']*/){ ?>
                        <div class='action-wrapper <?php echo ($showAddEnrtyButton)? "" : "hide"?>'>
                            <div class='entry-action-holder' onclick='timetable.cell_click("<?php echo $cell_id ?>","<?php echo $setupData['timetable_id']?>","<?php echo json_encode($setupData['editable']); ?>")'>
                                <div class='entry-title'> + <?php echo $this->Label->get('general.addNewEntry'); ?></div>
                            </div>
                        </div>
                        <?php }?>
                    </div> 
                </div>
            <?php 
                    $i++;
                }; 
            ?>
        </div>
    <?php 
            $columnStartTime = $columnEndTime + $setupData['break_interval'];
        } 
    ?>
</div>
