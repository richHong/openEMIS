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

App::uses('AppModel', 'Model');

class StudentPassword extends AppModel {
	public $useTable = 'students';
	public $belongsTo = array(
		'SecurityUser',
		'ModifiedUser' => array(
			'className' => 'SecurityUser',
			'fields' => array('first_name', 'last_name'),
			'foreignKey' => 'modified_user_id',
			'type' => 'LEFT'
		),
		'CreatedUser' => array(
			'className' => 'SecurityUser',
			'fields' => array('first_name', 'last_name'),
			'foreignKey' => 'created_user_id',
			'type' => 'LEFT'
		)
	);
	public $actsAs = array(
		'ControllerAction'
	);

	public function __construct() {
		parent::__construct();

		$this->validate = array(
		);
	}
	
	public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb($this->Message->getLabel('general.account'));
	}

	public function index() {
		$studentId = $this->Session->read('Student.id');
		if($this->request->is(array('put', 'post'))) {	
			if($this->SecurityUser->save($this->request->data)) {
				$this->Message->alert('general.edit.success');
			} else {
				$this->Message->alert('general.edit.failed', array('type' => 'error'));
			}
		}

		$securityUserData = $this->find(
			'first', array(
				'recursive' => 0,
				'fields' => array(
					'StudentPassword.id', 'StudentPassword.security_user_id',  'SecurityUser.id', 'SecurityUser.username', 'SecurityUser.password'
				),
				'conditions' => array (
					'StudentPassword.id' => $studentId
				)
			)
		);

		$this->setVar('data', $securityUserData);
		$this->setVar('tabHeader', $this->Message->getLabel('general.account'));
		$this->setVar('model', 'SecurityUser');
	}
}
