<?php 
if(strpos($_SERVER['SERVER_NAME'], 'dev') !== false 
|| strpos($_SERVER['SERVER_NAME'], 'tst') !== false 
|| strpos($_SERVER['SERVER_NAME'], 'localhost') !== false) {
?>
	
<style type="text/css">
.sql-dump { clear: both; overflow: hidden; background-color: #FFFFFF; }
.sql-dump table { border: 1px solid #CCC; margin: 10px; }
.sql-dump th, .sql-dump td { border: 1px solid #CCC; padding: 5px 8px; }
</style>

<div class="sql-dump"><?php echo $sql_dump = $this->element('sql_dump'); ?></div>
<?php } ?>