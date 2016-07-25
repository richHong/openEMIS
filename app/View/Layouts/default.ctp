<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link		  http://cakephp.org CakePHP(tm) Project
 * @package	   app.View.Layouts
 * @since		 CakePHP(tm) v 0.10.0.1076
 * @license	   http://www.opensource.org/licenses/mit-license.php MIT License
 */

$description = __d('openemis_school', 'OpenEMIS School');
?>
<!DOCTYPE html>
<html lang="<?php echo $lang_locale; ?>" dir="<?php echo $lang_dir; ?>" class="<?php echo $lang_dir == 'rtl' ? 'rtl' : '' ?>">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo $description ?></title>
	
	<?php
	echo $this->Html->meta('favicon', $this->webroot . 'favicon.ico?v=2', array('type' => 'icon'));
	echo $this->Html->css('default/font-awesome.min');
	echo $this->Html->css('default/googleapis/font');
	echo $this->Html->css('default/bootstrap.min', array('media' => 'screen'));

	echo $this->Html->css('default/App');
	echo $this->Html->css('kordit-fonts/style');
	echo $this->Html->css('styles');
	echo $this->Html->css('layout');
	echo $this->Html->css('table');
	echo $this->Html->css('../js/plugins/jquery-ui/' . jQuery_UI_Version . '/jquery-ui');
	
	if ($lang_dir == 'rtl') {
		echo $this->Html->css('rtl');
	}
	
	echo $this->Html->script('default/jquery-1.9.1.min');
	//echo $this->Html->script('plugins/jquery-ui/' . jQuery_UI_Version . '/jquery-ui');
	
	// // FROM REFERENCE CODE working
	// echo '<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>';
	// echo '<script src="//code.jquery.com/jquery-1.10.2.js"></script>';
  	// echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">';
	
	echo $this->Html->script('default/bootstrap.min');
	echo $this->Html->script('default/App');
	echo $this->Html->script('css_browser_selector');
	echo $this->Html->script('app.table');
	echo $this->Html->script('app.form');

	echo sprintf('<script type="text/javascript" src="%s%s"></script>', $this->webroot, 'Config/getJSConfig');
	
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
</head>

<body>
	<?php echo $this->element('layout/header'); ?>
	<?php echo $this->element('layout/left_navigation'); ?>
	<?php echo $this->fetch('content'); ?>
	<?php echo $this->fetch('modalBody'); ?>
	<?php echo $this->element('layout/footer'); ?>
	
	<?php echo $this->element('debug/sql'); ?>
	<?php echo $this->fetch('scriptBottom'); ?>
</body>

</html>
