<?php
$this->extend('/Layouts/portlet');

$this->assign('contentHeader', $contentHeader);

$this->start('portletHeader');
	echo $this->Icon->get('calendar');
	echo $this->Label->get('event.title');
$this->end();

$this->start('portletBody');
?>

	<h4 class="heading">
		<span><?php echo $contentHeader; ?></span>
		<?php
		echo $this->FormUtility->link('back', array('action' => 'view', $id));
		?>
	</h4>

<?php
	$formOptions = $this->FormUtility->getFormOptions(array('controller' => $this->params['controller'], 'action' => 'edit', $id));
	echo $this->Form->create($model, $formOptions);
	echo $this->Form->hidden($model.'.id');
	echo $this->element('layout/edit');
	echo $this->FormUtility->getFormButtons();
	echo $this->Form->end();
	
$this->end();
?>
