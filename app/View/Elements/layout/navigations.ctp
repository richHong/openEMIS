<ul class="nav nav-tabs">
<?php
$action = !isset($action) ? $this->action : $action;

foreach($navigations as $label => $options) {
	$url = array('controller' => $options['controller'], 'action' => $options['action']);
	$a = $this->Html->link(__($label), $url);
	$selected = false;
	
	if(array_key_exists('selected', $options)) {
		if(is_array($options['selected'])) {
			$selected = in_array($action, $options['selected']);
		} else {
			$selected = preg_match(sprintf('/%s/i', $options['selected']), $action);
		}
	} else {
		$selected = $action == $options['action'];
	}
	
	if($selected && $url['controller'] === $this->params['controller']) {
		echo '<li class="active">' . $a . '</li>';
	} else {
		echo '<li>' . $a . '</li>';
	}
}
?>
</ul>
