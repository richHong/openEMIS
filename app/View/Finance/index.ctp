<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
$this->end();

$this->prepend('portletBody');
$this->end();

$this->start('tabBody');
?>
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

	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<th><?php echo $this->Label->get('EducationGrade.education_programme_id') ?></th>
					<th><?php echo $this->Label->get('EducationGrade.title') ?></th>
					<th><?php echo $this->Label->get('EducationFee.title') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $obj['EducationProgramme']['name']; ?></td>
					<td><?php echo $this->Html->link($obj['EducationGrade']['name'], array('action' => $model, 'index', $obj['EducationGrade']['id'])); ?></td>
					<td><?php echo $this->Label->getCurrencyFormat($obj[0]['fee']); ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
<?php
$this->end();
?>
