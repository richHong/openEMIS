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

define('ACCESS_VIEW_TYPE', 1);

class AdministratorController extends AppController {
	public $uses = array(
		'Administrator',
		'SecurityUser',
		'SecurityUserType',
		'Country'
	);
	
	public $helpers = array('Paginator', 'Utility');
	public $components = array(
		'FileUploader',
		'option'
	);
	
	public $modules = array(
        'AdministratorPassword'
	);

	// public $acoName = 'AdministratorProfile';
	public $contentHeader;
	public $accessViewType;

	public $accessMapping = array(
		'search' => 'read',
		'listing' => 'read'
	);

	public function beforeFilter() {
		parent::beforeFilter();

		if(!$this->request->is('ajax')) {
			$viewUrlArray = array('controller' => $this->params['controller'], 'action' => 'view');
			$listingUrlArray = array('controller' => $this->params['controller'], 'action' => 'listing');

			// find out the name
			$data = $this->Session->read('Administrator.data');
			$name = $this->Message->getFullName($data);
			$this->set('name', $name);

			$isCurrentUser = false;
			if(!in_array($this->action, array('view','add','search'))) {
				$adminData = $this->Session->read('Administrator.data');
				$name = $this->Message->getFullName($adminData);
				$this->set('name', $name);

				if ($this->Session->check('Administrator.id') && $this->Session->read('Administrator.id')!=AuthComponent::user('id')) {
					$this->Navigation->addCrumb($this->Message->getLabel('Administrator.name'), $listingUrlArray);
					if(!in_array($this->action, array('view'))) { 
						$this->Navigation->addCrumb($name.' ('.$adminData['SecurityUser']['openemisid'].')', $viewUrlArray);
					}
				} else {	
					$this->Navigation->addCrumb($this->Message->getLabel('user.myProfile') . ' (' . $this->Session->read('Security.accessViewTypeName') . ')', $viewUrlArray);
					$isCurrentUser = true;
				}
			} else {
				if(in_array($this->action, array('view'))) {
					if (empty($this->params['pass'])
					) {
						if ($this->Session->check('Administrator.id')) {
							if ($this->Session->read('Administrator.id') == AuthComponent::user('id')) {
								$isCurrentUser = true;
							
							}
						}
					} else {
						if ($this->params['pass'][0]==AuthComponent::user('id')) {
							$isCurrentUser = true;
						}
					}
				} else {
					if ($this->Session->check('Administrator.id')) {
						if ($this->Session->read('Administrator.id') == AuthComponent::user('id')) {
							$isCurrentUser = true;
						}
					}
				}
				if (!in_array($this->action, array('add','search'))) {
					if ($isCurrentUser) { 
						if(in_array($this->action, array('view'))) {
							$this->Navigation->addCrumb($this->Message->getLabel('user.myProfile') . ' (' . $this->Session->read('Security.accessViewTypeName') . ')');
						} else {
							$this->Navigation->addCrumb($this->Message->getLabel('user.myProfile') . ' (' . $this->Session->read('Security.accessViewTypeName') . ')', $viewUrlArray);
						}
					} else {
						$this->Navigation->addCrumb($this->Message->getLabel('Administrator.name'), $listingUrlArray);
						
					}
				} 
				
			}

			$this->set('contentHeader', $this->Message->getLabel('Administrator.name'));
			$this->set('title', $this->Message->getLabel('Administrator.title'));
			$this->set('model', 'SecurityUser');
			$this->set('isCurrentUser',$isCurrentUser); // used for left_nav
			$this->set('tabElement', '../Administrator/tabs');
		}
	}

	public function listing() {
		return $this->redirect(array('action' => 'search'));
	}

	public function search() {
		$data = $this->SecurityUser->find(
			'all',
			array(
				'recursive' => -1,
				'fields' => array(
					'SecurityUser.first_name','SecurityUser.middle_name','SecurityUser.last_name','SecurityUser.id' ,'SecurityUser.openemisid' 
				),
				'joins' => array(
					array(
						'table' => 'security_user_types',
						'alias' => 'SecurityUserType',
						'conditions' => array(
							'SecurityUserType.security_user_id = SecurityUser.id',
							'SecurityUserType.type = 1'
						)
					)
				)
			)
		); 
		foreach ($data as $key => $value) {
			$data[$key]['SecurityUser']['full_name'] = $this->Message->getFullName($value);
		}
		$this->Navigation->addCrumb($this->Message->getLabel('general.searchResults'));
		$this->set('data', $data);
		$this->set('isCurrentUser', false); // used for left_nav
	}

	private function getFields() {
		$fields = $this->SecurityUser->getFields();
		$fields['username']['visible'] = false;
		$fields['password']['visible'] = false;
		$fields['photo_name']['visible'] = false;
		$fields['photo_content']['type'] = 'image';
		$fields['photo_content']['visible'] = array('edit' => true);
		$fields['super_admin']['visible'] = false;
		$fields['last_login']['visible'] = false;
		$fields['status']['visible'] = false;
		$fields['gender']['type'] = 'select';
		$fields['gender']['options'] = $this->option->get('gender');

		// $fields['new_password'] = Array (
  //           'type' => 'string',
  //           'order' => ++$fields['password']['order'],
  //           'visible' => array('edit' => true),
  //           'model' => 'SecurityUser'
  //       );
		// $fields['confirm_new_password'] = Array (
  //           'type' => 'string',
  //           'order' => ++$fields['new_password']['order'],
  //           'visible' => array('edit' => true),
  //           'model' => 'SecurityUser'
  //       );

		return $fields;
	}

	public function view($id=0) {
		$this->set('tabHeader', $this->Message->getLabel('general.general'));
		
		if ($id == 0) {	
			if ($this->Session->check('Administrator.id')) {
				$id = $this->Session->read('Administrator.id');
			} else {
				$id = AuthComponent::user('id');
			}
		}
		$this->SecurityUser->recursive = 0;
		$data = $this->SecurityUser->findById($id);
		$fields = $this->getFields();

		if ($this->SecurityUser->exists($id)) {
			$this->SecurityUser->recursive = 0;
			$data = $this->SecurityUser->findById($id);
			$fields = $this->getFields();

			$name = $this->Message->getFullName($data);
			$this->set('portletHeader', $name);
			$name = $this->Message->getFullName($data);
			$this->Navigation->addCrumb($name.' ('.$data['SecurityUser']['openemisid'].')');
			$this->set('name', $name);

			$this->Session->write('Administrator.data', $data);
			$this->Session->write('Administrator.id', $id);
			$this->set('data', $data);
			$this->set('model', 'SecurityUser');
			$this->set('fields', $fields);
			$this->set('id', $id);
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function add() {
		$fields = $this->getFields();
		unset($fields['new_password']);
		unset($fields['confirm_new_password']);
		if (empty($this->request->data)) {
			$default = $this->Country->getDefaultValue();
			$fields['country_id']['default'] = $default;
		}
		if($this->request->is('post')) {
			$this->SecurityUser->create();
			unset($this->request->data['SecurityUser']['id']);
			$this->request->data['SecurityUser']['date_of_birth'] = date('Y-m-d', strtotime($this->request->data['SecurityUser']['date_of_birth']));

			// file upload part...
			$this->request->data['SecurityUser']['file'] = $this->request->data['SecurityUser']['photo_content'];
			unset($this->request->data['SecurityUser']['photo_content']);

			if ($this->SecurityUser->save($this->request->data)) {
				$this->Message->alert('general.add.success');
				$id = $this->SecurityUser->getLastInsertId();
				$this->SecurityUserType->create();
				$type = $this->request->data;
				$type['SecurityUserType']['security_user_id'] = $id;
				$type['SecurityUserType']['type'] = '1';
				$savedSecurityUserType = $this->SecurityUserType->save($type);
				if ($this->request->data['SecurityUser']['file']['size']!=0) {
					$this->FileUploader->fileSizeLimit = 4 * 1024 * 1024;
					$this->FileUploader->fileModel = 'SecurityUser';
					$this->FileUploader->data = $this->request->data;
					$this->FileUploader->uploadFile($this->SecurityUser->getLastInsertId());
				}
				if (($this->request->data['SecurityUser']['file']['size']==0 || $this->FileUploader->success) && $savedSecurityUserType) {
					$this->Message->alert('general.add.success');
					return $this->redirect(array('action' => 'view', $this->SecurityUser->getLastInsertId()));
				} else {
					$this->Message->alert('general.add.failed');
				}
			} else {
				$this->Message->alert('general.add.failed', array('type' => 'error'));
			}
		}
		$this->Navigation->addCrumb($this->Message->getLabel('general.add'));
		$fields['openemisid']['default'] = $this->Administrator->getUniqueID();
		$this->set('fields', $fields);
		$this->set('isCurrentUser',false); // used for left_nav
	}

	public function edit($id=0) {
		if ($id == 0) {	
			$userId = AuthComponent::user('id');
		} else $userId = $id;

		if ($this->SecurityUser->exists($userId)) {
			if ($userId == AuthComponent::user('id')) {
				$this->set('portletHeader', $this->Message->getLabel('user.myProfile'));
			} else {
				$userName = $this->SecurityUser->find(
					'first',
					array(
						'recursive' => -1,
						'fields' => array('SecurityUser.first_name', 'SecurityUser.middle_name', 'SecurityUser.last_name'
						),
						'conditions' => array(
							array(
								'SecurityUser.id' => $userId
							)
						)
					)
				);
				$name = $this->Message->getFullName($userName);
				$this->set('portletHeader', $name);
			}
		
			$this->SecurityUser->recursive = 0;
			$data = $this->SecurityUser->findById($userId);

			if (array_key_exists('password', $data['SecurityUser'])) {
				unset($data['SecurityUser']['password']);
			}
			$fields = $this->getFields();

			if ($this->request->is(array('post', 'put'))) {

				$this->request->data['SecurityUser']['file'] = $this->request->data['SecurityUser']['photo_content'];
				unset($this->request->data['SecurityUser']['photo_content']);

				if ($this->SecurityUser->save($this->request->data)) {
					$this->FileUploader->fileSizeLimit = 4 * 1024 * 1024;
					$this->FileUploader->fileModel = 'SecurityUser';
					$this->FileUploader->data = $this->request->data;
					$this->FileUploader->allowNoFileUpload = true;
					$this->FileUploader->uploadFile();
					if ($this->FileUploader->success) {
						$this->Message->alert('general.edit.success');
						return $this->redirect(array('action' => 'view'));
					}// file upload error handled within component
				} else {
					$this->Message->alert('general.edit.failed',$this->request->data);
				}
			} else {
				$this->request->data = $data;
			}

			$this->Navigation->addCrumb($this->Message->getLabel('general.edit'));
			$this->set('tabHeader', $this->Message->getLabel('general.details'));
			$this->set('data', $data);
			$this->set('model', 'SecurityUser');
			$this->set('fields', $fields);
		} 
	}
}
?>