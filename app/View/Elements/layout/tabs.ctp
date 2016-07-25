<ul class="nav nav-tabs">
<?php
$action = $this->action;

foreach($tabs as $label => $attr) {
	$url = $attr['url'];
	$currentAcoName = '';
	if (array_key_exists('acoName', $attr)) {
		// this will override the normal behavior
		$currentAcoName = $attr['acoName'];
	} else {
		$token = strtok($attr['url']['action'], "/");
		$currentAcoName = $token;
	}

	if ($_access->hasAco($currentAcoName)) {
		if (!$_access->check($this->viewVars['_securityTypeName'], $currentAcoName, 'read')) {
			continue;
		}
	}
	
	// pr($_access->check($_securityUserType, $alias, 'read'));
	// echo 'asd';

	$url['plugin'] = array_key_exists('plugin', $attr) ? $attr['plugin'] : false;
	$a = $this->Html->link($label, $url);
	$selected = false;
	
	if(array_key_exists('selected', $attr)) {
		if(is_array($attr['selected'])) {
			$selected = in_array($action, $attr['selected']);
		} else {
			$selected = preg_match(sprintf('/%s/i', $attr['selected']), $action);
		}
	} else {
		$selected = $action == $url['action'];
	}
	
	if($selected && $url['controller'] === $this->params['controller']) {
		echo '<li class="active">' . $a . '</li>';
	} else {
		echo '<li>' . $a . '</li>';
	}
}
?>
</ul>
