<?php 
	$row = $this->FormUtility->getRowHTML();
	$label = $this->Label->get('CustomField.customOption');
	$value = '<table class="table table-striped table-hover table-bordered table-highlight" style="margin-bottom:2em">
		<thead>
			<tr>
				<th>'. $this->Label->get('CustomField.value') .'</th>
			</tr>
			<tbody>';

				foreach ($customFieldOption as $key => $customValue) {
					$value .= '<tr><td>'.$customValue['value'].'</td></tr>';
				}
						
	$value .=	'
			</tbody>
		</thead>
	</table>';
	if (isset($customFieldOption)) {
		echo sprintf($row, $label, $value);
	} else {
		echo sprintf($row, $label, $this->Label->get('general.noData'));
	}
	
?>