<?php
/*
OpenEMIS School
Open School Management Information System

This program is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 3 of the License, or any later version. This program is distributed in the hope 
that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should 
have received a copy of the GNU General Public License along with this program.  If not, see 
<http://www.gnu.org/licenses/>.  For more information please email contact@openemis.org.
*/

// setting up the web root and server root
$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$webRoot  = str_replace(array($docRoot, 'config.php'), '', $thisFile);
$srvRoot  = str_replace('config.php', '', $thisFile);
$app = getRoot();
$configTemplate = "<?php
class DATABASE_CONFIG {
	public \$default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => {host},
		'login' => {user},
		'password' => {pass},
		'database' => {database},
		'prefix' => '',
		'encoding' => 'utf8',
	);
}
?>
";
define('WEBROOT', 'http://'.$_SERVER['HTTP_HOST'].$webRoot);
define('CONFIG_DIR', $docRoot . $app . '/app/Config/');
define('CONFIG_FILE', $docRoot . $app . '/app/Config/database.php');
define('INSTALL_FILE', $docRoot . $app . '/app/Config/install');
define('CONFIG_TEMPLATE', $configTemplate);
define('INSTALL_SQL', $docRoot . $app . '/app/Sql/install.sql');
define('ABSPATH', $srvRoot);

function pr($a) {
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}
function getHostURL() {
	return 'http://'.$_SERVER['HTTP_HOST'];
}
function getRoot() {
	$tmp = explode('/', $_SERVER['SCRIPT_NAME']);
	$tmp = array_reverse($tmp);
	$webroot_array = array();
	$installWordFound = false;
	foreach($tmp as $t) {
		if ($t == 'installer' && !$installWordFound) {
			$installWordFound = true; 
			continue;
		}
		if ($installWordFound && $t!='') {
			array_push($webroot_array, $t);
		}
	}
	$webroot_array = array_reverse($webroot_array);
	$webroot = implode('/', $webroot_array);
	if ($webroot == '') {
		return $webroot;
	} else {
		return '/'.$webroot;
	}
}

function getSalt() {
	$salt = "openemis_school_salt";
	return $salt;
}

function password($pass) { // following CakePHP hash method
	return sha1(getSalt().$pass);
}
?>