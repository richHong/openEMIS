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

class NavigationComponent extends Component {
	private $controller;
	public $breadcrumbs;
	
	public function initialize(Controller $controller) {
		$this->controller =& $controller;
	}
	
	//called after Controller::beforeFilter()
	public function startup(Controller $controller) {}
	
	//called after Controller::beforeRender()
	public function beforeRender(Controller $controller) {
		$this->controller->set('_breadcrumbs', $this->breadcrumbs);
	}
	
	//called after Controller::render()
	public function shutdown(Controller $controller) {}
	
	//called before Controller::redirect()
	public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {}
	
	public function addCrumb($title, $options=array()) {		
		$item = array(
			'title' => __($title),
			'link' => array('url' => $options),
			'selected' => sizeof($options)==0
		);
		$this->breadcrumbs[] = $item;
	}

	public function resetCrumbs() {
		$this->breadcrumbs = array();
	}
}
?>