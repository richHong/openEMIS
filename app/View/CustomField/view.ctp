<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => 'index'));
	echo $this->FormUtility->link('edit', array('action' => 'edit', $data[$model]['id']));
$this->end();

$this->prepend('portletBody');
$this->end();

$this->start('tabBody');
	$staticFields = array();
	$createModifiedFields = array();
	foreach ($fields as $key => $value) {
		if (in_array($key, array('modified_user_id','modified','created_user_id','created'))) {
			$createModifiedFields[$key] = $value;
		} else {
			$staticFields[$key] = $value;	
		}
	}
	echo $this->element('layout/view', array('fields'=>$staticFields));
	if (array_key_exists($model, $data)) {
		if (array_key_exists('type', $data[$model])) {
			if ($data[$model]['type'] == 4) {
				// drop down.. need to grab the value table
				// probably need to 
				echo $this->element('layout/view_custom_option');
			}
		}
	}
	echo $this->element('layout/view', array('fields'=>$createModifiedFields));


$this->end();
?>
