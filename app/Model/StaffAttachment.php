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

class StaffAttachment extends AppModel {
	public $displayField = 'id';
	public $belongsTo = array(
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

		$this->Navigation->addCrumb($this->Message->getLabel('StaffAttachment.name'));
		$this->fields['staff_id']['type'] = 'hidden';
		$this->fields['staff_id']['value'] = $this->Session->read('Staff.id');
		$this->fields['description']['type'] = 'text';
		$this->fields['file_content']['type'] = 'file_upload';

		$this->setVar('tabHeader', $this->Message->getLabel('StaffAttachment.name'));
	}

	public function index() {
		$staffId = $this->Session->read('Staff.id');
		$this->recursive = 0;
		$data = $this->findAllByStaffId($staffId);
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

	public function attachment_download($params){
		$this->render = false;
		$id = empty($params)? NULL : $params;
		if(!empty($id)){
			$this->controller->FileUploader->fileModel = $this->name;
			$this->controller->FileUploader->dbPrefix = 'file';
			$this->controller->FileUploader->downloadFile($id);
		}
	}

	function setupAttachmentForms(){
		$id = $this->controller->Session->read('Staff.id');
		$this->controller->set('staffId', $id);
		$this->controller->set('modelName', $this->name);
		
		if($this->controller->request->is(array('post', 'put'))){
			$postData = $this->controller->request->data;
			if($this->save($postData)){
				if(!empty($postData[ $this->name]['file'])){ // with 'File' is add process
					$this->controller->FileUploader->fileSizeLimit = 2*1024*1024;
					$this->controller->FileUploader->fileModel = $this->name;
					$this->controller->FileUploader->dbPrefix = 'file';
					$this->controller->FileUploader->additionalFileType();
					$this->controller->FileUploader->uploadFile($this->id);
					
					if($this->controller->FileUploader->success){
						return $this->controller->redirect(array('action' => 'attachment'));
					}
				}
				else{
					$this->controller->Message->alert('general.edit.success');
					return $this->controller->redirect(array('action' => $this->name));
				}
			}
			else{
				if(!empty($postData[$this->name]['file'])){// with 'File' is add process
					$this->controller->Message->alert('general.add.failed');
				}
				else{
					$this->controller->Message->alert('general.edit.failed');
				}
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
				return $$this->controller->redirect(array('action' => $this->name));
			}
		}
		else{
			$this->controller->Message->alert('general.view.notExists', array('type' => 'warn'));
			return $this->controller->redirect(array('action' => $this->name));
		}
	}


	// public $indexPage = 'attachment';
	// public $module = 'Attachment';
	
	// public function getDisplayFields() {
	// 	$model = get_class($this);
	// 	$fields = array(
 //            'model' => $this->alias,
 //            'fields' => array(
 //                array('field' => 'name', 'model' => $model),
 //                array('field' => 'description', 'model' => $model),
 //                array('field' => 'file_name', 'model' => $model, 'label' => $this->Message->getLabel('general.file'), 'type' => 'download', 'action' => 'attachment_download'),
 //                array('field' => 'modified_by', 'model' => 'ModifiedUser'),
 //                array('field' => 'modified', 'model' => $model, 'label' => $this->Message->getLabel('general.modifiedOn')),
 //                array('field' => 'created_by', 'model' => 'CreatedUser'),
 //                array('field' => 'created', 'model' => $model, 'label' => $this->Message->getLabel('general.createdOn'))
 //            )
	// 	);
	// 	return $fields;
	// }

	// public function attachment($controller, $params){
	// 	$id = $controller->Session->read('Staff.id');
		
	// 	if(empty($id)){
	// 		return $controller->redirect(array('action' => 'index'));
	// 	}
		
	// 	$this->recursive = -1;
	// 	$data = $this->find('all', array('conditions'=> array('staff_id' => $id)));
		
	// 	$controller->set('data', $data);
	// 	$controller->set('modelName', $this->name);
	// }
	
	// public function attachment_add($controller, $params){
	// 	$this->setupAttachmentForms($controller, $params);
	// }
	
	// public function attachment_edit($controller, $params){
	// 	$this->setupAttachmentForms($controller, $params);
		
	// 	$id = !empty($params['pass'][0])?$params['pass'][0]: NULL;
		
	// 	if(!empty($id)){
	// 		$this->recursive = -1;
	// 		$data = $this->find('first', array('conditions'=> array('id' => $id)));
	// 		$controller->request->data = $data;
			
	// 	}
	// 	else{
	// 		$controller->Message->alert('general.view.notExists', array('type' => 'warn'));
	// 		return $controller->redirect(array('action' => 'attachment'));
	// 	}
		
	// }
	
	// public function attachment_view($controller, $params){
	// 	$id = !empty($params['pass'][0])?$params['pass'][0]: NULL;
		
	// 	if(!empty($id)){
	// 		$this->recursive = 0;
	// 		$data = $this->findById($id);
	// 		$controller->set('id', $id);
	// 		$controller->set('data', $data);
	// 		$controller->set('fields', $this->getDisplayFields());
	// 	}
	// 	else{
	// 		$controller->Message->alert('general.view.notExists', array('type' => 'warn'));
	// 		return $controller->redirect(array('action' => 'attachment'));
	// 	}
		
	// }
	
	
	
	// public function attachment_download($controller, $params){
	// 	$this->render = false;
	// 	$id = empty($params['pass'][0])? NULL : $params['pass'][0];
		
		
	// 	if(!empty($id)){
	// 		$controller->FileUploader->fileModel = $this->name;
	// 		$controller->FileUploader->dbPrefix = 'file';
	// 		$controller->FileUploader->downloadFile($id);
	// 	}
	// }
	
	
}
