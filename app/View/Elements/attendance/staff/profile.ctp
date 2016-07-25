<div class="profile">
    <div class="profile-img">
        <?php echo $this->Html->image('profile/school.jpg'); ?>
    </div>
    <div class="profile-content">
        <p>
            <i class="icon-staff"></i>  <?php  echo count($data)?> current staff<?php /*echo (count($data)> 1) ? 's':'' */?> 
        </p>
        <div class="clearfix"></div>
    </div>
</div><!-- end profile -->
<div class="break"></div>