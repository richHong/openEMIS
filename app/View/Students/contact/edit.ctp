<?php
echo $this->Html->script('app.form', array('inline' => false));
echo $this->element('students/header');
?>
                
<div class="tab-content">
    <h4 class="heading">
        <span><?php echo $this->Label->get('contact.title'); ?></span>
        <?php
            echo $this->FormUtility->link('back', array('action' => !empty($id) ? $page.'_view' : $page, $id));
            echo $this->FormUtility->link('deleteModal');
        ?>
    </h4>
    
    <?php
    echo $this->element('layout/alert');
    $formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => $this->action , $id));

    echo $this->Form->create($model, $formOptions);
    echo $this->Form->hidden('id', array('value' => $id));
    echo $this->Form->hidden('student_id', array('value' => $relatedModelId));
    echo $this->Form->hidden('security_user_id', array('value' => $securityUserId));
    
    echo $this->Form->input(
       'type',
       array(
           'options' => $typeOptions,
           'default' => $selectedTypeOption,
           'url' => sprintf('%s/%s/%s', $this->params['controller'], $this->params['action'], $id),
           'onchange' => 'Form.change(this)',
           'disabled' => true
       )
    );
    
    ?>
    
    <?php
    echo $this->Form->input(
        'contact_type_id',
        array(
            'label' => array(
                'text' => 'Description',
                'class' => 'col-md-2 control-label'
            ),
            'options' => $descOptions,
            'default' => $data['StudentContact']['contact_type_id']
        )
    );
    
    echo $this->Form->input('value', array('id' => 'value', 'value' => $data['StudentContact']['value']));
    
    echo $this->Form->input(
        'main',
        array(
            'label' => array(
                'text' => 'Preferred',
                'class' => 'col-md-2 control-label'
            ),
            'options' => $mainOptions,
            'default' => $data['StudentContact']['main']
        )
    );
    
    echo $this->FormUtility->getFormButtons($this->Form);
    echo $this->Form->end();
    ?>
</div>

<?php echo $this->element('students/footer'); ?>

<?php
$url = array('controller' => $this->params['controller'], 'action' => 'contact_delete', $id);
echo $this->element('layout/deleteModal', array('url' => $url, 'model' => 'StudentContact'));
?>
