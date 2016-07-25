
<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $this->Label->get('general.general'));

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => 'view', $this->data[$model]['id']));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Guardians/profile');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => 'edit', $this->data[$model]['id']));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	$this->Form->end();
?>

<?php
$this->end();
?>