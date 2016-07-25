<?php echo $this->element('students/header'); ?>
                
<div class="tab-content">
    <h4 class="heading">
        <span><?php echo $this->Label->get('contact.title'); ?></span>
        <?php 
        echo $this->FormUtility->link('back', array('action' => $page));
        echo $this->FormUtility->link('edit', array('action' => $page.'_edit', $id));
        ?>
    </h4>
    <?php echo $this->element('layout/alert'); ?>
    <?php echo $this->element('layout/view'); ?>
</div>

<?php echo $this->element('students/footer'); ?>
