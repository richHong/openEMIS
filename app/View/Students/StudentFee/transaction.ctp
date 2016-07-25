<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
echo $this->FormUtility->link('add', array('action' => $model, 'add'));
$this->end();

$this->prepend('portletBody');
	echo $this->element('../Students/profile');
$this->end();

$this->start('tabBody');
?>
	<?php if (isset($data) || true) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('date.date'); ?></th>
					<th><?php echo $this->Label->get('StudentFee.fitem'); ?></th>
					<th><?php echo $this->Label->get('StudentFee.fee'); ?></th>
					<th><?php echo $this->Label->get('StudentFee.payment'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				//foreach($data as $obj) : 
				foreach($data as $key => $row) {
				?>
				<tr>

					<td><?php echo $row['date']; ?></td>
					<td><?php 
					if (array_key_exists('id', $row)) {
						echo '<a href="">'.$row['item'].'</a>'; 
					} else {
						echo $row['item']; 
					}

					?></td>
					<td><?php if (array_key_exists('fee', $row)) echo $row['fee']; ?></td>
					<td><?php if (array_key_exists('payment', $row)) echo $row['payment']; ?></td>
				</tr>
				<?php
				}
				?>
			</tbody>

			<tfoot style="font-weight:bold">
				<tr>
					<td colspan="2" style="text-align:right">Total</td>
					<td><?php echo $total_fees; ?></td>
					<td><?php echo $total_payments; ?></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:right">Outstanding</td>
					<td colspan="2"><?php echo $outstanding_payments; ?></td>
				</tr>
			</tfoot>

		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
