<?php

$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $tabHeader);

$this->start('tabActions');
	echo $this->FormUtility->link('back', array('action' => 'view', $this->data[$model]['id']));
$this->end();

$this->prepend('portletBody');
$this->end();

$this->start('tabBody');
	$formOptions = $this->FormUtility->getFormOptions(array('action' => 'edit', $this->data[$model]['id'],$additionalRows));
	echo $this->Form->create($model, $formOptions);
	echo $this->element('layout/edit');
	echo $this->element('layout/edit_custom_option',array('action'=>'edit'));
	echo $this->FormUtility->getFormButtons();
	$this->Form->end();
?>

<script type="text/javascript">
	// will move to jscript object when implementing for guardian and staff
	var moduleName = <?php echo json_encode($moduleName); ?>;
	var isDropdown = <?php echo json_encode($isDropdown); ?>;
	var uniqueFieldId = "#"+moduleName+'CustomFieldIsUnique';

	if (isDropdown) {
		$( "#"+moduleName+"CustomFieldType" ).val(4);
	}
	
	handleTypeToggle();

	$( "#"+moduleName+"CustomFieldType" ).change(function() {
		handleTypeToggle();
	});

	$('form').bind('submit', function() {
        $(this).find(':input').removeAttr('disabled');
    });

	function handleTypeToggle() {
		$( "#customFieldOptionValues" ).hide();
		switch($( "#"+moduleName+"CustomFieldType" ).val()) {
			case '1': case '3':
				$( uniqueFieldId ).prop('disabled', false);
				break;
			case '2':
				$( uniqueFieldId ).val( '0' );
				$( uniqueFieldId ).prop('disabled', 'disabled');
				break;
			case '4': 
				$( uniqueFieldId ).val( '0' );
				$( uniqueFieldId ).prop('disabled', 'disabled');
				$( "#customFieldOptionValues" ).show();
				break;

		}
	}
</script>

<?php
$this->end();
?>