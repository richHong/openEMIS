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

class SecurityUser extends AppModel {
	public $belongsTo = array(
		'Country' => array(
			'className' => 'FieldOptionValue',
			'className' => 'Country',
			'foreignKey' => 'country_id'
		),
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
	
	public $hasMany = array(
		'SecurityUserType',
		'Student',
		'Staff',
		'StudentGuardian',
		'Contact',
		'Email',
		// 'GuardianCustomField',
		// 'GuardianCustomValue'
	);
	public $actsAs = array('ControllerAction');
	
	public function __construct() {
		parent::__construct();

		$this->validate = array(
			'openemisid' => array(
				'ruleRequired' => array(
					'rule' => 'notEmpty',
					'message' => $this->getErrorMessage('openemisid')
				),
				'ruleUnique' => array(
					'rule' => 'isUnique',
					'allowEmpty' => true,
					'message' => $this->getErrorMessage('openemisidUnique')
				)
			),
			'username' => array(
				'ruleUsernameCheckPassword' => array(
					'rule' => 'usernameCheckPassword',
					'on' => 'create',
					'message' => $this->getErrorMessage('username')
				), 
				'ruleChangePassword' => array(
					'rule' => 'notEmpty',
					'on' => 'update',
					'message' => $this->getErrorMessage('username')
				),
				'ruleUnique' => array(
					'rule' => 'isUnique',
					'allowEmpty' => true,
					'message' => $this->getErrorMessage('usernameInUse')
				)
				
			),
			'password' => array(
				'rulePasswordCheckUsername' => array(
					'rule' => 'passwordCheckUsername',
					'on' => 'create',
					'message' => $this->getErrorMessage('passwordLength')
				),
				'ruleChangePassword' => array(
					'rule' => 'notEmpty',
					'on' => 'update',
					'message' => $this->getErrorMessage('currentPassword')
				),
				'ruleAuthenticate' => array(
					'rule' => 'authenticate',
					'on' => 'update',
					'message' => $this->getErrorMessage('currentPasswordIncorrect')
				),
				'ruleMinLength' => array(
					'rule' => array('minLength', 6),
					'on' => 'create',
					'allowEmpty' => true,
					'message' => $this->getErrorMessage('passwordLength')
				)
			),
			'new_password' => array(
				'ruleChangePassword' => array(
					'rule' => 'notEmpty',
					'on' => 'update',
					'message' => $this->getErrorMessage('newPassword')
				),
				'ruleMinLength' => array(
					'rule' => array('minLength', 6),
					'on' => 'update',
					'message' => $this->getErrorMessage('passwordLength')
				)
			),
			'confirm_new_password' => array(
				'ruleChangePassword' => array(
					'rule' => 'notEmpty',
					'on' => 'update',
					'message' => $this->getErrorMessage('confirmNewPassword')
				),
				'ruleCompare' => array(
					'rule' => 'comparePasswords',
					'on' => 'update',
					'message' => $this->getErrorMessage('passwordsMismatch')
				)
			),
			'first_name' => array(
				'ruleRequired' => array(
					'rule' => 'notEmpty',
					'message' => $this->getErrorMessage('firstName')
				)
			),
			'last_name' => array(
				'ruleRequired' => array(
					'rule' => 'notEmpty',
					'message' => $this->getErrorMessage('lastName')
				)
			),
			'email' => array(
				'ruleValidEmail' => array(
					'rule' => 'email',
					'allowEmpty' => true,
					'message' => $this->getErrorMessage('email')
				)
			),
			'country_id' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => $this->getErrorMessage('country'),
					'allowEmpty' => false
				)
			),
			'gender' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => $this->getErrorMessage('gender'),
					'allowEmpty' => false
				)
			)
		);
	}

	public function getAccessViewType() {
		$securityUserType = $this->getSecurityUserType(AuthComponent::user('id'));

		// currently hardcoded... will need to discuss and handle when there are more staff roles other than teacher
		if ($securityUserType == 5) $securityUserType = 2;

		return $securityUserType;
	}

	public function getSecurityType() {
		// determine security user type
		$securityUserType = $this->getSecurityUserType(AuthComponent::user('id'));
		return $securityUserType;
	}

	public function getSecurityTypeName() {
		// determine security user type
		$securityUserType = $this->getSecurityUserType(AuthComponent::user('id'));
		return $this->SecurityUserType->types[$securityUserType];
	}

	public function usernameCheckPassword() {
		$usernameExists = array_key_exists('username', $this->data[$this->alias])&&$this->data[$this->alias]['username']&&$this->data[$this->alias]['username']!='';
		$passwordExists = array_key_exists('password', $this->data[$this->alias])&&$this->data[$this->alias]['password']&&$this->data[$this->alias]['password']!='';

		if ($usernameExists != $passwordExists) {
			if (!$usernameExists && $passwordExists) return false;
		}
		return true;
	}

	public function passwordCheckUsername() {
		$usernameExists = array_key_exists('username', $this->data[$this->alias])&&$this->data[$this->alias]['username']&&$this->data[$this->alias]['username']!='';
		$passwordExists = array_key_exists('password', $this->data[$this->alias])&&$this->data[$this->alias]['password']&&$this->data[$this->alias]['password']!='';
		if ($usernameExists != $passwordExists) {
			if ($usernameExists && !$passwordExists) return false;
		}
		return true;
	}
	
	// Change password validation
	public function authenticate() {
		$username = AuthComponent::user('username');
		$password = AuthComponent::password($this->data[$this->alias]['password']);
		$count = $this->find('count', array('recursive' => -1, 'conditions' => array('username' => $username, 'password' => $password)));
		return $count==1;
	}
	
	public function comparePasswords() {
		if(strcmp($this->data[$this->alias]['new_password'], $this->data[$this->alias]['confirm_new_password']) == 0 ) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['new_password']);
			return true;
		}
		return false;
	}
	// End change password validation
	
	public function getBase64EncodingImage($id) {
		$this->recursive = -1;
		$obj = $this->findById($id);
		$data = array();
		if($obj) {
			if(!empty($obj[$this->alias]['photo_name'])) {
				$filename = strtolower($obj[$this->alias]['photo_name']);
				$exts = explode(".", $filename);
				$ext = $exts[count($exts)-1];
				if($ext === 'jpg') {
					$ext = 'jpeg';
				}
				$data['type'] = $ext;
				$data['content'] = base64_encode($obj[$this->alias]['photo_content']);
			}
		}
		return $data;
	}
	
	// ControllerAction starts
	public $indexPage = 'user';
	public $module = 'SecurityUser';

	public function getFields($options=array()) {
		parent::getFields();
		$order = 1;
		$this->setFieldOrder('openemisid', $order++);
		$this->setFieldOrder('first_name', $order++);
		$this->setFieldOrder('middle_name', $order++);
		$this->setFieldOrder('last_name', $order++);
		$this->setFieldOrder('date_of_birth', $order++);
		$this->setFieldOrder('photo_content', $order++);
		$this->setFieldOrder('country_id', $order++);
		$this->setFieldOrder('gender', $order++);
		$this->setFieldOrder('address', $order++);
		$this->setFieldOrder('postal_code', $order++);

		$this->fields['country_id']['type'] = 'select';
		$this->fields['country_id']['options'] = $this->Country->getOptions();
		$this->fields['gender']['type'] = 'select';
		$this->fields['gender']['options'] = $this->getGenderOptions();
		$this->fields['identification_no']['type'] = 'hidden';
		return $this->fields;
	}

	public function getFieldsForGuardian() {
		// $currentCustomField = 'GuardianCustomField';
		// $currentCustomValue = 'GuardianCustomValue';
		parent::getFields();
		$order = 1;
		$this->setFieldOrder('openemisid', $order++);
		$this->setFieldOrder('first_name', $order++);
		$this->setFieldOrder('middle_name', $order++);
		$this->setFieldOrder('last_name', $order++);
		$this->setFieldOrder('date_of_birth', $order++);
		$this->setFieldOrder('country_id', $order++);
		$this->setFieldOrder('gender', $order++);
		$this->setFieldOrder('address', $order++);
		$this->setFieldOrder('postal_code', $order++);
		$this->fields['country_id']['type'] = 'select';
		$this->fields['country_id']['options'] = $this->Country->getOptions();
		$this->fields['gender']['type'] = 'select';
		$this->fields['gender']['options'] = $this->getGenderOptions();

		// $this->fields[$currentCustomField] = $this->$currentCustomField->getCustomFields();
		$this->fields['identification_no']['type'] = 'hidden';
		return $this->fields;
	}

	public function getGuardianData($id) {
		$data = $this->findById($id);
		// $additionalData = $this->GuardianCustomField->getCustomFieldValues(array('id'=>$id));
		// $data['GuardianCustomValue'] = $additionalData;
		return $data;
	}
	
	public function beforeAction($controller, $params) {
		$controller->Navigation->addCrumb($controller->Message->getLabel('admin.userManagement'), array('controller' => $controller->params['controller'], 'action' => $this->indexPage));
		$controller->set('model', get_class($this));
		$controller->set('page', $this->indexPage);
		$controller->set('contentHeader', $controller->Message->getLabel('admin.userManagement'));
	}
	
	public function user($controller, $params) {
		$controller->Navigation->addCrumb($controller->Message->getLabel('admin.listOfUsers'));
		$class = get_class($this);
		$search = '';
		$sortBy = isset($controller->request->params['named']['sort']) ? $controller->request->params['named']['sort'] : $class.'.username';
		$sortOrder = isset($controller->request->params['named']['direction']) ? $controller->request->params['named']['direction'] : 'asc';
		$pageNo = isset($controller->request->params['named']['page']) ? $controller->request->params['named']['page'] : 1;
		$conditions = array($class.'.super_admin' => 0);
		$settings = array('limit' => 10, 'maxLimit' => 100);
		if($controller->Session->check($class.'.search.sortBy')) {
			$sortBy = $controller->Session->read($class.'.search.sortBy');
			$sortOrder = $controller->Session->read($class.'.search.sortOrder');
		}
		if($controller->request->is(array('post', 'put'))) { // 
			$sortBy = $controller->request->data['sortBy'];
			$sortOrder = $controller->request->data['sortOrder'];
			$pageNo = $controller->request->data['pageNo'];
			$settings['page'] = $pageNo;
			$controller->Session->write($class.'.search.sortBy', $sortBy);
			$controller->Session->write($class.'.search.sortOrder', $sortOrder);
			if(isset($controller->request->data['clear'])) {
				$controller->Session->delete($class.'.search.str');
			} else {
				if(isset($controller->request->data[$class]['search'])) {
					$search = trim($controller->request->data[$class]['search']);
					if(empty($search)) {
						$controller->Session->delete($class.'.search.str');
					}
				}
			}
		} else {
			if($controller->Session->check($class.'.search.str')) {
				$search = $controller->Session->read($class.'.search.str');
			}
		}
		$order = array($sortBy => $sortOrder);
		$settings['order'] = $order;
		$controller->Paginator->settings = $settings;
		
		if(!empty($search)) {
			$conditions['search'] = $search;
		}
		$data = $controller->paginate($class, $conditions);
		
		if(!empty($search)) {
			if(count($data) > 0) {
				$controller->Session->write($class.'.search.str', $search);
			} else {
				$controller->Message->alert('general.search.no_result', array('type' => 'info'));
				$controller->Session->delete($class.'.search.str');
			}
		}
		$controller->set('data', $data);
		$controller->set('searchValue', $search);
		$controller->set('sortBy', $order);
		$controller->set('pageNo', $pageNo);
	}
	
	public function user_view($controller, $params) {
		$model = get_class($this);
		$id = isset($params['pass'][0]) ? $params['pass'][0] : null;

		if(empty($id) || !$this->exists($id)) {
			$controller->Message->alert('general.view.notExists', array('type' => 'warn'));
			return $controller->redirect(array('action' => $this->indexPage));
		}
		$this->recursive = 0;
		$data = $this->findById($id);
		
		$firstName = trim($data['SecurityUser']['first_name']);
		$lastName = trim($data['SecurityUser']['last_name']);
		$title = trim($firstName . ' ' . $lastName);
		$controller->Navigation->addCrumb($title);	
		$controller->set('contentHeader', $title);
		$controller->set('data', $data);
		$controller->set('id', $id);
		$controller->set('fields', $this->getDisplayFields());
	}
	
	public function user_edit($controller, $params) {
		$crumb = $controller->Message->getLabel('admin.addUser');
		$id = isset($params['pass'][0]) ? $params['pass'][0] : null;

		if(empty($id) || !$this->exists($id)) {
			$controller->Message->alert('general.view.notExists', array('type' => 'warn'));
			return $controller->redirect(array('action' => $this->indexPage));
		}

		if ($controller->request->is(array('post', 'put'))) {
			if ($this->saveAll($controller->request->data)) {
				if(!empty($id)) {
					$controller->Message->alert('general.edit.success');
				} else {
					$controller->Message->alert('general.add.success');
					$id = $this->id;
				}
				return $controller->redirect(array('action' => 'user_view', $id));
			} else {
				if(!empty($id)) {
					$controller->Message->alert('general.edit.failed', array('type' => 'error'));
				} else {
					$controller->Message->alert('general.add.failed', array('type' => 'error'));
				}
			}
		} else {
			if(!empty($id)) {
				$this->recursive = 0;
				$data = $this->findById($id);
				$controller->request->data = $data;
				$firstName = trim($data['SecurityUser']['first_name']);
				$lastName = trim($data['SecurityUser']['last_name']);
				$title = trim($firstName . ' ' . $lastName);
				$crumb = $title;
				$controller->set('contentHeader', $title);
			} else {
				$controller->set('contentHeader', $controller->Message->getLabel('admin.addUser'));
			}
		}
		$controller->Navigation->addCrumb($crumb);
		$controller->set('id', $id);
		$controller->set('fields', $this->getDisplayFields());
	}
	// ControllerAction ends
	
	public function beforeSave($options = array()) {
		parent::beforeSave();
		if (!$this->id && !isset($this->data[$this->alias][$this->primaryKey])) {
			// insert
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		} else {
			// edit - already handled in authenticate no action required
		}
		return true;
	}
	
	/*
	public function doValidate($data) {
		$validate = true;
		if(isset($data['new_password']) && !empty($data['new_password'])) {
			$newPassword = $data['new_password'];
			$confirmPassword = $data['confirm_new_password'];
			if(strcmp($newPassword, $confirmPassword) != 0) {
				$this->invalidate('password', __('Your passwords do not match.'));
				unset($data['password']);
			} else {
				$data['password'] = $newPassword;
			}
		} else {
			unset($data['password']);
		}
		if($validate) {
			$this->set($data);
			if($this->validates()) {
				$this->save($data);
			} else {
				$validate = false;
			}
		}
		return $validate;
	}
	*/
	
	public function autocomplete($search, $type) {
		$model = get_class($this);
		$search = sprintf('%%%s%%', $search);
		$list = $this->find('all', array(
			'recursive' => -1,
			'fields' => array($model . '.*'),
			'joins' => array(
				array(
					'table' => 'security_user_types',
					'alias' => 'SecurityUserType',
					'conditions' => array(
						'SecurityUserType.security_user_id = SecurityUser.id',
						'SecurityUserType.type = ' . $type
					)
				)
			),
			'conditions' => array(
				'OR' => array(
					$model . '.first_name LIKE' => $search,
					$model . '.last_name LIKE' => $search,
					$model . '.openemisid LIKE' => $search
				)
			),
			'order' => array($model . '.openemisid', $model . '.first_name', $model . '.last_name')
		));
		
		$data = array();
		foreach($list as $obj) {
			$user = $obj[$model];
			$info = array(
				'id' => $user['id'], 
				'first_name' => $user['first_name'], 
				'last_name' => $user['last_name'],
				'openemisid' => $user['openemisid']
			);
			$data[] = array(
				'data' => $info,
				'value' => sprintf('%s - %s %s', $user['openemisid'], $user['first_name'], $user['last_name'])
			);
		}
		return $data;
	}
	
	public function updateLastLogin($id) {
		$this->id = $id;
		$this->saveField('last_login', date('Y-m-d H:i:s'));
	}
	
	// Pagination starts
	public function paginateJoins($joins, $params) {
		
		return $joins;
	}
	
	public function paginateConditions($params) {
		$conditions = $params;
		$class = get_class($this);
		if(isset($params['search'])) {
			$search = '%' . $params['search'] . '%';
			$conditions = array('OR' => array(
				$class.'.username LIKE' => $search,
				$class.'.first_name LIKE' => $search,
				$class.'.last_name LIKE' => $search
			));
			unset($conditions['search']);
		}
		return $conditions;
	}
	
	public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		$model = $this->name;
		$fields = array('SecurityUser.*');
		$joins = array();
		$data = $this->find('all', array(
			'recursive' => 0,
			'fields' => $fields,
			'joins' => $this->paginateJoins($joins, $conditions),
			'conditions' => $this->paginateConditions($conditions),
			'limit' => $limit,
			'offset' => (($page-1)*$limit),
			'group' => null,
			'order' => $order
		));
		return $data;
	}
	
	public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$joins = array();
		$count = $this->find('count', array(
			'recursive' => 0,
			'joins' => $this->paginateJoins($joins, $conditions),
			'conditions' => $this->paginateConditions($conditions)
		));
		return $count;
	}
	// Pagination ends

	public function getSecurityUserType($userId) {
		$securityUserType = null;
		$this->unbindModel(
			array(
				'hasMany' => array('Contact', 'Email'),
				'belongsTo' => array('ModifiedUser', 'CreatedUser')
			)
		);
		$data = $this->find(
			'first',
			array(
				'recursive' => 1,
				'fields' => array('SecurityUser.id'),
				'conditions' => array(
					'SecurityUser.id' => $userId				
				)
			)
		);

		if (!empty($data['SecurityUserType'])) {
			$securityUserType = $data['SecurityUserType'][0]['type'];
		} 

		return $securityUserType;
	}
}