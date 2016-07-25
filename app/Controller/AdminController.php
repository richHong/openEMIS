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

class AdminController extends AppController {
	public $uses = array('InstitutionSite');
	public $components = array('Paginator', 'FileUploader');
	
	public $modules = array(
		//'report_card_template' => 'ReportCardTemplate'
		'ConfigItem',
		'ReportCardTemplate'
	);

	public $acoName = 'AdminProfile';

	public $accessMapping = array(
		'aclTest'=>'update',
		'aclRun'=>'update',
		// 'patch121'=>'execute'
	);	

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Navigation->addCrumb($this->Message->getLabel('admin.title'), array('controller' => $this->params['controller'], 'action' => 'view'));
		$this->set('header', $this->Message->getLabel('admin.title'));
		$this->set('tabElement', '../Admin/tabs');
		$this->set('contentHeader', $this->Message->getLabel('admin.title'));
	}

	public function index() {
		return $this->redirect(array('action' => 'view'));
	}
	
	public function view() {
		$this->Navigation->addCrumb($this->Message->getLabel('general.general'));
		$this->InstitutionSite->recursive = 0;
		$data = $this->InstitutionSite->find('first');
		$this->Session->write('InstitutionSite.data', $data['InstitutionSite']);
		$fields = $this->InstitutionSite->getFields();
		$model = 'InstitutionSite';
		$this->set(compact('data', 'fields', 'model'));
	}

	public function edit() {
		$this->Navigation->addCrumb($this->Message->getLabel('general.general'));
		$fields = $this->InstitutionSite->getFields();
		
		if ($this->request->is(array('post', 'put'))) {
			// file upload part...
			$this->request->data['InstitutionSite']['file'] = $this->request->data['InstitutionSite']['photo_content'];
			unset($this->request->data['InstitutionSite']['photo_content']);
			if ($this->InstitutionSite->save($this->request->data)) {
				$this->FileUploader->fileSizeLimit = 4 * 1024 * 1024;
				$this->FileUploader->fileModel = 'InstitutionSite';
				$this->FileUploader->data = $this->request->data;
				$this->FileUploader->allowNoFileUpload = true;
				$this->FileUploader->uploadFile();

				if ($this->FileUploader->success) {
					$this->Message->alert('general.edit.success');
					return $this->redirect(array('action' => 'view', '1'));
				}// file upload error handled within component
				$this->Message->alert('general.edit.success');
				return $this->redirect(array('action' => 'view'));
			} else {
				$this->Message->alert('general.edit.failed');
			}
		} else {
			$this->InstitutionSite->recursive = 0;
			$this->request->data = $this->InstitutionSite->find('first');
		}
		$model = 'InstitutionSite';
		$this->set(compact('fields', 'model'));
	}

	public function aclTest($securityType = 0) {
		$this->autoRender = false;
		$this->render = false;
		switch($securityType) {
			case 1:
				pr('Admin Access');
				pr($this->listPermissions('Admin'));
				break;
			case 2:
				pr('Staff Access');
				pr($this->listPermissions('Staff'));
				break;
			case 3:
				pr('Student Access');
				pr($this->listPermissions('Student'));
				break;
			case 4:
				pr('Guardian Access');
				pr($this->listPermissions('Guardian'));
				break;
			case 5:
				pr('Teacher Access');
				pr($this->listPermissions('Teacher'));
				break;
		}
	}


	public function aclRun() {		
		// used by programmers to populate access tables and to check... eventually will become the custom access control for users
		$this->Access->setup();

		$this->autoRender = false;
		$this->render = false;

		$this->Access->allow('Admin', 'All', '*');  

		$this->Access->allow('Student', 'Dashboard', array('read')); 
		$this->Access->allow('Student', 'Events', array('read')); 
		$this->Access->allow('Student', 'Students', array('read'));
		$this->Access->deny('Student', 'StudentBehaviour', '*');
		$this->Access->deny('Student', 'StudentAttachment', '*');
		$this->Access->allow('Student', 'StudentPassword', '*');

		$this->Access->allow('Staff', 'Dashboard', array('read')); 
		$this->Access->allow('Staff', 'Events', array('read')); 
		$this->Access->allow('Staff', 'Students', array('read')); 
		$this->Access->allow('Staff', 'StudentResult', '*'); 
		$this->Access->allow('Staff', 'StudentAttendanceDay', '*'); 
		$this->Access->allow('Staff', 'StudentAttendanceLesson', '*'); 
		$this->Access->allow('Staff', 'StudentBehaviour', '*');

		$this->Access->allow('Staff', 'Staff', array('read'));
		$this->Access->allow('Staff', 'StaffContact', '*');
		$this->Access->deny('Staff', 'StaffBehaviour', '*');
		$this->Access->deny('Staff', 'StudentPassword', '*');
		$this->Access->deny('Staff', 'GuardianPassword', '*');
		$this->Access->allow('Staff', 'StaffPassword', '*');

		$this->Access->allow('Staff', 'Classes', array('read'));
		$this->Access->allow('Staff', 'ClassAssignment', '*');
		$this->Access->allow('Staff', 'ClassResult', '*');
		$this->Access->allow('Staff', 'ClassAttendanceDay', '*');
		$this->Access->allow('Staff', 'ClassAttendanceLesson', '*');
		$this->Access->allow('Staff', 'ClassAttachment', '*');

		$this->Access->allow('Guardian', 'Dashboard', array('read')); 
		$this->Access->allow('Guardian', 'Events', array('read'));
		$this->Access->allow('Guardian', 'Guardians', array('read'));
		$this->Access->allow('Guardian', 'Students', array('read'));
		$this->Access->allow('Guardian', 'GuardianPassword', '*');
		$this->Access->deny('Guardian', 'StudentPassword', '*');
	}

	public function listPermissions($thisAro) {
		$dataArray = array();
		$Acos = ClassRegistry::init('Aco');
		$allAco = $Acos->find(
			'list',
			array(
				'recursive' => 0,
				'fields' => array('Aco.alias')
			)
		);

		$tArray = array();
		foreach($allAco as $key => $aco) {
			$tArray = array();
			$tArray['checking'] = $thisAro . ' has permission? ' . $aco;
			$tArray['result'] = $this->toArrayPermission($thisAro,$aco);
			array_push($dataArray, $tArray);
		}

		return $dataArray;
	}

	public function toArrayPermission($aro, $aco) {
		$tArray = array();
		$tArray['read'] = ($this->Access->check($aro, $aco, 'read')) ? 'true' : "false";
		$tArray['update'] = ($this->Access->check($aro, $aco, 'update')) ? 'true' : "false";
		$tArray['create'] = ($this->Access->check($aro, $aco, 'create')) ? 'true' : "false";
		$tArray['delete'] = ($this->Access->check($aro, $aco, 'delete')) ? 'true' : "false";
		$tArray['execute'] = ($this->Access->check($aro, $aco, 'execute')) ? 'true' : "false";
		return $tArray;
	}
}
