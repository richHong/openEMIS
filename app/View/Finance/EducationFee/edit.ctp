<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$id = '';
if (isset($this->data[$model])) {
	$id = $this->data[$model]['id'];
}

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model, ($action=='add' ? '' : 'view'), $id));
$this->end();

$this->prepend('portletBody');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => $model, $action, $id));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	$this->Form->end();
?>

<?php
$this->end();
?>
