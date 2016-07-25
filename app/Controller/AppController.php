<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $_productName = 'OpenEMIS School';
	public $helpers = array('Html', 'Form', 'Session', 'Utility');
	public $components = array(
		'RequestHandler',
		'Session',
		'Auth' => array(
			'loginAction' => array('controller' => 'Users', 'action' => 'login'),
			'logoutRedirect' => array('controller' => 'Users', 'action' => 'login'),
			'authenticate' => array('Form' => array('userModel' => 'SecurityUser'))
		),
		// Custom Components starts here
		'Message',
		'Utility',
		'Navigation',
		'Option',
		'Access',
		'Localization'
		//'ExportFile'
	);
	
	public $modules = array();

	public $dbConfig = 'Config/database.php';
        public $install = 'Config/install';
	
	public function beforeFilter() {
		if(!file_exists(APP.$this->dbConfig) || file_exists(APP.$this->install)) {
			return $this->redirect(array('controller' => '/', 'action' => 'installer'));
		}
		
        $config = ClassRegistry::init('ConfigItem');
        $attendance_view = $config->getValue('attendance_view');
		$name_display_format = $config->getValue('name_display_format');

		$versionFile = WWW_ROOT . 'version';
		if(file_exists($versionFile)) {
			$f = fopen($versionFile, 'r');
			$version = fgets($f);
			fclose($f);
		}
		$this->set('version', $version);
		$this->set('attendance_view', $attendance_view);
		$this->Session->write('name_display_format', $name_display_format);

		// full name
		$nameArray = array();
		$nameArray['SecurityUser'] = array();
		$nameArray['SecurityUser']['first_name'] = AuthComponent::user('first_name');
		$nameArray['SecurityUser']['middle_name'] = AuthComponent::user('middle_name');
		$nameArray['SecurityUser']['last_name'] = AuthComponent::user('last_name');
		$this->set('userFullName', $this->Message->getFullName($nameArray));
		$this->set('_userId', AuthComponent::user('id'));

		if ($this->Session->check('Security.accessViewType')) {
			$accessViewType = $this->Session->read('Security.accessViewType');
			$accessViewTypeName = $this->Session->read('Security.accessViewTypeName');	
			$this->set('accessViewTypeName', $accessViewTypeName);
		}
		$this->set('_productName', $this->_productName);
	}
		
	public function isEdited() {
		return $this->request->is(array('put', 'post'));
	}
	
	public function invokeAction(CakeRequest $request) {
		try {
			// intercept for ControllerAction behavior
			$action = $request->params['action'];
			if(!method_exists($this, $action)) {
				return $this->processAction();
			}
			// End ControllerAction
			$method = new ReflectionMethod($this, $request->params['action']);

			if ($this->_isPrivateAction($method, $request)) {
				throw new PrivateActionException(array(
					'controller' => $this->name . "Controller",
					'action' => $request->params['action']
				));
			}
			return $method->invokeArgs($this, $request->params['pass']);
		} catch (ReflectionException $e) {
			if ($this->scaffold !== false) {
				return $this->_getScaffold($request);
			}
			throw new MissingActionException(array(
				'controller' => $this->name . "Controller",
				'action' => $request->params['action']
			));
		}
	}
	
	public function processAction() {
		$action = $this->action;
		
		if (!empty($this->modules)) {
			$module = null;
			if (in_array($action, $this->modules)) {
				$module = array();
			} else if (array_key_exists($action, $this->modules)) {
				$module = $this->modules[$action];
			}
			
			if (!is_null($module)) {
				$plugin = isset($module['plugin']) ? $module['plugin'] : '';
				$this->loadModel($plugin . '.' . $action);
				if (!$this->{$action}->Behaviors->loaded('ControllerAction')) {
					pr('ControllerActionBehavior is not loaded in ' . $action . ' Model');
					die;
				}
				return $this->{$action}->processAction($this);
			}
		}
	}
}