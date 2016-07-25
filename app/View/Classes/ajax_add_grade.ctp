<div class="control-dynamic controls control-break">
	<?php echo $this->Form->input('ClassGrade.'. $index.'.id', array('type'=>'hidden')); ?>
	<?php echo $this->Form->input('ClassGrade.' . $index . '.education_grade_id', array('options' => $educationGradeOptions, 'label' => false, 'div' => false, 'class' => 'input-select')); ?>
</div>