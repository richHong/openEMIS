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

class FileUploaderComponent extends Component {
	/*---------------------------------------------------------------------------
	* $fileSizeLimit : Set the file size limit in bytes
	* @var int 
	*----------------------------------------------------------------------------*/
	public $fileSizeLimit = 0;
	
	/*---------------------------------------------------------------------------
	* $fileModel : It is the name of the model that you want. 
	* @var string
	*----------------------------------------------------------------------------*/
	public $fileModel = 'ImageUpload';
	
	/*---------------------------------------------------------------------------
	* $fileVar : it is the name of the key to look in for an uploaded file
    * For this to work you will need to use the
	* 
	* - Single upload
    * $form-input('file', array('type'=>'file')); 
	* 
	* OR
	*
	* - Multiple uploads
	* $form-input('files.', array('type'=>'file' ,'multiple' ));  
	*
	* @var string
	*----------------------------------------------------------------------------*/
	public $fileVar = 'file'; 
	
	/*---------------------------------------------------------------------------
	* $uploadedFile : This will hold the uploadedFile array if there is one 
	* @var boolean|array 
	*----------------------------------------------------------------------------*/
	public $uploadedFile = false;
	
	/*---------------------------------------------------------------------------
	* $data and $param : Both are retriving from the controller
	*----------------------------------------------------------------------------*/
	public $data = array();
	public $param = array();
	
	/*---------------------------------------------------------------------------
	* $allowedTypes : The type of files that are accepted
	* @var array
	*----------------------------------------------------------------------------*/
	public $allowedTypes = array(
		'image/jpeg',
		'image/gif',
		'image/png',
		'image/pjpeg',
		'image/x-png'
	); 

	public $allowNoFileUpload = false;
	
	/*---------------------------------------------------------------------------
	* $success is to check whether the upload is completed or not
	* @return true/false
	*----------------------------------------------------------------------------*/
	public $success = false; 
	
	/*---------------------------------------------------------------------------
	* $dbPrefix : the name that will be used in the database column
	*----------------------------------------------------------------------------*/
	public $dbPrefix = 'photo';
 	
	public $components = array('Message');
	
	public function initialize(Controller $controller){
		$this->fileSizeLimit = 2 * 1024 * 1024;
		$this->data = $controller->data;
		$this->params = $controller->params; 
	}
	
	/*---------------------------------------------------------------------------
	* By calling uploadFile() at the controller, it will handle all the error 
	* checking plus upload it to the database
	* 
	*----------------------------------------------------------------------------*/
	public function uploadFile($id = NULL){
		if(!empty($this->data)){
			$this->uploadedFile = $this->_getUploadFileArr();
		//	pr($this->_getUploadFileArr());
		//	pr($this->data);
		//	$id = '';
			if(!empty($this->data[$this->fileModel]['id'])){
				$id = $this->data[$this->fileModel]['id'];
			}
			
			if($this->_checkFile() && $this->_checkType()){
				if ($this->allowNoFileUpload) {
					// if no file... and is not a remove action... remove from array so it will not be processed
					foreach ($this->uploadedFile as $key => $value) {
						if (array_key_exists('error', $value) && $value['error'] == UPLOAD_ERR_NO_FILE) {
							if (!array_key_exists('action', $value) || (array_key_exists('action', $value)&&$value['action']!='remove')) {
								unset($this->uploadedFile[$key]);
							}
						}
					}
				}
				$this->_processFile($id);
			}
			else{
				$this->success = false;	
			}
		}
	}
	
	/*---------------------------------------------------------------------------
	* By calling fetchImage() at the controller, it will load the image blob and 
	* display it according
	* 
	* $id : image id 
	*----------------------------------------------------------------------------*/
	public function fetchImage($id){
		$model =& $this->getModel(); 
		$controller->autoRender = false;
		$data = $model->findById($id);
		
		$fileExt = pathinfo($data[$this->fileModel]['photo_name'], PATHINFO_EXTENSION);
		
		if($fileExt == 'jpg'){
			$fileExt = 'jpeg';
		}
		
		header('Content-type: image/'.$fileExt);
		echo $data[$this->fileModel]['photo_content'];
	}
	
	/*---------------------------------------------------------------------------
	* By calling additionalFileType() at the controller, it will support more file types
	*----------------------------------------------------------------------------*/
	public function additionalFileType(){
		$this->allowedTypes[] = 'text/rtf';
		$this->allowedTypes[] = 'text/plain';
		$this->allowedTypes[] = 'application/pdf';
		$this->allowedTypes[] = 'application/vnd.ms-powerpoint';
		$this->allowedTypes[] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
		$this->allowedTypes[] = 'application/msword';
		$this->allowedTypes[] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
		$this->allowedTypes[] = 'application/vnd.ms-excel';
		$this->allowedTypes[] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$this->allowedTypes[] = 'application/zip';
	}
	
	public function downloadFile($id){
		$model =& $this->getModel(); 
		$model->recursive = -1;
		$data = $model->findById($id);
		//pr($data);
		$fileName = $data[$this->fileModel][$this->dbPrefix.'_name'];
		
		$fileInfo = explode('.',$data[$this->fileModel][$this->dbPrefix.'_name']);
		$fileType = $fileInfo[count($fileInfo) -1];
		
		//$fileName = str_replace('.'.$fileType, '', $data[$this->fileModel][$this->dbPrefix.'_name']);
		
		header('Content-type: ' . $fileType);
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		echo $data[$this->fileModel][$this->dbPrefix.'_content'];
		
		exit();
	}
	
	
	function &getModel() {
		$model = null;
		$name = $this->fileModel;
		
		if($name){
			$model = ClassRegistry::init($name);
			
			
			if (empty($model) && $this->fileModel) {
				return null;
			}
		}
		return $model;
    } 
	
	function _processFile($id = NULL){
		$model =& $this->getModel(); 
		$fileData = array();
		
		foreach($this->uploadedFile as $selectedFile){	
			$selectedData = array();
			if(!empty($id)){
				$selectedData['id'] = $id;
			}
			if (!$this->allowNoFileUpload || $selectedFile['tmp_name'] != '') {
				$selectedData[$this->dbPrefix.'_content'] = file_get_contents($selectedFile['tmp_name']);
				$selectedData[$this->dbPrefix.'_name'] = $selectedFile['name'];
			} else {
				$selectedData[$this->dbPrefix.'_content'] = "";
				$selectedData[$this->dbPrefix.'_name'] = "";
			}
			array_push($fileData, array($this->fileModel => $selectedData));
		}	
		
		if(!empty($fileData) && !empty($model)){
			if($model->saveAll($fileData,array('validate' => false))){
				$this->success = true;
				if(count($fileData) > 1){
					$this->Message->alertBox($this->Message->get('upload.success.multi'), array('type' => 'ok'));
				}
				else{
					$this->Message->alertBox($this->Message->get('upload.success.single'), array('type' => 'ok'));
				}
			}
			else{
				$this->success = false;
				$this->Message->alertBox($this->Message->get('upload.error.saving'), array('type' => 'error'));
			}
		}
		else{
			if ($this->allowNoFileUpload && empty($fileData)) {
				$this->success = true;
			} else {
				$this->success = false;
				$this->Message->alertBox($this->Message->get('upload.error.general'), array('type' => 'error'));
			}
		}
	}
	
	function _getUploadFileArr(){
		if(!empty($this->fileModel) && isset($this->data[$this->fileModel][$this->fileVar]) ){
			if($this->fileVar == 'files'){
				$fileArr = $this->data[$this->fileModel][$this->fileVar];
			}
			else{
				$fileArr[] = $this->data[$this->fileModel][$this->fileVar];
			}
		}
		else{
			$fileArr = false;
		}
		return $fileArr;
	}
	
	function _checkFile(){
		foreach($this->uploadedFile as $key => $selectedFile){
			if ($this->allowNoFileUpload && empty($selectedFile)) {
				$this->uploadedFile[$key] = array(
					'name' => '',
					'type' => '',
					'tmp_name' => '',
					'error' => '4',
					'size' => '0',
					'action' => 'remove'
				);
				$selectedFile = $this->uploadedFile[$key];
			}

			if($selectedFile['size'] > $this->fileSizeLimit){
				$message = $this->Message->get('upload.error.uploadSizeError');
				$this->Message->alertBox($message, array('type' => 'error'));
				return false;
			}
			else if($selectedFile['error'] == UPLOAD_ERR_OK){
				return true;
			}
			else if($selectedFile['error'] == UPLOAD_ERR_INI_SIZE){
				$message = $this->Message->get('upload.error.UPLOAD_ERR_INI_SIZE');
				$this->Message->alertBox($message, array('type' => 'error'));
				return false;
			}
			else if($selectedFile['error'] == UPLOAD_ERR_FORM_SIZE){
				$message = $this->Message->get('upload.error.UPLOAD_ERR_FORM_SIZE');
				$this->Message->alertBox($message, array('type' => 'error'));
				return false;
			}
			else if($selectedFile['error'] == UPLOAD_ERR_NO_FILE){
				if ($this->allowNoFileUpload) {
					return true;
				} else {
					$message = $this->Message->get('upload.error.UPLOAD_ERR_NO_FILE');
					$this->Message->alertBox($message, array('type' => 'error'));
					return false;
				}
			}
			else{
				$message = $this->Message->get('upload.error.general');
				$this->Message->alertBox($message, array('type' => 'error'));
				return false;
			}
			
		}
	}
	
	function _checkType(){
		foreach($this->uploadedFile as $selectedFile){
			$isSameFileType = false;
			
			foreach($this->allowedTypes as $fileType){
				if ($this->allowNoFileUpload && $selectedFile['type']=="") {
					$isSameFileType = true; // not the same.. just empty
					break;
				}
				if(strtolower($fileType) == strtolower($selectedFile['type']) && !$isSameFileType){
					$isSameFileType = true;
					break;
				}
			}
			
			if(!$isSameFileType){
				$message = $this->Message->get('upload.error.invalidFileFormat');
				$this->Message->alertBox($message, array('type' => 'error'));
				return false;
			}
		}
		
		return true;
	}
	
	function _convertByteToMegabyte(){
		return (($this->fileSizeLimit/1024)/1024)." MB";
	}
}

?>