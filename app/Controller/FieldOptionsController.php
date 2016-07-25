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

class FieldOptionsController extends AppController {
	public $uses = Array(
		'FieldOption',
		'FieldOptionValue'
	);

	public $accessMapping = array(
		'indexEdit' => 'update',
		'reorder' => 'update'
	);
	
	public $optionList = array();
	public $options = array();
	
	public function beforeFilter() {
		parent::beforeFilter();

		// $this->Navigation->addCrumb($this->Message->getLabel('admin.title'), array('controller' => $this->params['controller'], 'action' => 'view'));
		// $this->Navigation->addCrumb($this->Message->getLabel('FieldOption.title'), array('controller' => 'FieldOptions', 'action' => 'index'));
		$this->Navigation->addCrumb($this->Message->getLabel('admin.title'), array('controller' => 'Admin', 'action' => 'view'));
		$this->Navigation->addCrumb('Field Options', array('controller' => 'FieldOptions', 'action' => 'index'));
		$this->optionList = $this->FieldOption->findOptions(true);
		// change the index to start from 1
		array_unshift($this->optionList, array());
		unset($this->optionList[0]);
		$this->options = $this->buildOptions($this->optionList);
		$this->set('tabElement', '../Admin/tabs');
		$this->set('contentHeader', $this->Message->getLabel('admin.title'));
		$this->set('portletHeader', $this->Message->getLabel('FieldOption.title'));
	}
	
	private function buildOptions($list) {
		$options = array();
		foreach($list as $key => $values) {
			$key = $key;
			if(!empty($values['FieldOption']['parent'])) {
				$parent = __($values['FieldOption']['parent']);
				if(!array_key_exists($parent, $options)) {
					$options[$parent] = array();
				}
				$options[$parent][$key] = __($values['FieldOption']['name']);
			} else {
				$options[$key] = __($values['FieldOption']['name']);
			}
		}
		return $options;
	}
	
	public function index($selectedOption=1) {
		if(!array_key_exists($selectedOption, $this->optionList)) {
			$selectedOption = 1;
		}
		$options = $this->options;
		$obj = $this->optionList[$selectedOption];
		$this->FieldOptionValue->setParent($obj['FieldOption']);
		$model = $this->FieldOptionValue->getModel()->alias;
		$header = $this->FieldOptionValue->getHeader();
		$subOptions = $this->FieldOptionValue->getSubOptions();
		$conditions = array();
		if(!empty($subOptions)) {
			$conditionId = $this->FieldOptionValue->getModel()->getConditionId();
			$selectedSubOption = $this->FieldOptionValue->getFirstSubOptionKey($subOptions);
			if(isset($this->request->params['named'][$conditionId])) {
				$selectedSubOption = $this->request->params['named'][$conditionId];
			}
			$conditions[$conditionId] = $selectedSubOption;
			$this->set(compact('subOptions', 'selectedSubOption', 'conditionId'));
		}

		$data = $this->FieldOptionValue->getAllValues($conditions);
		$fields = $this->FieldOptionValue->getValueFields();
		if(empty($data)) $this->Message->alert('general.view.noRecords');
		$this->set(compact('data', 'header', 'selectedOption', 'options', 'model', 'fields'));
		$this->Navigation->addCrumb($header);
	}
	
	public function indexEdit($selectedOption=1) {
		if(!array_key_exists($selectedOption, $this->optionList)) {
			$selectedOption = 1;
		}
		$options = $this->options;
		$obj = $this->optionList[$selectedOption];
		$this->FieldOptionValue->setParent($obj['FieldOption']);
		$model = $this->FieldOptionValue->getModel()->alias;
		$header = $this->FieldOptionValue->getHeader();
		$subOptions = $this->FieldOptionValue->getSubOptions();
		$conditions = array();
		if(!empty($subOptions)) {
			$conditionId = $this->FieldOptionValue->getModel()->getConditionId();
			$selectedSubOption = $this->FieldOptionValue->getFirstSubOptionKey($subOptions);
			if(isset($this->request->params['named'][$conditionId])) {
				$selectedSubOption = $this->request->params['named'][$conditionId];
			}
			$conditions[$conditionId] = $selectedSubOption;
			$this->set(compact('selectedSubOption', 'conditionId'));
		}
		$data = $this->FieldOptionValue->getAllValues($conditions);
		if($model === 'FieldOptionValue') {
			$conditions['field_option_id'] = $obj['FieldOption']['id'];
		}
		$this->set(compact('data', 'header', 'selectedOption', 'options', 'model', 'conditions'));
		$this->Navigation->addCrumb($header);
	}
	
	public function reorder ($selectedOption=1) {
		if ($this->request->is('post') || $this->request->is('put')) {
			$obj = $this->optionList[$selectedOption];
			$this->FieldOptionValue->setParent($obj['FieldOption']);
			$data = $this->request->data;
			$model = $this->FieldOptionValue->getModel();
			$conditions = array();
			$redirect = array('action' => 'indexEdit', $selectedOption);
			
			if(!empty($this->request->params['named'])) {
				$conditionId = key($this->request->params['named']);
				$selectedSubOption = current($this->request->params['named']);
				$conditions[$conditionId] = $selectedSubOption;
				$redirect = array_merge($redirect, $conditions);
			}
			$model->reorder($data, $conditions);
			return $this->redirect($redirect);
		}
	}
	
	public function add($selectedOption=1) {
		if(!array_key_exists($selectedOption, $this->optionList)) {
			$selectedOption = 1;
		}
		
		$obj = $this->optionList[$selectedOption];
		$this->FieldOptionValue->setParent($obj['FieldOption']);
		$header = $this->FieldOptionValue->getHeader();
		$fields = $this->FieldOptionValue->getValueFields();
		$model = $this->FieldOptionValue->getModel();
		$selectedSubOption = false;
		$conditionId = false;
		
		// get suboption value from index page and set it as the default option
		if(!empty($this->request->params['named'])) {
			$conditionId = key($this->request->params['named']);
			$selectedSubOption = current($this->request->params['named']);
			$this->set(compact('conditionId', 'selectedSubOption'));
			foreach($fields['fields'] as $key => $obj) {
				if($obj['FieldOption']['field']==$conditionId) {
					$fields['fields'][$key]['default'] = $selectedSubOption;
				}
			}
		}
		
		if($this->request->is(array('post', 'put'))) {
			$dateformat = 'Y-m-d';
			foreach ($fields as $key => $value) {
				if ($value['type'] == 'date') {
					if (array_key_exists($key, $this->request->data[$model->alias])) {
						$this->request->data[$model->alias][$key] = date($dateformat, strtotime($this->request->data[$model->alias][$key]));
					}
				}
			}
			if($this->FieldOptionValue->saveValue($this->request->data)) {
				$redirect = array('action' => 'index', $selectedOption);
				if($conditionId !== false) {
					$redirect = array_merge($redirect, array($conditionId => $this->request->data[$model->alias][$conditionId]));
				}
				$this->Message->alert('general.add.success');
				return $this->redirect($redirect);
			} else {
				$this->Message->alert('general.add.failed');
			}
			$dateformat = 'd-m-Y';
			foreach ($fields as $key => $value) {
				if ($value['type'] == 'date') {
					if (array_key_exists($key, $this->request->data[$model->alias])) {
						$this->request->data[$model->alias][$key] = date($dateformat, strtotime($this->request->data[$model->alias][$key]));
					}
				}
			}
		}

		$this->set('model', $model->alias);
		$this->set(compact('header', 'fields', 'selectedOption'));
		$this->Navigation->addCrumb($header);
	}
	
	public function view($selectedOption=1, $selectedValue=0) {
		if(!array_key_exists($selectedOption, $this->optionList)) {
			$selectedOption = 1;
		}
		$obj = $this->optionList[$selectedOption];
		$this->FieldOptionValue->setParent($obj['FieldOption']);
		$data = $this->FieldOptionValue->getValue($selectedValue);
		$selectedSubOption = false;
		$conditionId = false;
		
		if(!empty($this->request->params['named'])) {
			$conditionId = key($this->request->params['named']);
			$selectedSubOption = current($this->request->params['named']);
			$this->set(compact('conditionId', 'selectedSubOption'));
		}
		
		if(empty($data)) {
			$this->Message->alert('general.notExists');
			return $this->redirect(array('action' => 'index', $selectedOption));
		}
		
		$editable = true;
		$model = $this->FieldOptionValue->getModel();
		if (array_key_exists('editable', $data[$model->alias])) {
			$editable = $data[$model->alias]['editable'];
		}
		$header = $this->FieldOptionValue->getHeader();
		$fields = $this->FieldOptionValue->getValueFields();
		$this->set(compact('data', 'header', 'fields', 'selectedOption', 'selectedValue', 'editable'));
		$this->Navigation->addCrumb($header);
	}
	
	public function edit($selectedOption=1, $selectedValue=0) {
		if($selectedValue == 0) {
			$this->Message->alert('general.notExists');
			return $this->redirect(array('action' => 'index', $selectedOption));
		}
		if(!array_key_exists($selectedOption, $this->optionList)) {
			$selectedOption = 1;
		}
		$obj = $this->optionList[$selectedOption];
		$this->FieldOptionValue->setParent($obj['FieldOption']);
		$model = $this->FieldOptionValue->getModel();
		$selectedSubOption = false;
		$conditionId = false;
		
		if(!empty($this->request->params['named'])) {
			$conditionId = key($this->request->params['named']);
			$selectedSubOption = current($this->request->params['named']);
			$this->set(compact('conditionId', 'selectedSubOption'));
		}

		$fields = $this->FieldOptionValue->getValueFields();

		if($this->request->is(array('post', 'put'))) {
			$dateformat = 'Y-m-d';
			foreach ($fields as $key => $value) {
				if ($value['type'] == 'date') {
					if (array_key_exists($key, $this->request->data[$model->alias])) {
						$this->request->data[$model->alias][$key] = date($dateformat, strtotime($this->request->data[$model->alias][$key]));
					}
				}
			}
			if($this->FieldOptionValue->saveValue($this->request->data)) {
				$redirect = array('action' => 'view', $selectedOption, $selectedValue);
				if($conditionId !== false) {
					$redirect = array_merge($redirect, array($conditionId => $this->request->data[$model->alias][$conditionId]));
				}
				$this->Message->alert('general.edit.success');
				return $this->redirect($redirect);
			} else {
				$this->Message->alert('general.edit.failed');
			}
		} else {
			$data = $this->FieldOptionValue->getValue($selectedValue);
			$editable = true;
			if (array_key_exists('editable', $data[$model->alias])) {
				$editable = $data[$model->alias]['editable'];
			}
			if (!$editable) {
				$this->Message->alert('general.notEditable');
				return $this->redirect(array('action' => 'index', $selectedOption));
			}
			$this->request->data = $data;
		}
		$header = $this->FieldOptionValue->getHeader();

		$dateformat = 'd-m-Y';
		foreach ($fields as $key => $value) {
			if ($value['type'] == 'date') {
				if (array_key_exists($key, $this->request->data[$model->alias])) {
					$this->request->data[$model->alias][$key] = date($dateformat, strtotime($this->request->data[$model->alias][$key]));
				}
			}
		}

		$this->set('model', $model->alias);
		$this->set(compact('header', 'fields', 'selectedOption', 'selectedValue'));
		$this->Navigation->addCrumb($header);
	}
} 
