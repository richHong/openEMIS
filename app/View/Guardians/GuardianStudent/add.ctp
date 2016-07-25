'<?php
echo $this->Html->css('../js/plugins/autocomplete/jquery-ui', array('inline' => false));
echo $this->Html->css('../js/plugins/autocomplete/autocomplete', array('inline' => false));
echo $this->Html->script('plugins/autocomplete/jquery-ui', array('inline' => false));
echo $this->Html->script('guardian.student', array('inline' => false));
echo $this->Html->script('app.form', array('inline' => false));


$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Guardians/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => $model.'/add'));
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->hidden('security_user_id', array('value' => $guardianId));
	echo $this->Form->input('search', array('id' => 'autocomplete', 'type' => 'text'));
	echo $this->Form->hidden('student_security_user_id', array('id' => 'StudentSecurityUserId'));
	echo $this->Form->input('first_name', array('id' => 'FirstName', 'disabled'));
	echo $this->Form->input('last_name', array('id' => 'LastName', 'disabled'));
	echo $this->Form->input('relationship_category_id', array('options' => $relationshipOptions));
	echo '<div class="col-md-offset-2 form-buttons">';
	echo $this->Form->button($this->Label->get('general.add'), array('type' => 'submit', 'class' => 'btn btn-primary'));
	echo '</div>';
	$this->Form->end();
?>

<?php
$this->end();
?>