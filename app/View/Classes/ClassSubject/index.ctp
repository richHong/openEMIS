<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	echo $this->FormUtility->link('edit', array('action' => $model, 'edit'));
	echo $this->FormUtility->link('export', array('action' => $model,'export'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Classes/profile');
$this->end();

$this->start('tabBody');
?>
<?php if (isset($data) && !empty($data)) { ?>
<div class="table-responsive">
	<table class="table table-highlight table-bordered table-striped">
		<thead>
			<tr>
				<th><?php echo $this->Label->get('general.code'); ?></th>
				<th><?php echo $this->Label->get('EducationGrade.title'); ?></th>
				<th><?php echo $this->Label->get('general.code'); ?></th>
				<th><?php echo $this->Label->get('EducationSubject.title'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $obj) : ?>
			<tr>
				<td><?php echo $obj['EducationGrade']['code']; ?></td>
				<td><?php echo $obj['EducationGrade']['name'];?></td>
				<td><?php echo $obj['EducationSubject']['code']; ?></td>
				<td><?php echo $obj['EducationSubject']['name'];?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php } ?>

<?php
$this->end();
?>
