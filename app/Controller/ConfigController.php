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

App::uses('AppController', 'Controller');

class ConfigController extends AppController {
	public $uses = array(
		'ConfigItem'
	);

	public $accessMapping = array(
		'getJSConfig' => 'none',
		'getRootURL' => 'none'
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('getJSConfig');
	}
	
	public function getJSConfig() {
		$this->autoLayout = false;
		$this->RequestHandler->respondAs('text/javascript');
		
		$protocol = ($_SERVER['SERVER_PORT'] == '443'?'https://':'http://');
		$host = $_SERVER['HTTP_HOST'];
		
		$url = $protocol . $host . $this->webroot;
		
		$this->set('rootURL', $url);
		$this->render('config');
	}
}