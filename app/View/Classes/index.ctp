<?php
$this->extend('/Layouts/portlet');

$this->assign('contentHeader', $header);

$this->start('portletHeader');
	echo $this->Icon->get('table');
	echo $this->Label->get('SClass.list');
$this->end();

$this->start('portletBody');
echo $this->FormUtility->link('export', array('action' => 'export'));
?>
	<?php if (isset($yearOptions) && !empty($yearOptions)) { ?>
	<div class="row">
		<div class="col-md-3">
			<?php
			echo $this->Form->input('school_year_id', array(
				'label' => false,
				'div' => false,
				'options' => $yearOptions,
				'default' => $selectedYear,
				'class' => 'form-control',
				'url' => $this->params['controller'] . '/index',
				'onchange' => 'Form.change(this)',
				'autocomplete' => 'off'
			));
			?>
		</div>
	</div>
	<?php } ?>
	
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('SClass.title') ?></th>
					<th><?php echo $this->Label->get('EducationGrade.title') ?></th>
					<th><?php echo $this->Label->get('SClass.seats_total') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj[$model]['name'], array('action' => 'view', $obj[$model]['id'])) ?></td>
					<td>
						<?php
						foreach ($obj['ClassGrade'] as $grade) {
							echo '<div>' . $programmes[$grade['EducationGrade']['education_programme_id']] . ' - ' . $grade['EducationGrade']['name'] . '</div>';
						}
						?>
					</td>
					<td><?php echo $obj[$model]['seats_total'] ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<?php
$this->end();
?>
