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


class AccessComponent extends Component {
	public $components = array('Auth', 'Acl');
	// to use the acl component

	public $accessMapping = array(
		'index' => 'read',
		'view' => 'read',
		'add' => 'create',
		'edit' => 'update',
		'remove' => 'delete',
		'export' => 'execute'
	);

	public function initialize(Controller $controller) {
		if (get_class($controller) == 'CakeErrorController') return;
		if (get_class($controller) == 'PreferencesController') return;

		$alias = $controller->params['controller'];

		$actionName = $controller->params['action'];

		$securityUserType = null;
		if ($controller->Session->check('Security.securityTypeName')) {
			$securityUserType = $controller->Session->read('Security.securityTypeName');
		} 
		
		// handle the alias
		$modelName = null;
		if (in_array($actionName, $controller->modules)) {
			if ($actionName == 'Timetable') {
				$alias = Inflector::singularize($controller->name).$actionName;
			} else if (isset($controller->$actionName->acoName)) {
				$alias = $controller->$actionName->acoName;
			} else {
				$alias = $actionName;
			}

			$modelName = $actionName;
			if (!empty($controller->params->pass)) {
				$actionName = $controller->params->pass[0];
			} else {
				$actionName = 'index';
			}
		} else {
			if (isset($controller->acoName)) {
				$alias = $controller->acoName;
			}
		}
		
		// handle the operation
		$accessMapping = array();

		if (array_key_exists($actionName, $this->accessMapping)) {
			$accessMapping = $this->accessMapping;
		} else {
			if (isset($modelName)) {
				$accessMapping = $controller->$modelName->accessMapping;
			} else {
				if (isset($controller->accessMapping)) {
					$accessMapping = $controller->accessMapping;
				}
			}
		}
		if(array_key_exists($actionName, $accessMapping)) {
			$operation = $accessMapping[$actionName];
		} else {
			throw new NotFoundException();
		}
		
		// pr($operation);
		// pr('access check - '.$securityUserType.'/'.$alias.'/'.$actionName.'-'.$operation);
		$viewCheckVarArray = array(
			'_read' => 'read',
			'_update' => 'update',
			'_create' => 'create',
			'_delete' => 'delete',
			'_execute' => 'execute'
		);
		if ($operation!= 'none') {
			if ($securityUserType!=null) {
				if (!$this->check($securityUserType, $alias, $operation)) {
					return $controller->redirect($this->getRedirectLoginLandingPage($controller->Session->read('Security.accessViewType')));
				}
			} else {
				if (!($alias == 'Users' && $actionName == 'login')) {
					$redirect = $this->Auth->logout();
					return $controller->redirect($redirect);
				}
			}
			foreach ($viewCheckVarArray as $viewVar => $checkVar) {
				$controller->set($viewVar, $this->check($securityUserType, $alias, $checkVar));
			}
		} else {
			foreach ($viewCheckVarArray as $viewVar => $checkVar) {
				$controller->set($viewVar, true);
			}
		}

		// set the landing pages
		$landingPage = $this->getRedirectLoginLandingPage($controller->Session->read('Security.accessViewType'));
		if (!empty($landingPage) && array_key_exists('controller', $landingPage)) {
			$landingPage = $landingPage['controller'];
		}
		$controller->set('_landingPage',$landingPage);
		$controller->set('_securityTypeName', $securityUserType);
	}

	public function startup(Controller $controller) {
		$controller->set('_access', $this);
	}

	public function check($aro, $aco, $action = "*") {
		return $this->Acl->check($aro, $aco, $action);
	}

	public function allow($aro, $aco, $action = "*") {
		return $this->Acl->allow($aro, $aco, $action);
	}

	public function deny($aro, $aco, $action = "*") {
		return $this->Acl->deny($aro, $aco, $action);
	}

	public function inherit($aro, $aco, $action = "*") {
		return $this->Acl->inherit($aro, $aco, $action);
	}

	public function hasAco($acoName) {
		$Aco = ClassRegistry::init('Aco');
		return $Aco->hasAny(array('alias'=>$acoName));
	}

	public function grant($aro, $aco, $action = "*") {
		trigger_error(__d('cake_dev', '%s is deprecated, use %s instead', 'AclComponent::grant()', 'allow()'), E_USER_WARNING);
		return $this->Acl->allow($aro, $aco, $action);
	}

	public function revoke($aro, $aco, $action = "*") {
		trigger_error(__d('cake_dev', '%s is deprecated, use %s instead', 'AclComponent::revoke()', 'deny()'), E_USER_WARNING);
		return $this->Acl->deny($aro, $aco, $action);
	}

	public function getRedirectLoginLandingPage($accessViewType) {
		$redirectArray = array();
		switch($accessViewType) {
			// case 1: 
			// 	$redirectArray = (array('controller' => 'Administrator/view/'.$this->Auth->user('id')));
			// 	break;
			// case 2: 
			// 	$redirectArray = (array('controller' => 'Staff/view'));
			// 	break;
			// case 3: 
			// 	$redirectArray = (array('controller' => 'Students/view'));
			// 	break;
			// case 4: 
			// 	$redirectArray = (array('controller' => 'Guardians/view'));
			// 	break;
			default:
				$redirectArray = (array('controller' => 'Dashboard'));
				break;
		}
		return $redirectArray;
	}

	public function setup() {

		// $dataArray = array(
		// 	array( 'id' => '1', 'alias' => 'Everyone', 'parent_id' => null)	,
		// 	array( 'id' => '2', 'alias' => 'Admin', 'parent_id' => 1)	,
		// 	array( 'id' => '3', 'alias' => 'Staff', 'parent_id' => 1)	,
		// 	array( 'id' => '4', 'alias' => 'Student', 'parent_id' => 1)	,
		// 	array( 'id' => '5', 'alias' => 'Guardian', 'parent_id' => 1)	,
		// 	array( 'id' => '6', 'alias' => 'Teacher', 'parent_id' => 3)	
		// );

		// $aro = ClassRegistry::init('Aro');
		// $aro->saveAll($dataArray);


		$dataArray = array(
			array( 'model' => null, 'foreign_key' => null, 'parent_id' => null, 'alias' => 'All')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 1, 'alias' => 'Events')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 1, 'alias' => 'Students')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 1, 'alias' => 'Staff')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 1, 'alias' => 'Guardians')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 1, 'alias' => 'Admin')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 1, 'alias' => 'Classes')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 1, 'alias' => 'Administrator')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 1, 'alias' => 'Dashboard')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentProfile')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentContact')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentGuardian')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentBehaviour')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentTimetable')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentResult')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentAttachment')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentAttendanceDay')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentAttendanceLesson')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentIdentity')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentReportCard')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentFee')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 3, 'alias' => 'StudentPassword')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffProfile')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffContact')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffAttendanceDay')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffAttendanceLesson')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffTimetable')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffBehaviour')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffEmployment')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffAttachment')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffIdentity')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 4, 'alias' => 'StaffPassword')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 5, 'alias' => 'GuardianProfile')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 5, 'alias' => 'GuardianContact')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 5, 'alias' => 'GuardianIdentity')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 5, 'alias' => 'GuardianStudent')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 5, 'alias' => 'GuardianPassword')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'AdminProfile')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'Education')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'Assessment')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'CustomField')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'FieldOptions')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'Translations')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'ConfigItem')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'EducationProgramme')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'EducationGrade')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'EducationGradesSubject')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'EducationSubject')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'Finance')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 6, 'alias' => 'EducationFee')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassProfile')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassStudent')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassTeacher')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassSubject')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassAssignment')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassResult')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassLesson')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassTimetable')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassAttendanceDay')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassAttendanceLesson')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 7, 'alias' => 'ClassAttachment')	,
array( 'model' => null, 'foreign_key' => null, 'parent_id' => 8, 'alias' => 'AdministratorPassword')	
		);
		$aco = ClassRegistry::init('Aco');
		$aco->query('TRUNCATE TABLE acos;');
		$aco->saveAll($dataArray);
		
	}
}
