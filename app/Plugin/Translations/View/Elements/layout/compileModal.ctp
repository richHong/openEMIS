<?php 
$title = $this->Label->get('general.compileTitle');
$body = $this->Label->get('general.compileMsg');
$formOptions = $this->FormUtility->getFormOptions($url);

$formOptions['class'] = 'modal-form';
$btn = $this->Form->create($model, $formOptions);
$btn .= $this->Form->button($this->Label->get('general.compileTitle'), array('type' => 'submit', 'class' => 'btn btn-primary'));
$btn .= $this->Form->end();

echo $this->element('layout/modal', array(
	'modalId' => 'compileModal',
	'modalTitle' => $title,
	'modalBody' => $body,
	'modalBtn' => $btn
));
?>