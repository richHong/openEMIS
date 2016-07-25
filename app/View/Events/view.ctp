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
		echo $this->FormUtility->link('back', array('action' => 'index'));
		echo $this->FormUtility->link('edit', array('action' => 'edit', $id));
		echo $this->FormUtility->link('deleteModal');
		?>
	</h4>

<?php
	echo $this->element('layout/view');
	// echo $this->element('layout/deleteModal', array('url' => $url, 'model' => $model));
$this->end();

$this->start('modalBody');
	$url = array('controller' => $this->params['controller'], 'action' => 'delete', $data[$model]['id'], $data[$model]['start_date']);
	echo $this->element('layout/deleteModal', array('url' => $url, 'model' => $model));
$this->end();
?>
