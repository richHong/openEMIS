<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	echo $this->FormUtility->link('edit', array('controller' => $this->params['controller'], 'action' => $model, 'edit', $selectedSecondary));
$this->end();

$this->start('tabBody');
	echo $this->element('../Education/controls');
?>
	<div class="table-responsive">
		<table class="table table-hover table-striped table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('general.code'); ?></th>
					<th><?php echo $this->Label->get('EducationGrade.name'); ?></th>
					<th><?php echo $this->Label->get('general.code'); ?></th>
					<th><?php echo $this->Label->get('EducationSubject.name'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr row-id="<?php echo $obj[$model]['id']; ?>">
					<td><?php echo $obj['EducationGrade']['code']; ?></td>
					<td><?php echo $obj['EducationGrade']['name']; ?></td>
					<td><?php echo $obj['EducationSubject']['code']; ?></td>
					<td><?php echo $obj['EducationSubject']['name']; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

<?php $this->end(); ?>
