<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('export', array('action' => $model,'export'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
?>
	<?php if (isset($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('SchoolYear.name'); ?></th>
					<th><?php echo $this->Label->get('EducationProgramme.title'); ?></th>
					<th><?php echo $this->Label->get('EducationGrade.title'); ?></th>
					<th><?php echo $this->Label->get('StudentFee.name'); ?></th>
					<th><?php echo $this->Label->get('StudentFee.paid'); ?></th>
					<th><?php echo $this->Label->get('StudentFee.outstanding'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$totalFee = 0;
				$totalPaid = 0;
				$totalOutstanding= 0;
				foreach($data as $key => $classData) {
					foreach($classData['ClassGrade'] as $key2 => $classGradeData) {
						$currentOutstanding = $data[$key]['ClassGrade'][$key2]['fees']-$data[$key]['ClassGrade'][$key2]['paid'];
						$totalFee += $data[$key]['ClassGrade'][$key2]['fees'];
						$totalPaid += $data[$key]['ClassGrade'][$key2]['paid'];
						$totalOutstanding += $currentOutstanding;
				?>
				<tr>
					<td><?php echo $classData['SchoolYear']['name']; ?></td>
					<td><?php echo $data[$key]['ClassGrade'][$key2]['programme_name'] ?></td>
					<td><?php echo $this->Html->link($data[$key]['ClassGrade'][$key2]['name'], array('action' => $model, 'index',$data[$key]['SchoolYear']['id'], $data[$key]['ClassGrade'][$key2]['education_grade_id'])); ?></td>
					<td><?php echo $this->Label->getCurrencyFormat($data[$key]['ClassGrade'][$key2]['fees']); ?></td>
					<td><?php 
						echo $this->Label->getCurrencyFormat($data[$key]['ClassGrade'][$key2]['paid']); 
					?></td>
					<td><?php echo $this->Label->getCurrencyFormat($currentOutstanding); ?></td>
				</tr>
				<?php 
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="3" style="text-align:right"><?php echo $this->Label->get('general.total'); ?></th>
					<th><?php echo $this->Label->getCurrencyFormat($totalFee); ?></th>
					<th><?php echo $this->Label->getCurrencyFormat($totalPaid); ?></th>
					<th><?php echo $this->Label->getCurrencyFormat($totalOutstanding); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
