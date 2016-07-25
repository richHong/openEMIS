<div class="table-responsive">
    <?php if(!empty($selectedTimetable)) : ?>
    <div class="form-group option-filter">
        <div class="col-md-3">
            <?php  
                echo $this->Form->input('timetable', array(
                    'label' => false,
                    'selected' => $selectedTimetable,
                    'options' => $timetableList,
                    'class' => 'form-control',
                    'onchange' => 'timetable.search(this)',
                    'url' => sprintf('%s/%s/', $this->params['controller'], $this->params['action']),
                ));
            ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(!empty($timetableList)) : ?>
    <label class="control-label"><?php echo date('j F Y', strtotime($timetableStartEndTime['Timetable']['start_date'])); ?> - <?php echo date('j F Y', strtotime($timetableStartEndTime['Timetable']['end_date'])); ?></label>
    
    <div class="overflow-scroll timetable">
    <?php echo $this->element('schedule')?>
    </div>
    <?php endif; ?>
</div>