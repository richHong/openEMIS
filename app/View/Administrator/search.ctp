<?php
$this->extend('/Layouts/portlet');

$this->assign('contentHeader', $contentHeader);

$this->start('portletHeader');
	echo $this->Icon->get('table');
	echo $this->Label->get('general.searchResults');
$this->end();

$this->start('portletBody');
?>
	<div class="table-responsive">
		<?php
		echo $this->Form->create($model, array('url' => array('controller' => $this->params['controller'], 'action' => 'search')));
		?>
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead>
				<tr>
					<?php
					echo '<th>' . $this->Label->get('SecurityUser.openemisid') . '</th>';
					echo '<th>' . $this->Label->get('SecurityUser.full_name') . '</th>';
					?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data as $obj) : ?>
				<tr>
					<td><?php echo $this->Html->link($obj['SecurityUser']['openemisid'], array('action' => 'view', $obj['SecurityUser']['id'])) ?></td>
					<td><?php echo $obj['SecurityUser']['full_name'] ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		echo $this->Form->end();
		?>
	</div>
<?php
$this->end();
?>
