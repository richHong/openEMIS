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

class StudentAttachment extends AppModel {
	public $belongsTo = array(
		'Student',
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
		'FileUpload'
	);

	public $accessMapping = array(
		'attachment_delete' => 'delete',
		'download' => 'read'
	);

	public function __construct() {
		parent::__construct();

		$this->validate['name'] = array(
			 'ruleRequired' => array(
				 'rule' => 'notEmpty',
				 'required' => true,
				 'message' => $this->getErrorMessage('name')
			 )
		 );
	}
	
	public function beforeAction() {
		parent::beforeAction();

		$this->Navigation->addCrumb($this->Message->getLabel('general.attachments'));
		$this->fields['student_id']['type'] = 'hidden';
		$this->fields['student_id']['value'] = $this->Session->read('Student.id');
		$this->fields['description']['type'] = 'text';
		$this->fields['file_content']['type'] = 'file_upload';

		$this->setVar('tabHeader', $this->Message->getLabel('general.attachments'));
	}

	public function index() {
		$studentId = $this->Session->read('Student.id');
		$this->recursive = 0;
		$data = $this->findAllByStudentId($studentId);
		if(empty($data)) $this->Message->alert('general.view.noRecords');
		$this->setVar('data', $data);
	}
	
	public function view($id=0) {
		$this->fields['file_name']['type'] = 'download';
		$this->fields['file_name']['attr'] = array('url' => array('action' => get_class($this), 'download', $id));
		$this->fields['file_content']['visible'] = false;
		parent::view($id);
		$this->render = 'view';
	}

	public function edit($id=0) {
		$this->fields['file_name']['visible'] = false;
		$this->fields['file_content']['visible'] = false;
		parent::edit($id);
		
		$this->render = 'edit';
	}
	
	public function add() {
		$this->fields['file_name']['visible'] = false;
		
		if ($this->request->is(array('post', 'put'))) {
			if ($this->save($this->request->data)) {
				$this->Message->alert('general.add.success');
				return $this->redirect(array('action' => get_class($this)));
			} else {
				$this->Message->alert('general.add.failed');
			}
		}
	}
	
	public function attachment_delete($params){
		$this->render = false;
		$id = !empty($params)?$params: NULL;
		
		if(!empty($id)){
			$conditions = array(
				'id' => $id
			);
			if ($this->hasAny($conditions)){
				$this->delete($id);
				$this->controller->Message->alert('general.delete.success');
				return $this->controller->redirect(array('action' => $this->name));
				$this->controller->set('id', $id);
			}
			else{
				$this->controller->Message->alert('general.view.notExists', array('type' => 'warn'));
				return $this->controller->redirect(array('action' => $this->name));
			}
		}
		else{
			$this->controller->Message->alert('general.view.notExists', array('type' => 'warn'));
			return $this->controller->redirect(array('action' => $this->name));
		}
	}
	
}
