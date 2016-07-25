<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
echo $this->FormUtility->link('back', array('action' => $model, 'listing'));
	echo $this->FormUtility->link('add', array('action' => $model, 'add'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('StudentFee.created_user_id'); ?></th>
					<th><?php echo $this->Label->get('StudentFee.created'); ?></th>
					<th><?php echo $this->Label->get('StudentFee.comment'); ?></th>
					<th><?php echo $this->Label->get('StudentFee.paid'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$total_paid = 0;
				foreach($data as $obj) : 
				$total_paid += $obj[$model]['paid'];
				?>
				<tr>
					<td><?php echo $obj['CreatedUser']['full_name']; ?></td>
					<td><?php echo $obj[$model]['created']; ?></td>
					<td><?php echo $this->Html->link($obj[$model]['comment'], array('action' => $model, 'view', $obj[$model]['id'])); ?></td>
					<td><?php echo $this->Label->getCurrencyFormat($obj[$model]['paid']); ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="3" style="text-align:right"><?php echo $this->Label->get('general.total'); ?></th>
					<th><?php echo $this->Label->getCurrencyFormat($total_paid); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<div class="row" style="margin-top:40px">
		<div class="col-md-3">Fee</div>
		<?php if (isset($educationFeeData)&&!empty($educationFeeData)) { ?>
		<div class="col-md-6">
			<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered table-highlight">
					<thead>
						<th><?php echo $this->Label->get('StudentFee.type'); ?></th>
						<th><?php echo $this->Label->get('StudentFee.amount'); ?></th>
					</thead>

					<?php 
					$totalEducationFee = 0;
					foreach($educationFeeData as $key => $row) { 
						$totalEducationFee += $row['EducationFee']['amount'];

						?>
					<tr>
						<td><?php echo $row['FeeType']['name']; ?></td>
						<td><?php echo $row['EducationFee']['amount']; ?></td>	
					</tr>
					<?php } ?>
					<tfoot>
						<tr>
							<th style="text-align:right"><?php echo $this->Label->get('general.total'); ?></th>
							<th><?php echo $this->Label->getCurrencyFormat($totalEducationFee); ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<?php } else { ?>
			No Fees
		<?php } ?>
	</div>
<?php
$this->end();
?>
