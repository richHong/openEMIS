<?php
$this->extend('/Layouts/portlet');

$this->assign('contentHeader', $contentHeader);

$this->start('portletHeader');
	echo $this->Icon->get('table');
	echo $this->Label->get('general.searchResults');
$this->end();

$this->start('portletBody');
echo $this->FormUtility->link('export', array('action' => 'export'));
?>
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<?php
		echo $this->Form->create($model, array('url' => array('controller' => $this->params['controller'], 'action' => 'search')));
		echo $this->Form->hidden('sortBy', array('class' => 'sortBy', 'value' => $sort['by']));
		echo $this->Form->hidden('sortOrder', array('class' => 'sortOrder', 'value' => $sort['order']));
		echo $this->Form->hidden('pageNo', array('value' => $pageNo));
		?>
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<?php
					echo $this->FormUtility->getSort($sort, 'SecurityUser.openemisid');
					echo $this->FormUtility->getSort($sort, $this->Session->read('name_display_format'), $this->Label->get('SecurityUser.full_name'));
					echo '<th>' . $this->Label->get('general.status') . '</th>';
					?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj['SecurityUser']['openemisid'], array('action' => 'view', $obj[$model]['id'])) ?></td>
					<td><?php echo $obj['SecurityUser']['full_name'] ?></td>
					<td><?php echo $obj['StudentStatus']['name'] ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		echo $this->Form->end();
		echo $this->element('layout/pagination');
		?>
	</div>
	<?php } ?>
<?php
$this->end();
?>
