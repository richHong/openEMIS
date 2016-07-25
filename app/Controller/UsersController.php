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

class UsersController extends AppController {
	public $useTable = false;

	public $uses = array(
		'SecurityUser',
		'ContactType',
		'InstitutionSite'
	);

	public $components = array(
		'FileUploader',
		'option'
	);

	public $accessMapping = array(
		'login' => 'none',
		'login_remote' => 'none',
		'logout' => 'none',
		'password' => 'update',
		'profileImage' => 'read',
	);


	public function initialize() {
		$this->Components->disable('Access');
	}
	
	public function beforeFilter() {
		
		parent::beforeFilter();
		$this->Auth->allow('login');
		$this->Auth->allow('login_remote');
		$this->set('title', 'My Profile');

		$this->Navigation->addCrumb('My Profile', array('controller' => $this->params['controller'], 'action' => 'view'));

		$this->set('contentHeader', $this->Message->getLabel('user.myProfile'));
		$this->set('portletHeader', $this->Message->getLabel('user.myProfile'));

		$this->set('model', 'SecurityUser');
		$this->set('tabElement', '../Users/tabs');
	}
	
    public function login() {
		$this->layout = 'login';
		
		$username = '';
		$password = '';
		
		if($this->request->is('post') && $this->request->data['submit'] == 'login') {
			$username = $this->data['SecurityUser']['username'];
			$this->log('[' . $username . '] Attempt to login as ' . $username . '@' . $_SERVER['REMOTE_ADDR'], 'security');
			if(!$this->RequestHandler->isAjax()) {
				$result = $this->Auth->login();
				if($result) {
					if($this->Auth->user('status') == 1) {
						$this->Session->delete('_alert');
						$this->log('[' . $username . '] Login successfully.', 'security');
						$userId = AuthComponent::user('id');
						$this->SecurityUser->updateLastLogin($userId);
						$this->Session->write('Security.accessViewType',$this->SecurityUser->getAccessViewType());
						$this->Session->write('Security.accessViewTypeName',$this->SecurityUser->SecurityUserType->types[$this->Session->read('Security.accessViewType')]);
						$this->Session->write('Security.securityType',$this->SecurityUser->getSecurityType());
						$this->Session->write('Security.securityTypeName',$this->SecurityUser->getSecurityTypeName());
						$site = $this->InstitutionSite->find('first');
						$this->Session->write('InstitutionSite.data', $site);
						$this->redirect(
							$this->getRedirectLoginLandingPage(
								$this->Session->read('Security.accessViewType')
							)
						);
					} else if ($this->Auth->user('status') == 0) {
						$this->log('[' . $username . '] Account is not active.', 'security');
						$this->Message->alert('security.login.inactive', array('type' => 'error'));
					}
				} else {
					$this->Message->alert('security.login.fail', array('type' => 'error'));
				}
			} else {
				// ajax login implement here, if necessary
			}
		} else {
			// login credential sent from website
			if ($this->Session->check('login.username')) {
				$username = $this->Session->read('login.username');
			}
			if ($this->Session->check('login.password')) {
				$password = $this->Session->read('login.password');
			}
		}
		
		if($this->request->is('post') && $this->request->data['submit'] == 'reload') {
			$username = $this->request->data['SecurityUser']['username'];
			$password = $this->request->data['SecurityUser']['password'];
		}
		
		$this->set('username', $username);
		$this->set('password', $password);
    }

    public function getRedirectLoginLandingPage($securityType) {
    	if (isset($this->Access)) {
    		return $this->Access->getRedirectLoginLandingPage($securityType);
    	} else {
    		return (array('controller' => 'Events'));
    	}
    }
	
	public function login_remote() {
        $this->autoRender = false;
        $this->Session->write('login.username', $this->data['username']);
        $this->Session->write('login.password', $this->data['password']);
        return $this->redirect(array('action' => 'login'));
    }

    public function logout() {
		$redirect = $this->Auth->logout();
		$this->Session->destroy();
        $this->redirect($redirect);
    }
    
	public function index() {
		$data = $this->SecurityUser->find('all'); 
		$this->set('data', $data);
	}

	public function getFields() {
		$fields = $this->SecurityUser->getFields();
		$fields['password']['visible'] = false;
		$fields['photo_name']['visible'] = false;
		$fields['photo_content']['type'] = 'image';
		$fields['photo_content']['visible'] = array('edit' => true);
		$fields['super_admin']['visible'] = false;
		$fields['last_login']['visible'] = false;
		$fields['status']['visible'] = false;
		$fields['gender']['type'] = 'select';
		$fields['gender']['options'] = $this->option->get('gender');

		return $fields;
	}
	
	public function view() {
		$this->Navigation->addCrumb('Details');
		$this->set('tabHeader', $this->Message->getLabel('general.details'));
		$userId = $this->Auth->user('id');
		$this->SecurityUser->recursive = 0;
		$data = $this->SecurityUser->findById($userId);
		$fields = $this->getFields();

		$this->set('data', $data);
		$this->set('model', 'SecurityUser');
		$this->set('fields', $fields);
		$this->set('id', $userId);
	}
	
	public function edit() {
		$userId = $this->Auth->user('id');
		$this->SecurityUser->recursive = 0;
		$data = $this->SecurityUser->findById($userId);
		$fields = $this->getFields();

		if ($this->request->is(array('post', 'put'))) {
			// file upload part...
			$this->request->data['SecurityUser']['file'] = $this->request->data['User']['photo_content'];
			unset($this->request->data['User']['photo_content']);

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
		$this->set('model', 'User');
		$this->set('fields', $fields);
	}
		
	public function password() {
		$userId = $this->Auth->user('id');
		$this->SecurityUser->recursive = 0;
		$data = $this->SecurityUser->findById($userId);

		$title = 'Change Password';
		$this->Navigation->addCrumb($title);
		$this->set('tabHeader', $this->Message->getLabel('general.changePassword'));
		
		if($this->request->is(array('put', 'post'))) {
			// pr('usercontroller password');
			// pr($this->request->data);
			// echo '<br>';
			if($this->SecurityUser->save($this->request->data)) {
				// die('dead');
				$this->Message->alert('general.edit.success');
				return $this->redirect(array('action' => 'password'));
			} else {
				$this->Message->alert('general.edit.failed', array('type' => 'error'));
			}
		}
		$this->set('data', $data);
		$this->set('model', 'SecurityUser');
		$this->set('id', $this->Auth->user('id'));
	}
	
	public function fetchImage($id){
		$this->FileUploader->fileModel = 'SecurityUser';
		$this->FileUploader->fetchImage($id);
	}
	
	public function ajax_add_contact() {
		$this->layout = 'ajax';
		$index = $this->params->query['index'];
		$type = $this->params->query['type'];
		$contactTypeOptions = $this->ContactType->getOptions();
		$this->set('contactTypeOptions', $contactTypeOptions);
		$this->set('index', $index);
		$this->set('type', $type);
	}
}
