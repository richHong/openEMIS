<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	echo $this->FormUtility->link('add', array('action' => 'add'));
$this->end();

$this->start('tabBody');
?>
<?php if (isset($data) && !empty($data)) { ?>
<div class="table-responsive">
	<table class="table table-highlight table-bordered table-striped">
		<thead>
			<tr>
				<th><?php echo $this->Label->get('general.code'); ?></th>
				<th><?php echo $this->Label->get('Assessment.title'); ?></th>
				<th><?php echo $this->Label->get('general.description'); ?></th>
				<th><?php echo $this->Label->get('EducationGrade.title'); ?></th>
				<th><?php echo $this->Label->get('general.status'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $obj) : ?>
			<tr>
				<td><?php echo $obj['AssessmentItemType']['code'] ?></td>
				<td><?php echo $this->Html->link($obj['AssessmentItemType']['name'], array('action' => 'view', $obj['AssessmentItemType']['id'])) ?></td>
				<td><?php echo $obj['AssessmentItemType']['description'] ?></td>
				<td><?php echo $obj['EducationGrade']['name'] ?></td>
				<td><?php echo $this->FormUtility->getStatus($obj['AssessmentItemType']['visible']) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php } ?>

<?php
$this->end();
?>