<?php
	define('APP_DIR', 'app');
	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', dirname(__FILE__));
	define('WEBROOT_DIR', 'webroot');
	define('WWW_ROOT', ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS);

	$filename = "app/Config/database.php";
	$filename = str_replace(" ", "", $filename);

	if (!file_exists($filename)) {
    	header("Location: installer/");
	} else {
		$file = '.htaccess';
		$current = file_get_contents($file);
		$current .= "
<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteRule    ^$ app/webroot/    [L]
	RewriteRule    (.*) app/webroot/$1 [L]
</IfModule>
		";
		file_put_contents($file, $current);
		
		if (!defined('CAKE_CORE_INCLUDE_PATH')) {
			define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');
		}
		require APP_DIR . DS . WEBROOT_DIR . DS . 'index.php';
	}
?>