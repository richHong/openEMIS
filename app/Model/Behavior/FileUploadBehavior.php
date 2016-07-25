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

class FileUploadBehavior extends ModelBehavior {
	public function setup(Model $model, $settings = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = array(
				'name' => 'file_name',
				'content' => 'file_content',
				'allowEmpty' => false,
				'size' => '1MB',
				'validate' => array(
					'extension' => array('gif', 'jpeg', 'png', 'jpg', 'doc', 'csv', 'xls', 'pdf', 'ppt')
				)
			);
		}
		
		$this->settings[$model->alias] = array_merge($this->settings[$model->alias], (array)$settings);
		
		$validate = array(
			'ruleExtension' => array(
				'rule' => array('extension', $this->settings[$model->alias]['validate']['extension']),
				'message' => __('Please upload a valid file type.')
			),
			'ruleFileSize' => array(
				'rule' => array('fileSize', '<=')
			)/*,
			'ruleUploadError_1' => array(
				'rule' => array('uploadError', UPLOAD_ERR_FORM_SIZE),
				'message' => 'File Size exceeded'
			)*/
		);
		
		if(!empty($this->settings[$model->alias])) {
			$fields = $this->settings[$model->alias];
			
			$fieldName = $fields['content'];
			if(!isset($model->validate[$fieldName])) {
				$size = $fields['size'];
				$validate['ruleFileSize']['rule'][] = $size;
				$validate['ruleFileSize']['message'] = __('File size must be less than ') . $size;
				$model->validate[$fieldName] = $validate;
			}
		}
	}
	
	public function beforeValidate(Model $model, $options = array()) {
		$fields = $this->settings[$model->alias];
		$alias = $model->alias;
		$fieldName = $fields['content'];
		if(!empty($model->data[$alias][$fieldName])) {
			$file = $model->data[$alias][$fieldName];
			if($file['error'] == 4 && $fields['allowEmpty'] == true) {
				unset($model->data[$alias][$fieldName]);
			}
		} else { // if the file is null, remove validation
			if (!empty($model->data[$model->alias][$fields['name']])) {
				unset($model->validate[$fieldName]);
				$model->data[$alias][$fields['name']] = null;
				$model->data[$alias][$fieldName] = null;
				return true;
			}
		}
		return parent::beforeValidate($model, $options);
	}
	
	public function beforeSave(Model $model, $options = array()) {
		$fields = $this->settings[$model->alias];
		$alias = $model->alias;
		$fieldName = $fields['content'];
		if(!empty($model->data[$alias][$fieldName])) {
			$file = $model->data[$alias][$fieldName];
			if($file['error'] == 0) {
				if(isset($fields['name'])) {
					$model->data[$alias][$fields['name']] = $file['name'];
				}
				$model->data[$alias][$fieldName] = file_get_contents($file['tmp_name']);
			}
		}
		return parent::beforeSave($model, $options);
	}
	
	public function download(Model $model, $id) {
		$model->recursive = -1;
		$data = $model->findById($id);
		
		$fields = $this->settings[$model->alias];
		$fileName = $data[$model->alias][$fields['name']];
		
		$fileInfo = explode('.', $fileName);
		$fileType = $fileInfo[count($fileInfo)-1];
		
		header('Content-type: ' . $fileType);
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		echo $data[$model->alias][$fields['content']];
		
		exit();
	}
}
