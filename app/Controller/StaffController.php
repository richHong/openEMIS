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

define('ACCESS_VIEW_TYPE', 2);

class StaffController extends AppController {
	public $uses = array(
		'Staff',
		'StaffStatus',
		'StaffCategory',
		'Timetable',
		'TimetableEntry',
		'ContactType',
		'SecurityUser',
		'StaffAttendanceDay',
		'StaffAttachment',
		'StaffContact',
		'Country',
		'StaffCustomField',
		'StaffCustomValue'
	);
	public $helpers = array('Paginator', 'Utility','CustomField');
	public $components = array(
		'Paginator',
		'Schedule',
		'FileUploader',
		'Search'
	);
	public $modules = array(
        'StaffContact',
		'StaffAttendance',
		'timetable' => 'Timetable',
		'StaffBehaviour',
		'employment' => 'StaffEmployment',
		'StaffAttachment',
		'StaffAttendanceDay',
		'StaffPassword',
		'StaffIdentity'
	);

	public $acoName = 'StaffProfile';
	public $contentHeader;
	public $accessViewType;

	public $accessMapping = array(
		'search' => 'read',
		'listing' => 'read',
		'export' => 'execute'
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->Session->check('Security.accessViewType')) $this->accessViewType = $this->Session->read('Security.accessViewType');
		$this->contentHeader = $this->Message->getLabel('Staff.title');
		if (isset($this->accessViewType) && $this->accessViewType == ACCESS_VIEW_TYPE) {
			$this->contentHeader = $this->Message->getLabel('user.myProfile') . ' (' . $this->Session->read('Security.accessViewTypeName') . ')';
		}
		$this->set('contentHeader', $this->contentHeader);

		$actions = array('add', 'index', 'view', 'search', 'edit', 'listing','export');

		if(!$this->request->is('ajax')) {
			if (isset($this->accessViewType) && $this->accessViewType != ACCESS_VIEW_TYPE) {
				$this->Navigation->addCrumb($this->Message->getLabel('staff.title'), array('controller' => $this->params['controller'], 'action' => 'index'));
			}
			
			if (!in_array($this->action, $actions)) { // if the action is not one of the actions not requiring the id
				if(!$this->Session->check('Staff.id')) { // if id not exists in session
					// only Staff Attendance Day breaks the norm
					if ($this->action!='StaffAttendanceDay') {
						$this->Message->alert('general.view.notExists');
						return $this->redirect(array('action' => 'index'));	
					}
				} else {
					$id = $this->Session->read('Staff.id');
					if(!$this->Staff->exists($id)) {
						$this->Message->alert('general.view.notExists');
						return $this->redirect(array('action' => 'index'));
					} else {
						$data = array();
						$data['SecurityUser'] = array();
						$data['SecurityUser']['first_name'] = trim($this->Session->read('Staff.data.SecurityUser.first_name'));
						$data['SecurityUser']['middle_name'] = trim($this->Session->read('Staff.data.SecurityUser.middle_name'));
						$data['SecurityUser']['last_name'] = trim($this->Session->read('Staff.data.SecurityUser.last_name'));

						$openemisid = trim($this->Session->read('Staff.data.SecurityUser.openemisid'));
						$name = $this->Message->getFullName($data) . ' (' . $openemisid . ')';
						$this->Session->write('Report.Staff.reportHeader', array(
							$this->Message->getLabel('general.name')=>$this->Message->getFullName($data),
							$this->Message->getLabel('SecurityUser.openemisid')=>$openemisid)
						);	
						$this->set('name', $name);
						if ($this->action !== 'view' && $this->action !== 'edit') {
							if (isset($this->accessViewType) && $this->accessViewType != ACCESS_VIEW_TYPE) {
								$this->Navigation->addCrumb($name, array('controller' => 'Staff', 'action' => 'view', $id));
							} else {
								$this->Navigation->addCrumb($this->contentHeader, array('controller' => 'Staff', 'action' => 'view', $id));
							}
						}
					}
				}
			}
		}
		$this->set('title', $this->Message->getLabel('staff.title'));
		$this->set('model', 'Staff');

		if ($this->action!='StaffAttendanceDay' || $this->accessViewType == 2) {
			$this->set('tabElement', '../Staff/tabs');
		}
	}
	
	public function index() {
		$this->Navigation->addCrumb($this->Message->getLabel('general.search'));
		$statusOptions = $this->StaffStatus->getOptions();
		$this->set('statusOptions', $statusOptions);
	}

	public function listing() {
		$this->Search->clearSearchParams('Staff');
		return $this->redirect(array('action' => 'search'));
	}
	
	public function search() {
		if ($this->accessViewType != ACCESS_VIEW_TYPE) {
			$this->Navigation->addCrumb($this->Message->getLabel('general.searchResults'));
			$data = $this->Search->search('Staff', array('sortDefault' => 'SecurityUser.openemisid'));
			if(empty($data)) $this->Message->alert('general.view.noRecords');
			$this->set('data', $data);
		}
	}

	public function export() {
		$currentModel = 'Staff';
		$this->$currentModel->export();
	}
	
	public function view($id=0) {
		if ($this->accessViewType != ACCESS_VIEW_TYPE) {
			if ($id == 0) {
				if ($this->Session->check('Staff.id')) {
					$id = $this->Session->read('Staff.id');
				} else {
					$this->Message->alert('general.view.notExists');
					return $this->redirect(array('action' => 'index'));
				}
			}
		} else {
			// is staff
			$this->Staff->recursive = 0;
			$id = $this->Staff->getStaffIdBySecurityId(AuthComponent::user('id'));
		}
		
		if ($this->Staff->exists($id)) {
			$fields = $this->Staff->getFields();
			
			$this->Staff->recursive = 0;
			$data = $this->Staff->getStaffData($id);
			$securityUserID = $data['SecurityUser']['id'];
			$this->Session->write('Staff.id', $id);
			$this->Session->write('Staff.data', $data);

			$mainPhone = $this->StaffContact->getPreferredPhone($securityUserID);
			$mainEmail = $this->StaffContact->getPreferredEmail($securityUserID);

			$this->Session->write('Staff.id', $id);
			$this->Session->write('Staff.data.mainPhone', $mainPhone);
			$this->Session->write('Staff.data.mainEmail', $mainEmail);
			$this->Session->write('Staff.data.status', $data['StaffStatus']['name']);
			
			$openemisid = $data['SecurityUser']['openemisid'];
			$name = $this->Message->getFullName($data) . ' (' . $openemisid . ')';

			$this->set('name', $name);
			$this->set('data', $data);
			$this->set('fields', $fields);

			if ($this->accessViewType != ACCESS_VIEW_TYPE) {
				$this->Navigation->addCrumb($name);
			} else {
				$this->Navigation->addCrumb($this->contentHeader);
			}

			$this->set('mainPhone', $mainPhone);
			$this->set('mainEmail', $mainEmail);
			$this->set('roleStatus', $data['StaffStatus']['name']);
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function add() {
		$fields = $this->Staff->getFields();
		if (empty($this->request->data)) {
			$default = $this->Country->getDefaultValue();
			$fields['country_id']['default'] = $default;
		}
		if($this->request->is('post')) {
			$this->Staff->create();
			$this->request->data['Staff']['start_year'] = date('Y', strtotime($this->request->data['Staff']['start_date']));
			$this->request->data['Staff']['start_date'] = date('Y-m-d', strtotime($this->request->data['Staff']['start_date']));
			$this->request->data['SecurityUser']['date_of_birth'] = date('Y-m-d', strtotime($this->request->data['SecurityUser']['date_of_birth']));

			// file upload part...
			$this->request->data['SecurityUser']['file'] = $this->request->data['Staff']['photo_content'];
			unset($this->request->data['Staff']['photo_content']);

			if ($this->Staff->saveAll($this->request->data)) {
				$this->SecurityUser->SecurityUserType->create();
				$savedSecurityUserType = $this->SecurityUser->SecurityUserType->saveAll(
					array(
						'SecurityUserType' => array(
							'security_user_id' => $this->Staff->SecurityUser->getLastInsertId(),
							'type' => '5'
						)
					)
				);

				if ($this->request->data['SecurityUser']['file']['size']!=0) {
					$this->FileUploader->fileSizeLimit = 4 * 1024 * 1024;
					$this->FileUploader->fileModel = 'SecurityUser';
					$this->FileUploader->data = $this->request->data;
					$this->FileUploader->uploadFile($this->SecurityUser->getLastInsertId());
				}
				if (($this->request->data['SecurityUser']['file']['size']==0 || $this->FileUploader->success) && $savedSecurityUserType) {
					$this->Message->alert('general.add.success');
					return $this->redirect(array('action' => 'view', $this->Staff->getLastInsertId()));
				}
			} else {
				$this->Message->alert('general.add.failed');
			}
		}
		$this->Navigation->addCrumb($this->Message->getLabel('general.add'));
		$fields['openemisid']['default'] = $this->Staff->getUniqueID();
		$this->set('fields', $fields);
	}
	
	public function edit($id=0) {
		if ($this->Staff->exists($id)) {
			$fields = $this->Staff->getFields();
			$this->Staff->recursive = 0;
			$data = $this->Staff->getStaffData($id);
			$data['Staff']['start_date'] = date('d-m-Y', strtotime($data['Staff']['start_date']));
			$data['SecurityUser']['date_of_birth'] = date('d-m-Y', strtotime($data['SecurityUser']['date_of_birth']));
			
			$openemisid = $data['SecurityUser']['openemisid'];
			$name = $this->Message->getFullName($data) . ' (' . $openemisid . ')';
			
			if ($this->request->is(array('post', 'put'))) {
				$this->request->data['Staff']['start_year'] = date('Y', strtotime($this->request->data['Staff']['start_date']));
				$this->request->data['Staff']['start_date'] = date('Y-m-d', strtotime($this->request->data['Staff']['start_date']));
				$this->request->data['SecurityUser']['date_of_birth'] = date('Y-m-d', strtotime($this->request->data['SecurityUser']['date_of_birth']));
				$this->request->data['SecurityUser']['id'] = $this->request->data['Staff']['security_user_id'];

				// file upload part...
				$this->request->data['SecurityUser']['file'] = $this->request->data['Staff']['photo_content'];
				unset($this->request->data['Staff']['photo_content']);

				if ($this->Staff->saveAll($this->request->data)) {
					$this->FileUploader->fileSizeLimit = 4 * 1024 * 1024;
					$this->FileUploader->fileModel = 'SecurityUser';
					$this->FileUploader->data = $this->request->data;
					$this->FileUploader->allowNoFileUpload = true;
					$this->FileUploader->uploadFile();
					if ($this->FileUploader->success) {
						$this->Message->alert('general.edit.success');
						return $this->redirect(array('action' => 'view', $id));
					}// file upload error handled within component
				} else {
					$errors = $this->StaffCustomValue->validationErrors;
					$this->Message->alert('general.edit.failed');
				}
			} else {
				$this->request->data = $data;
			}
			$this->Navigation->addCrumb($name);
			$this->set('name', $name);
			$this->set('roleId', $id);
			$this->set('fields', $fields);
		}
	}

	public function delete($id) {
		$this->Staff->id = $id;
		if (!$id || !$this->Staff->exists()) {
			$this->Message->alert('general.view.notExists', array('type' => 'warn'));
			$this->redirect(array('action' => 'index'));
		}

		$this->request->onlyAllow('post', 'delete');
		if ($this->Staff->delete()) {
			$this->Message->alert('general.delete.success');
		} else {
			$this->Message->alert('general.delete.failed', array('type' => 'error'));
		}
		return $this->redirect(array('action' => 'guardian'));
	}
	
	public function fetchImage($id){
		$this->FileUploader->fileModel = 'SecurityUser';
		$this->FileUploader->fetchImage($id);
	}
}
