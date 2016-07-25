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

class PreferencesController extends AppController {
	public function initialize() {
		$this->Components->disable('Access');
	}
	
	public function beforeFilter() {
		parent::beforeFilter();
	}
	
	public function index() {
		if ($this->Session->check('Security.accessViewType')) {
			$accessViewType = $this->Session->read('Security.accessViewType');
		}

		switch($accessViewType) {
			case 1: 
				$redirectArray = (array('controller' => 'Administrator/view/'.$this->Auth->user('id')));
				break;
			case 2: 
				$redirectArray = (array('controller' => 'Staff/view'));
				break;
			case 3: 
				$redirectArray = (array('controller' => 'Students/view'));
				break;
			case 4: 
				$redirectArray = (array('controller' => 'Guardians/view'));
				break;
			default:
				$redirectArray = (array('controller' => 'Dashboard'));
				break;
		}
		$this->redirect($redirectArray);
	}
}
