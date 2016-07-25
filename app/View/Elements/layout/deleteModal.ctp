<?php 
$title = $this->Label->get('general.deleteTitle');
$body = $this->Label->get('general.deleteMsg');
$formOptions = $this->FormUtility->getFormOptions($url);
$formOptions['class'] = 'modal-form';
$btn = $this->Form->create($model, $formOptions);
$btn .= $this->Form->button($this->Label->get('general.deleteTitle'), array('type' => 'submit', 'class' => 'btn btn-primary'));
$btn .= $this->Form->end();

echo $this->element('layout/modal', array(
	'modalId' => 'deleteModal',
	'modalTitle' => $title,
	'modalBody' => $body,
	'modalBtn' => $btn
));
?>