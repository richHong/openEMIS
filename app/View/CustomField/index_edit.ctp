<?php
echo $this->Html->script('reorder', false);
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	$params = array('action' => 'index', $selectedOption);
	echo $this->FormUtility->link('back', $params);
$this->end();

$this->start('tabBody');
	$formParams = array('controller' => $this->params['controller'], 'action' => 'reorder', $selectedOption);
	if(isset($conditionId)) {
		$formParams = array_merge($formParams, array($conditionId => $selectedSubOption));
	}
	echo $this->Form->create($model, array('id' => 'OptionMoveForm', 'url' => $formParams));
	echo $this->Form->hidden('id', array('class' => 'option-id'));
	echo $this->Form->hidden('move', array('class' => 'option-move'));
	echo $this->Form->end();

?>

<div class="table-responsive">
	<table class="table table-striped table-hover table-bordered table-highlight">
		<thead>
			<tr>
				<th><?php echo $this->Label->get('general.option'); ?></th>
				<th class="cell-order"><?php echo $this->Label->get('general.order'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(!empty($data)) :
				$index = 1;
				foreach($data as $obj) :
			?>
			<tr row-id="<?php echo $obj[$model]['id']; ?>">
				<td><?php echo $this->Html->link($obj[$model]['name'], array('action' => 'view', $selectedOption, $obj[$model]['id'])); ?></td>
				<td class="action">
					<?php
					$size = count($data);
					echo $this->element('layout/reorder', compact('index', 'size'));
					$index++;
					?>
				</td>
			</tr>
			<?php 
				endforeach;
			endif;
			?>
		</tbody>
	</table>
</div>

<?php
$this->end();
?>
