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

define('ACCESS_VIEW_TYPE', 3);

class StudentsController extends AppController {
	public $uses = array(
		'EducationGrade',
		'EducationGradesSubject',
		'EducationSubject',
		'Student',
		'StudentStatus',
		'AssessmentResult',
		'AssessmentResultType',
		'SchoolYear',
		'ClassStudent',
		'ClassSubject',
		'ClassGrade',
		'SClass',
		'AssessmentItemResult',
		'Timetable',
		'TimetableEntry',
		'ContactType',
		'SecurityUser',
		'StudentAttachment',
		'StudentContact',
		'Country',
		'StudentFee',
		'StudentCustomField',
		'StudentCustomValue',
		'StudentReportCard',
	);
	public $helpers = array('Paginator', 'Utility', 'CustomField');
	public $components = array(
		'Paginator',
		'Schedule',
		'FileUploader',
		'Search',
		// 'Mpdf'
	);
	public $modules = array(
		'StudentBehaviour',
		'StudentContact',
		'StudentGuardian',
		'StudentFee',
		'attendance' => 'Attendance',
		'Timetable',
		'academic' => 'StudentAcademic',
		'StudentResult',
		'StudentAttachment',
		'StudentAttendanceDay',
		'StudentAttendanceLesson',
		'StudentPassword',
		'StudentIdentity',
		'StudentReportCard'
	);

	public $acoName = 'StudentProfile';
	public $contentHeader;
	public $accessViewType;
	public $securityType;

	public $accessMapping = array(
		'search' => 'read',
		'listing' => 'read',
		'download' => 'execute'
	);

	public function beforeFilter() {
		parent::beforeFilter();

		if ($this->Session->check('Security.securityType')) $this->securityType = $this->Session->read('Security.securityType');
		if ($this->Session->check('Security.accessViewType')) $this->accessViewType = $this->Session->read('Security.accessViewType');
		$this->contentHeader = $this->Message->getLabel('Student.title');
		if (isset($this->accessViewType) && $this->accessViewType == ACCESS_VIEW_TYPE) {
			$this->contentHeader = $this->Message->getLabel('user.myProfile') . ' (' . $this->Session->read('Security.accessViewTypeName') . ')';
		}
		$this->set('contentHeader', $this->contentHeader);
		
		$actions = array('add', 'index', 'view', 'search', 'edit', 'listing', 'export');
		
		if (!$this->request->is('ajax')) {
			if (isset($this->accessViewType) && $this->accessViewType != ACCESS_VIEW_TYPE) {
				$this->Navigation->addCrumb($this->Message->getLabel('student.title'), array('controller' => $this->params['controller'], 'action' => 'index'));
			}

			if (!in_array($this->action, $actions)) { // if the action is not one of the actions not requiring the id
				if (!$this->Session->check('Student.id')) { // if id not exists in session
					$this->Message->alert('general.view.notExists');
					return $this->redirect(array('action' => 'index'));
				} else {
					$id = $this->Session->read('Student.id');
					if (!$this->Student->exists($id)) {
						$this->Message->alert('general.view.notExists');
						return $this->redirect(array('action' => 'index'));
					} else {
						$data = array();
						$data['SecurityUser'] = array();
						$data['SecurityUser']['first_name'] = trim($this->Session->read('Student.data.SecurityUser.first_name'));
						$data['SecurityUser']['middle_name'] = trim($this->Session->read('Student.data.SecurityUser.middle_name'));
						$data['SecurityUser']['last_name'] = trim($this->Session->read('Student.data.SecurityUser.last_name'));
						$openemisid = trim($this->Session->read('Student.data.SecurityUser.openemisid'));

						$name = $this->Message->getFullName($data) . ' (' . $openemisid . ')';
						$this->Session->write('Report.Student.reportHeader', array(
							$this->Message->getLabel('general.name')=>$this->Message->getFullName($data),
							$this->Message->getLabel('SecurityUser.openemisid')=>$openemisid)
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
		}
		$this->set('title', $this->Message->getLabel('student.title')); 
		$this->set('model', 'Student');
		$this->set('tabElement', '../Students/tabs');
	}

	public function index() {
		$this->Navigation->addCrumb($this->Message->getLabel('general.search'));
		$statusOptions = $this->StudentStatus->getOptions();
		$this->set('statusOptions', $statusOptions);
	}

	public function listing() {
		$this->Search->clearSearchParams('Student');
		return $this->redirect(array('action' => 'search'));
	}

	public function search() {
		if ($this->accessViewType != ACCESS_VIEW_TYPE) {
			$this->Navigation->addCrumb($this->Message->getLabel('general.searchResults'));
			$data = $this->Search->search('Student', array('sortDefault' => 'SecurityUser.openemisid'));
			if(empty($data)) $this->Message->alert('general.view.noRecords');
			$this->set('data', $data);
		}
	}

	public function export() {
		$currentModel = 'Student';
		$this->$currentModel->export();
	}

	public function view($id=0) {
		if ($this->accessViewType != ACCESS_VIEW_TYPE) {
			if ($id == 0) {
				if ($this->Session->check('Student.id')) {
					$id = $this->Session->read('Student.id');
				} else {
					$this->Message->alert('general.view.notExists');
					return $this->redirect(array('action' => 'index'));
				}
			}
		} else {
			// is student
			$this->Student->recursive = 0;
			$id = $this->Student->getStudentIdBySecurityId(AuthComponent::user('id'));
		}
		if ($this->Student->exists($id)) {
			if ($this->securityType == 5) {
				$Staff = ClassRegistry::init('Staff');
				$studentsInClasses = $Staff->getClassStudentIdsByStaffId();
				if (!in_array($id, $studentsInClasses)) {
					return $this->redirect(array('action' => 'index'));
				}
			}

			$fields = $this->Student->getFields();
			$this->Student->recursive = 0;
			$data = $this->Student->getStudentData($id);
			$securityUserID = $data['SecurityUser']['id'];
			$this->Session->write('Student.data', $data);

			$mainPhone = $this->StudentContact->getPreferredPhone($securityUserID);
			$mainEmail = $this->StudentContact->getPreferredEmail($securityUserID);

			$this->Session->write('Student.id', $id);
			$this->Session->write('Student.data.mainPhone', $mainPhone);
			$this->Session->write('Student.data.mainEmail', $mainEmail);
			$this->Session->write('Student.data.status', $data['StudentStatus']['name']);

			$openemisid = $data['SecurityUser']['openemisid'];
			$name = $this->Message->getFullName($data) . ' (' . $openemisid . ')';
			
			$this->set('portletHeader', $name);
			$this->set('data', $data);
			$this->set('fields', $fields);

			if ($this->accessViewType != ACCESS_VIEW_TYPE) {
				$this->Navigation->addCrumb($name);
			} else {
				$this->Navigation->addCrumb($this->contentHeader);
			}
			$this->set('mainPhone', $mainPhone);
			$this->set('mainEmail', $mainEmail);
			$this->set('roleStatus', $data['StudentStatus']['name']);
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function add() {
		$moduleName = 'Student';
		$fields = $this->Student->getFields();
		if (empty($this->request->data)) {
			$default = $this->Country->getDefaultValue();
			$fields['country_id']['default'] = $default;
		}
		if ($this->request->is('post')) {
			$this->Student->create();

			$this->request->data['Student']['start_year'] = date('Y', strtotime($this->request->data['Student']['start_date']));
			$this->request->data['Student']['start_date'] = date('Y-m-d', strtotime($this->request->data['Student']['start_date']));

			$this->request->data['SecurityUser']['date_of_birth'] = date('Y-m-d', strtotime($this->request->data['SecurityUser']['date_of_birth']));
			// file upload part...
			$this->request->data['SecurityUser']['file'] = $this->request->data['Student']['photo_content'];
			unset($this->request->data['Student']['photo_content']);
			if ($this->Student->saveAll($this->request->data)) {
				$this->SecurityUser->SecurityUserType->create();
				$savedSecurityUserType = $this->SecurityUser->SecurityUserType->saveAll(
					array(
						'SecurityUserType' => array(
							'security_user_id' => $this->Student->SecurityUser->getLastInsertId(),
							'type' => '3'
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
					return $this->redirect(array('action' => 'view', $this->Student->getLastInsertId()));
				} else {
					$this->Message->alert('general.add.failed');
				}
			} else {
				$this->Message->alert('general.add.failed');
			}
		}
		$this->Navigation->addCrumb($this->Message->getLabel('general.add'));
		$fields['openemisid']['default'] = $this->Student->getUniqueID();
		$this->set('fields', $fields);
	}

	public function edit($id=0) {
		$moduleName = 'Student';
		if ($this->Student->exists($id)) {
			$fields = $this->Student->getFields();
			$this->Student->recursive = 0;
			$data = $this->Student->getStudentData($id);
			$data['Student']['start_date'] = date('d-m-Y', strtotime($data['Student']['start_date']));
			$data['SecurityUser']['date_of_birth'] = date('d-m-Y', strtotime($data['SecurityUser']['date_of_birth']));

			$openemisid = $data['SecurityUser']['openemisid'];
			$name = $this->Message->getFullName($data) . ' (' . $openemisid . ')';

			if ($this->request->is(array('post', 'put'))) {
				$this->request->data['Student']['start_year'] = date('Y', strtotime($this->request->data['Student']['start_date']));
				$this->request->data['Student']['start_date'] = date('Y-m-d', strtotime($this->request->data['Student']['start_date']));
				$this->request->data['SecurityUser']['date_of_birth'] = date('Y-m-d', strtotime($this->request->data['SecurityUser']['date_of_birth']));
				$this->request->data['SecurityUser']['id'] = $this->request->data['Student']['security_user_id'];

				// file upload part...
				$this->request->data['SecurityUser']['file'] = $this->request->data['Student']['photo_content'];
				unset($this->request->data['Student']['photo_content']);

				// pr($this->request->data);
				
				if ($this->Student->saveAll($this->request->data)) {
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
					$errors = $this->StudentCustomValue->validationErrors;
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
		$this->Student->id = $id;
		if (!$id || !$this->Student->exists()) {
			$this->Message->alert('general.view.notExists', array('type' => 'warn'));
			$this->redirect(array('action' => 'index'));
		}

		$this->request->onlyAllow('post', 'delete');
		if ($this->Student->delete()) {
			$this->Message->alert('general.delete.success');
		} else {
			$this->Message->alert('general.delete.failed', array('type' => 'error'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function fetchImage($id) {
		$this->FileUploader->fileModel = 'SecurityUser';
		$this->FileUploader->fetchImage($id);
	}
}