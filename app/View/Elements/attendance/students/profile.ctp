<div class="profile">
    <div class="profile-img">
        <?php echo $this->Html->image('profile/class.jpg'); ?>
    </div>
    <div class="profile-content">
        <p>
            <i class="icon-classes"></i>
            <?php echo $link = $this->Html->link($className, array('controller'=> 'Classes', 'action'=> 'view', $classId), array('target' => '_self','escape' => false)); ?>
            <br>
            <i class="icon-staff"></i> Teacher(s):
            <?php 
				if(!empty($teachersData)){
					$teacherStr = '';
					foreach($teachersData as $teacher){
						if(!empty($teacherStr)){
							$teacherStr .=', ';
						}
						$teacherFullName = $teacher['SecurityUser']['first_name']." ".$teacher['SecurityUser']['last_name'];
						$openemisid = ' ('.$teacher['SecurityUser']['openemisid'].')';
						
						$teacherStr .= $this->Html->link($teacherFullName.$openemisid, array('controller'=> 'Staff', 'action'=> 'view', $teacher['Staff']['id']), array('target' => '_new'));
					}	
					
					echo $teacherStr;
				}
			?>
            <br>
            <i class="icon-students"></i> <?php  echo count($data)?> Student<?php echo (count($data)> 1) ? 's':'' ?> 
        </p>
        <div class="clearfix"></div>
    </div>
</div><!-- end profile -->
<div class="break"></div>