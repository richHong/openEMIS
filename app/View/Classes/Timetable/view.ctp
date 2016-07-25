<?php 
echo $this->Html->css('timetable', array('inline' => false));
echo $this->Html->script('timetable', array('inline' => false));
echo $this->Html->script('app.table', array('inline' => false));
?>

<?php
$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $name);
$this->assign('tabHeader', $this->Label->get('general.timetable'));

$this->start('tabActions');
echo $this->FormUtility->link('edit', array('action' => $model, 'edit', $selectedTimetable));
echo $this->FormUtility->link('add', array('action' => $model, 'add'));
$this->end();
$this->prepend('portletBody');
    echo $this->element('../Classes/profile');
$this->end();   

$this->start('tabBody');
    echo $this->element('layout/timetable');
$this->end();
?>
