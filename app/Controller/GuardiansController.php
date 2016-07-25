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

define('ACCESS_VIEW_TYPE', 4);

class GuardiansController extends AppController {
	public $uses = array(
		'StudentGuardian',
		'SecurityUser',
		'SecurityUserType',
		'GuardianContact',
		'Country',
		'GuardianStudent',
		'GuardianCustomField',
		'GuardianCustomValue'
	);
	
	public $helpers = array('Paginator', 'Utility','CustomField');
	public $components = array(
		'Paginator',
		'Schedule',
		'FileUploader',
		'Search'
	);
	
	public $modules = array(
        'GuardianContact',
        'GuardianPassword',
        'GuardianIdentity',
        'GuardianStudent'
	);

	public $acoName = 'GuardianProfile';
	public $contentHeader;
	public $accessViewType;

	public $accessMapping = array(
		'search' => 'read',
		'listing' => 'read',
		'download' => 'execute'
	);

	public function beforeFilter() {
		parent::beforeFilter();

		if ($this->Session->check('Security.accessViewType')) $this->accessViewType = $this->Session->read('Security.accessViewType');
		$this->contentHeader = $this->Message->getLabel('general.guardians');
		if (isset($this->accessViewType) && $this->accessViewType == ACCESS_VIEW_TYPE) {
			$this->contentHeader = $this->Message->getLabel('user.myProfile') . ' (' . $this->Session->read('Security.accessViewTypeName') . ')';
		}
		$this->set('contentHeader', $this->contentHeader);

		$actions = array('add', 'index', 'view', 'search', 'edit', 'listing','download');
		
		if(!$this->request->is('ajax')) {
			if (isset($this->accessViewType) && $this->accessViewType != ACCESS_VIEW_TYPE) {
				$this->Navigation->addCrumb($this->Message->getLabel('guardian.title'), array('controller' => $this->params['controller'], 'action' => 'index'));
			}
			if(!in_array($this->action, $actions)) { // if the action is not one of the actions not requiring the id
				if(!$this->Session->check('StudentGuardian.id')) { // if id not exists in session
					$this->Message->alert('general.view.notExists');
					return $this->redirect(array('action' => 'index'));
				} else {
					$id = $this->Session->read('StudentGuardian.id');
					if(!$this->SecurityUser->exists($id)) {
						$this->Message->alert('general.view.notExists', array('type' => 'warn'));
						return $this->redirect(array('action' => 'index'));
					} else {
						$data = array();
						$data['SecurityUser'] = array();
						$data['SecurityUser']['first_name'] = trim($this->Session->read('StudentGuardian.data.SecurityUser.first_name'));
						$data['SecurityUser']['middle_name'] = trim($this->Session->read('StudentGuardian.data.SecurityUser.middle_name'));
						$data['SecurityUser']['last_name'] = trim($this->Session->read('StudentGuardian.data.SecurityUser.last_name'));
						$openemisid = trim($this->Session->read('StudentGuardian.data.SecurityUser.openemisid'));
						$name = $this->Message->getFullName($data) . ' (' . $openemisid . ')';
						$this->Session->write('Report.Guardian.reportHeader', array(
							$this->Message->getLabel('general.name')=>$this->Message->getFullName($data))
						);
						$this->set('name', $name);

						if ($this->action !== 'view' && $this->action !== 'edit') {
							if (isset($this->accessViewType) && $this->accessViewType != ACCESS_VIEW_TYPE) {
								$this->Navigation->addCrumb($name, array('controller' => $this->params['controller'], 'action' => 'view', $id));
							} else {
								$this->Navigation->addCrumb($this->contentHeader, array('controller' => $this->params['controller'], 'action' => 'view', $id));
							}
						}
					}
				}
			}
			// else if($this->action == 'view') { // action 'view' will initialise the id in session
			// 	if(!$this->Session->check('StudentGuardian.id')) { // if id not exists in session
			// 		if(isset($this->params['pass'][0])) {
			// 			$id = $this->params['pass'][0];
			// 			if(!$this->SecurityUser->exists($id)) { // if id not exists in database
			// 				$this->Message->alert('general.view.notExists', array('type' => 'warn'));
			// 				$this->redirect(array('action' => 'index'));
			// 			} else {
			// 				$this->Session->write('StudentGuardian.id', $id);
			// 			}
			// 		} else { // if url does not contain id
			// 			$this->Message->alert('general.view.notExists', array('type' => 'warn'));
			// 			return $this->redirect(array('action' => 'index'));
			// 		}
			// 	}
			// 	else{//check whether the session is same as the param id
			// 		$id = $this->Session->read('StudentGuardian.id');
			// 		if(isset($this->params['pass'][0])) {
			// 			if($id != $this->params['pass'][0]){
			// 				$id = $this->params['pass'][0];
			// 				if(!$this->SecurityUser->exists($id)) { // if id not exists in database
			// 					$this->Message->alert('general.view.notExists', array('type' => 'warn'));	
			// 					$this->redirect(array('action' => 'index'));
			// 				} else {
			// 					$this->Session->write('StudentGuardian.id', $id);
			// 				}
			// 			}
			// 		}
			// 	}
			// }
		}
		$this->set('title', $this->Message->getLabel('guardian.title'));
		$this->set('model', 'SecurityUser');
		$this->set('tabElement', '../Guardians/tabs');
	}

	public function index() {
		$this->Navigation->addCrumb($this->Message->getLabel('general.search'));
	}
	
	public function listing() {
		$this->Search->clearSearchParams('StudentGuardian');
		return $this->redirect(array('action' => 'search'));
	}

	public function search() {
		if ($this->accessViewType != ACCESS_VIEW_TYPE) {
			$this->Navigation->addCrumb($this->Message->getLabel('general.searchResults'));
			$data = $this->Search->search('StudentGuardian', array('sortDefault' => $this->Session->read('name_display_format'), 'usingModel' => 'SecurityUser'));
			if(empty($data)) $this->Message->alert('general.view.noRecords');
			$this->set('data', $data);
		}
	}

	public function export() {
		// $currentModel = 'SecurityUser';
		// $this->render = false;
		// $data = array();

		// if ($this->Session->check($currentModel.'.search.conditions')) {
		// 	$sessionConditions = $this->Session->read($currentModel.'.search.conditions');
		// 	$conditions = $this->$currentModel->paginateConditions($sessionConditions);
		// }
		
		// $order = array();
		// if ($this->Session->read($currentModel.'.search.sort.processedOrder')) {
		// 	$order = $this->Session->read($currentModel.'.search.sort.processedOrder');
		// }
		
		// $data['data'] = $this->$currentModel->find(
		// 	'all',
		// 	array(
		// 		'recursive' => 0,
		// 		'conditions' => $conditions,
		// 		'order' => $order
		// 	)
		// );

		// pr($data);
	}

	private function getFields() {
		$fields = $this->SecurityUser->getFieldsForGuardian();
		$fields['username']['visible'] = false;
		$fields['password']['visible'] = false;
		$fields['photo_name']['visible'] = false;
		$fields['photo_content']['visible'] = false;
		$fields['super_admin']['visible'] = false;
		$fields['status']['visible'] = false;
		$fields['last_login']['visible'] = false;

		return $fields;
	}

	public function download() {
		$fileName = $this->Message->getLabel('general.guardians');
		$nowTime = time();
		$nowTime = date('Y-m-d H:i:s');
		$fileName .= ' - '.$nowTime;

		$rawFields = $this->getFields();
		unset($rawFields['username']);
		unset($rawFields['password']);
		unset($rawFields['photo_name']);
		unset($rawFields['photo_content']);
		unset($rawFields['super_admin']);
		unset($rawFields['status']);
		$fieldNames = $this->Export->getFieldNamesFromFields($rawFields);
		$fieldSQL = $keys = array_keys($fieldNames);
		$data = $this->SecurityUser->find(
			'all',
			array(
				'fields' => $fieldSQL,
				'recursive' => 0
			)
		);
		$data = $this->Export->handleOptionsInData($rawFields,$data);
		$this->Export->exportCSV($fieldNames, $data, $fileName);
		exit;
	}

	public function view($id=0) {
		if ($this->accessViewType != ACCESS_VIEW_TYPE) {
			if ($id == 0) {	
				if ($this->Session->check('StudentGuardian.id')) {
					$id = $this->Session->read('StudentGuardian.id');
				} else {
					$this->Message->alert('general.view.notExists');
					return $this->redirect(array('action' => 'index'));
				}
			}
		} else {
			// is guardian
			$id = AuthComponent::user('id');
		}

		if ($this->SecurityUser->exists($id)) {
			$fields = $this->getFields();
			
			$this->SecurityUser->recursive = 0;
			$data = $this->SecurityUser->getGuardianData($id);
			$securityUserID = $data['SecurityUser']['id'];

			$openemisid = $data['SecurityUser']['openemisid'];
			$this->Session->write('StudentGuardian.data', $data);

			$mainPhone = $this->GuardianContact->getPreferredPhone($securityUserID);
			$mainEmail = $this->GuardianContact->getPreferredEmail($securityUserID);

			$this->Session->write('StudentGuardian.id', $id);
			$this->Session->write('StudentGuardian.data.mainPhone', $mainPhone);
			$this->Session->write('StudentGuardian.data.mainEmail', $mainEmail);

			$name = $this->Message->getFullName($data) . ' (' . $openemisid . ')';

			$this->set('name', $name);
			$this->set('data', $data);
			$this->set('fields', $fields);
			$this->set('mainPhone', $mainPhone);
			$this->set('mainEmail', $mainEmail);

			if ($this->accessViewType != ACCESS_VIEW_TYPE) {
				$this->Navigation->addCrumb($name);
			} else {
				$this->Navigation->addCrumb($this->contentHeader);
			}
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function add() {
		$fields = $this->getFields();
		if (empty($this->request->data)) {
			$default = $this->Country->getDefaultValue();
			$fields['country_id']['default'] = $default;
		}
		if($this->request->is('post')) {
			$this->SecurityUser->create();
			unset($this->request->data['SecurityUser']['id']);
			$this->request->data['SecurityUser']['date_of_birth'] = date('Y-m-d', strtotime($this->request->data['SecurityUser']['date_of_birth']));
			if ($this->SecurityUser->save($this->request->data)) {
				$this->Message->alert('general.add.success');
				$id = $this->SecurityUser->getLastInsertId();
				$this->SecurityUserType->create();
				$type = $this->request->data;
				$type['SecurityUserType']['security_user_id'] = $id;
				$type['SecurityUserType']['type'] = '4';
				$this->SecurityUserType->save($type);
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Message->alert('general.add.failed', array('type' => 'error'));
			}
		}
		$this->Navigation->addCrumb($this->Message->getLabel('general.add'));
		$fields['openemisid']['default'] = $this->StudentGuardian->getUniqueID();
		$this->set('fields', $fields);
	}

	public function edit($id=0) {
		if ($this->SecurityUser->exists($id)) {
			$fields = $this->getFields();
			$this->SecurityUser->recursive = 0;
			$data = $this->SecurityUser->getGuardianData($id);
			$data['SecurityUser']['date_of_birth'] = date('d-m-Y', strtotime($data['SecurityUser']['date_of_birth']));

			$guardianSecurityId = $id;
			$name = $this->Message->getFullName($data) . ' (' . $guardianSecurityId . ')';

			if ($this->request->is(array('post', 'put'))) {
				$this->request->data['SecurityUser']['date_of_birth'] = date('Y-m-d', strtotime($this->request->data['SecurityUser']['date_of_birth']));
				if ($this->SecurityUser->saveAll($this->request->data)) {
					$this->Message->alert('general.edit.success');
					return $this->redirect(array('action' => 'view', $id));
				} else {
					$errors = $this->GuardianCustomValue->validationErrors;
					$this->Message->alert('general.edit.failed');
				}
			} else {
				$this->request->data = $data;
			}
			$this->Navigation->addCrumb($name);
			$this->set('name', $name);
			$this->set('fields', $fields);
		}
	}

	public function delete($id) {
		$this->StudentGuardian->id = $id;
		if(!$id || !$this->StudentGuardian->exists()) {
			$this->Message->alert('general.view.notExists', array('type' => 'warn'));
			$this->redirect(array('action' => 'index'));
		}

		$this->request->onlyAllow('post', 'delete');
		if ($this->StudentGuardian->delete()) {
			$this->Message->alert('general.delete.success');
		} else {
			$this->Message->alert('general.delete.failed', array('type' => 'error'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function profileImage(){
		$this->Navigation->addCrumb($this->Message->getLabel('general.profileImage'));
		
		$id = $this->Session->read('StudentGuardian.id');
		$data = $this->SecurityUser->find('first', array('recursive' => 0, 'conditions' => array('SecurityUser.id' => $id)));
		$this->set('data', $data);
		$this->set('id', $id);
		
		if($this->request->is('post')) { 
			$this->FileUploader->fileSizeLimit = 4*1024*1024;
			$this->FileUploader->fileModel = 'SecurityUser';
			$this->FileUploader->uploadFile();
			
			$image = $this->SecurityUser->getBase64EncodingImage($this->Session->read('StudentGuardian.data.SecurityUser.id'));
			$this->Session->write('StudentGuardian.picture', $image);
			return $this->redirect(array('action' => 'profileImage'));
		}
	}
	
	public function fetchImage($id){
		$this->FileUploader->fileModel = 'SecurityUser';
		$this->FileUploader->fetchImage($id);
	}
}
?>