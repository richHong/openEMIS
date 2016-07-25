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

class StaffContact extends AppModel {
	public $useTable = 'contacts';
	public $belongsTo = array(
		'Staff' => array(
			'className' => 'SecurityUser',
			'fields' => array('first_name', 'last_name'),
			'foreignKey' => 'modified_user_id',
			'type' => 'LEFT'
		),
		'ContactType',
		// 'ContactType' => array(
		// 	'className' => 'FieldOptionValue',
		// 	'foreignKey' => 'contact_type_id'
		// ),
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
		'ControllerAction',
		'Contact',
		'Export' => array('module' => 'Staff')
	);

    public function __construct() {
        parent::__construct();

        $this->validate = array(
     		// also handled in contact behavior beforeValidate
        );
    }
	
	public function beforeAction() {
		parent::beforeAction();
		
		$this->Navigation->addCrumb($this->Message->getLabel('general.contacts'));
		$this->fields['security_user_id']['type'] = 'hidden';
		$this->fields['security_user_id']['value'] = $this->Session->read('Staff.data.SecurityUser.id');
		$this->fields['contact_type_id']['type'] = 'select';
		$this->fields['contact_type_id']['options'] = $this->ContactType->getOptions();
		$this->fields['main']['type'] = 'select';
		$this->fields['main']['options'] = $this->getYesnoOptions();
		$this->setFieldOrder('contact_type_id', 1);

		$this->fields['contact_type_id']['labelKey'] = 'Contact';
		$this->fields['name']['labelKey'] = 'Contact';
		$this->fields['value']['labelKey'] = 'Contact';
		$this->fields['main']['labelKey'] = 'Contact';

		$this->setVar('tabHeader', $this->Message->getLabel('Contact.title'));
	}

	public function index() {
		$data = $this->getListData();

		$id = $this->Session->read('Staff.data.SecurityUser.id');
		$mainPhone = $this->getPreferredPhone($id);
		$mainEmail = $this->getPreferredEmail($id);
		$this->Session->write('Staff.data.mainPhone', $mainPhone);
		$this->Session->write('Staff.data.mainEmail', $mainEmail);
		$this->set('mainPhone', $mainPhone);
		$this->set('mainEmail', $mainEmail);
		if(empty($data)) $this->Message->alert('general.view.noRecords');
		$this->setVar(compact('data'));
	}
	
	public function view($id=0) {
		parent::view($id);

		$id = $this->Session->read('Staff.data.SecurityUser.id');
		$mainPhone = $this->getPreferredPhone($id);
		$mainEmail = $this->getPreferredEmail($id);
		$this->Session->write('Staff.data.mainPhone', $mainPhone);
		$this->Session->write('Staff.data.mainEmail', $mainEmail);
		$this->set('mainPhone', $mainPhone);
		$this->set('mainEmail', $mainEmail);

		$this->render = 'view';
	}

	public function edit($id=0) {
		parent::edit($id);
		$this->render = 'edit';
	}
	
	public function add() {
		parent::add();
		$this->render = 'edit';
	}

	public function reportGetFieldNames() {
		$fields = $this->fields;
		unset($fields['security_user_id']);
		return $this->getFieldNamesFromFields($fields);
	}

	public function reportGetData() {
		return $this->handleOptionsInData($this->fields,$this->getListData());
	}

	public function getListData($options=array()) {
		$id = $this->Session->read('Staff.data.SecurityUser.id');
		$data = $this->find('all', array(
			'recursive' => 0, 
			'conditions' => array($this->alias.'.security_user_id' => $id),
			'order' => array($this->alias.'.main desc', $this->alias.'.contact_type_id asc')
		));
		
		return $data;
	}
}
