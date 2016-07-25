<?php
$this->Html->scriptStart(array('inline' => false, 'block' => 'scriptBottom'));
?>
$(function () {
	<?php
	foreach ($timepicker as $id => $attr) {
		$attrStr = array();
		
		foreach ($attr as $key => $value) {
			if ($value === 'true' || $value === 'false') {
				$attrStr[] = $key . ": " . $value;
			} else {
				$attrStr[] = $key . ": '" . $value . "'";
			}
		}
		echo "$('#" . $id . "').timepicker({" . implode(', ', $attrStr) . "});\n";
	}
	?>
});
<?php
$this->Html->scriptEnd();
?>
