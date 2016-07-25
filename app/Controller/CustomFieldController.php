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

class CustomFieldController extends AppController {

	public $uses = Array(
		'StudentCustomField',
		'StaffCustomField',
		'GuardianCustomField',
	);

	public $accessMapping = array(
		'indexEdit' => 'update',
		'reorder' => 'update'
	);

	public $moduleList = array(
		'1' => 'Student',
		'2' => 'Staff',
		// '3' => 'Guardian',
	);

	public $fieldTypes;

	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->Session->check('CustomField.moduleName')) {
			$customFieldName = $this->Session->read('CustomField.moduleName').'CustomField';
			$this->fieldTypes = $this->$customFieldName->getCustomFieldTypes();
		} else {
			$selectedOption = key($this->moduleList);
			$moduleName = $this->moduleList[$selectedOption];
			$customFieldName = $moduleName.'CustomField';
			$this->fieldTypes = $this->$customFieldName->getCustomFieldTypes();
		}

		$this->Navigation->addCrumb($this->Message->getLabel('admin.title'), array('controller' => 'Admin', 'action' => 'view'));
		$this->Navigation->addCrumb($this->Message->getLabel('CustomField.list'), array('controller' => 'CustomField', 'action' => 'index'));
		$this->set('tabElement', '../Admin/tabs');
		$this->set('contentHeader', $this->Message->getLabel('admin.title'));
		$this->set('portletHeader', $this->Message->getLabel('CustomField.list'));
		$this->set('moduleOptions',$this->moduleList);
	}

	public function index($selectedOption=1) {
		if (empty($selectedOption)) {
			$selectedOption = key($this->moduleList);
		}

		$moduleName = $this->moduleList[$selectedOption];
		$this->Session->write('CustomField.moduleName', $moduleName);
		$customFieldName = $moduleName.'CustomField';

		$data = $this->$customFieldName->find(
			'all',
			array(
				'recursive' => -1,
				'order' => 'order asc'
			)
		);

		foreach ($data as $key => $value) {
			$data[$key][$customFieldName]['typeName'] = $this->fieldTypes[$value[$customFieldName]['type']];
		}

		$this->Navigation->addCrumb($this->Message->getLabel($customFieldName.'.title'));

		if(empty($data)) $this->Message->alert('general.view.noRecords');
		
		$this->set('data',$data);
		$this->set('model',$customFieldName);
		$this->set('selectedOption',$selectedOption);
		$this->set('tabHeader', $this->Message->getLabel($customFieldName.'.title'));
	}

	public function view($id=0) {
		if ($this->Session->check('CustomField.moduleName')) {
			$customFieldName = $this->Session->read('CustomField.moduleName').'CustomField';
			if ($this->$customFieldName->exists($id)) {
				$fields = $this->$customFieldName->getFields();
				$this->$customFieldName->unbindModel(array('hasMany' => array($this->Session->read('CustomField.moduleName').'CustomValue')));
				$this->$customFieldName->recursive = 1	;
				$data = $this->$customFieldName->findById($id);

				$this->Navigation->addCrumb($this->Message->getLabel($customFieldName.'.title'));

				$this->set('data',$data);
				$customFieldOptionData = $data[$this->Session->read('CustomField.moduleName').'CustomFieldOption'];
				$this->set('customFieldOption',$customFieldOptionData);
				
				$this->set('fields',$fields);	
				$this->set('model',$customFieldName);
				$this->set('tabHeader', $this->Message->getLabel($customFieldName.'.title'));
			} else {
				$this->Message->alert('general.view.notExists');
				return $this->redirect(array('action' => 'index'));
			}
		} else {
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function edit($id=0, $additionalRows=0,$isDropdown=0) {
		if ($this->Session->check('CustomField.moduleName')) {
			$customFieldName = $this->Session->read('CustomField.moduleName').'CustomField';
			if ($this->$customFieldName->exists($id)) {
				$fields = $this->$customFieldName->getFields();
				$this->$customFieldName->unbindModel(array('hasMany' => array($this->Session->read('CustomField.moduleName').'CustomValue')));
				$this->$customFieldName->recursive = 1;
				$data = $this->$customFieldName->findById($id);

				$this->Navigation->addCrumb($this->Message->getLabel($customFieldName.'.title'));

				if ($this->request->is(array('post', 'put'))) {
					if ($this->$customFieldName->saveAll($this->request->data)) {
						$this->Message->alert('general.edit.success');
						return $this->redirect(array('action' => 'view', $id));
					} else {
						$this->Message->alert('general.edit.failed');
					}
				} else {
					$this->request->data = $data;
				}

				$this->set('data',$data);
				$customFieldOptionData = $data[$this->Session->read('CustomField.moduleName').'CustomFieldOption'];
				$customFieldOptionModelName = $this->Session->read('CustomField.moduleName').'CustomFieldOption';
				$this->set('customFieldOption',$customFieldOptionData);
				$this->set('customFieldOptionModel',$customFieldOptionModelName);

				$this->set('id',$id);
				$this->set('fields',$fields);	
				$this->set('model',$customFieldName);
				$this->set('moduleName',$this->Session->read('CustomField.moduleName'));
				$this->set('tabHeader', $this->Message->getLabel($customFieldName.'.title'));
				$this->set('additionalRows',$additionalRows);
				$this->set('isDropdown',$isDropdown);
			} else {
				$this->Message->alert('general.view.notExists');
				return $this->redirect(array('action' => 'index'));
			}
		} else {
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function add($additionalRows=0,$isDropdown=0) {
		if ($this->Session->check('CustomField.moduleName')) {
			$customFieldName = $this->Session->read('CustomField.moduleName').'CustomField';
			$fields = $this->$customFieldName->getFields();

			$this->Navigation->addCrumb($this->Message->getLabel($customFieldName.'.title'));
			if ($this->request->is(array('post', 'put'))) {	
				// need to find out the largest order... and that will be the order
				$maxOrderData = $this->$customFieldName->find(
					'first', 
					array(
						'recursive' => -1,	
						'fields' => array('MAX('.$customFieldName.'.order) as max_order')
					)
				);
				$currentOrder = 0;
				if (!empty($maxOrderData)) {
					$currentOrder = $maxOrderData[0]['max_order']+1;
				}
			    if (array_key_exists($customFieldName, $this->request->data)) {
			    	$this->request->data[$customFieldName]['order'] = $currentOrder;
			    }
				if ($this->$customFieldName->saveAll($this->request->data)) {
					$this->Message->alert('general.edit.success');
					return $this->redirect(array('action' => 'view', $this->$customFieldName->getLastInsertId()));
				} else {
					$this->Message->alert('general.edit.failed');
				}
			}

			$customFieldOptionModelName = $this->Session->read('CustomField.moduleName').'CustomFieldOption';
			$this->set('customFieldOptionModel',$customFieldOptionModelName);

			$this->set('fields',$fields);
			$this->set('model',$customFieldName);
			$this->set('tabHeader', $this->Message->getLabel($customFieldName.'.title'));
			$this->set('moduleName',$this->Session->read('CustomField.moduleName'));
			$this->set('additionalRows',$additionalRows);
			$this->set('isDropdown',$isDropdown);
		} else {
			return $this->redirect(array('action' => 'index'));
		}
	}

	public function reorder($selectedOption=1) {
		if ($this->Session->check('CustomField.moduleName')) {
			$customFieldName = $this->Session->read('CustomField.moduleName').'CustomField';
		} else {
			return $this->redirect(array('action' => 'index'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->request->data;
			$redirect = array('action' => 'indexEdit', $selectedOption);
			$this->$customFieldName->reorder($data, array());
			return $this->redirect($redirect);
		}
	}

	public function indexEdit($selectedOption=1) {
		if ($this->Session->check('CustomField.moduleName')) {
			$customFieldName = $this->Session->read('CustomField.moduleName').'CustomField';
			$data = $this->$customFieldName->find('all',
				array(
					'recursive' => -1, 
					'conditions' => array('visible' => '1'),
					'order' => 'order asc'
				)
			);

			$this->Navigation->addCrumb($this->Message->getLabel($customFieldName.'.title'));
			$this->set('data',$data);
			$this->set('model', $customFieldName);
			$this->set('selectedOption',$selectedOption);
			$this->set('tabHeader', $this->Message->getLabel($customFieldName.'.title'));
		}
		// $this->Navigation->addCrumb($header);

		// $options = $this->options;
		// $obj = $this->optionList[$selectedOption];
		// // $this->$customFieldName->setParent($obj['FieldOption']);
		// $model = $this->$customFieldName->getModel()->alias;
		// $header = $this->$customFieldName->getHeader();
		// $subOptions = $this->$customFieldName->getSubOptions();
		// $conditions = array();
		// if(!empty($subOptions)) {
		// 	$conditionId = $this->$customFieldName->getModel()->getConditionId();
		// 	$selectedSubOption = $this->$customFieldName->getFirstSubOptionKey($subOptions);
		// 	if(isset($this->request->params['named'][$conditionId])) {
		// 		$selectedSubOption = $this->request->params['named'][$conditionId];
		// 	}
		// 	$conditions[$conditionId] = $selectedSubOption;
		// 	$this->set(compact('selectedSubOption', 'conditionId'));
		// }
		// $data = $this->$customFieldName->getAllValues($conditions);
		// if($model === '$customFieldName') {
		// 	$conditions['field_option_id'] = $obj['FieldOption']['id'];
		// }
		// $this->set(compact('data', 'header', 'selectedOption', 'options', 'model', 'conditions'));
		// $this->Navigation->addCrumb($header);
	}

}