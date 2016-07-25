<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => 'index'));
	echo $this->FormUtility->link('add', array('action' => $model, 'add'));
$this->end();

$this->prepend('portletBody');
$this->end();

$this->start('tabBody');
$totalAmount = 0;
?>
	<?php if (!empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('EducationFee.fee_type_id') ?></th>
					<th><?php echo $this->Label->get('EducationFee.description') ?></th>
					<th><?php echo $this->Label->get('EducationFee.amount') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $obj['FeeType']['name']; ?></td>
					<td><?php echo $this->Html->link($obj['EducationFee']['description'], array('action' => $model, 'view', $obj['EducationFee']['id'])); ?></td>
					<td><?php echo $this->Label->getCurrencyFormat($obj['EducationFee']['amount']); ?></td>
				</tr>
				<?php 
				$totalAmount += $obj['EducationFee']['amount'];
				endforeach 
				?>
			</tbody>
			<tfoot>
				<tr>
					<th style="text-align:right" colspan="2"><?php echo $this->Label->get('general.total'); ?></th>
					<th><?php echo $this->Label->getCurrencyFormat($totalAmount); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
