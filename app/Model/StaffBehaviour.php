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

class StaffBehaviour extends AppModel {
	public $belongsTo = array(
		'Staff',
		'BehaviourCategory',
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
		'DatePicker' => array('date_of_behaviour'),
		'TimePicker' => array('time_of_behaviour'), // for fixing time format from timepicker
		'Export' => array('module' => 'Staff')
	);



    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'title' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('title')
                )
            ),
            'date_of_behaviour' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('date')
                )
            ),
            'description' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('description')
                )
            ),
            'action' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('action')
                )
            ),
            'behaviour_category_id' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('behaviour_category_id')
                )
            )
        );
    }
	
	public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb($this->Message->getLabel('StaffBehaviour.name'));
		$this->fields['staff_id']['type'] = 'hidden';
		$this->fields['staff_id']['value'] = $this->Session->read('Staff.id');
		$this->fields['behaviour_category_id']['type'] = 'select';
		$this->fields['behaviour_category_id']['options'] = $this->BehaviourCategory->getOptions();
		$this->setFieldOrder('behaviour_category_id', 1);
		$this->setVar('tabHeader', $this->Message->getLabel('StaffBehaviour.name'));
	}

	public function index() {
		$data = $this->getListData();
		$this->setVar('data', $data);
	}
	
	public function view($id=0) {
		parent::view($id);
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
		return $this->getFieldNamesFromFields($this->fields);
	}

	public function reportGetData() {
		return $this->handleOptionsInData($this->fields,$this->getListData());
	}

	public function getListData($options=array()) {
		$staffId = $this->Session->read('Staff.id');
		$this->recursive = 0;
		$data = $this->findAllByStaffId($staffId);
		return $data;
	}
}
