<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $header);

$this->start('tabActions');
	$params = array('action' => 'add', $selectedOption);
	if(isset($conditionId)) {
		$params = array_merge($params, array($conditionId => $selectedSubOption));
	}
	echo $this->FormUtility->link('add', $params);
	
	if(count($data) > 1) {
		$params = array('action' => 'indexEdit', $selectedOption);
		if(isset($conditionId)) {
			$params = array_merge($params, array($conditionId => $selectedSubOption));
		}
		echo $this->FormUtility->link('reorder', $params);
	}
$this->end();

$this->start('tabBody');
	
	echo $this->element('../FieldOptions/controls');
	$hasDefault = false;
	if(!empty($data)) {
		$first = current($data);
		$hasDefault = isset($first[$model]['default']);
	}
	$tableHeaders = array();
	if($hasDefault) {
		$tableHeaders[] = array($this->Label->get('general.default') => array('class' => 'cell-default'));
	}
	$tableHeaders[] = array($this->Label->get('general.option') => array('class' => 'cell-option'));
	
	foreach($fields as $key => $value) {
		if(isset($value['visible']['index']) && $value['visible']['index']) {
			$labelKey = isset($value['labelKey']) ? $value['labelKey'] : $model;
			$tableHeaders[] = $this->Label->get($labelKey . '.' . $key);
		}
	}
	$tableHeaders[] = array($this->Label->get('general.status') => array('class' => 'cell-status'));
	
	$tableData = array();
	foreach($data as $obj) {
		$name = isset($obj[$model]['name']) ? $obj[$model]['name'] : $obj[$model]['value'];
		$row = array();
		$visible = $obj[$model]['visible'];
		$linkParams = array('action' => 'view', $selectedOption, $obj[$model]['id']);
		if(isset($conditionId)) {
			$linkParams = array_merge($linkParams, array($conditionId => $selectedSubOption));
		}
		
		if($hasDefault) {
			$default = '';
			if ($obj[$model]['default']==1) {
				$default = '<span class="green">&#10003;</span>';
			}
			$row[] = array($default, array('class' => 'center'));
		}
		$row[] = $this->Html->link($name, $linkParams);
		
		foreach($fields as $key => $value) {
			if(isset($value['visible']['index']) && $value['visible']['index']) {
				if($value['type'] != 'select') {
					$row[] = $obj[$model][$key];
				} else {
					$row[] = $value['options'][$obj[$model][$key]];
				}
			}
		}
		
		$row[] = array($this->FormUtility->getStatus($visible), array('class' => 'center'));
		$tableData[] = $row;
	}
?>
	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead><?php echo $this->Html->tableHeaders($tableHeaders); ?></thead>
			<tbody><?php echo $this->Html->tableCells($tableData); ?></tbody>
		</table>
	</div>
	<?php } ?>

<?php
$this->end();
?>
