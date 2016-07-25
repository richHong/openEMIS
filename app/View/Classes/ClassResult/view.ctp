<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => $model, 'index'));
	echo $this->FormUtility->link('edit', array('action' => $model, 'edit', $id));
	// echo $this->FormUtility->link('export', array('action' => $model,'exportView'));
$this->end();

$this->start('tabBody');
?>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-checkable table-input table-highlight">
		<thead>
			<tr>
				<th><?php echo $this->Label->get('SecurityUser.openemisid'); ?></th>
				<th><?php echo $this->Label->get('general.name'); ?></th>
				<th><?php echo $this->Label->get('ClassResult.marks'); ?></th>
				<th><?php echo $this->Label->get('ClassResult.grading'); ?></th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($data as $i => $obj) : ?>
			<tr>
				<td><?php echo $obj['SecurityUser']['openemisid']; ?></td>
				<td><?php echo trim($obj['SecurityUser']['first_name'] . ' ' . $obj['SecurityUser']['last_name']); ?></td>
				<td><?php echo $obj[$model]['marks']; ?></td>
				<td><?php echo $obj['AssessmentResultType']['name']; ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

<?php $this->end(); ?>
