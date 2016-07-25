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

class ConfigItem extends AppModel {
	public $actsAs = array('ControllerAction');
	
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
	
	public $hasMany = array(
		'ConfigItemOption'
	);
	
	// any custom validation, add here
	public function beforeValidate($options = array()) {
		$name = $this->data[$this->alias]['name'];
		
		switch ($name) {
			default:
				break;
		}
		return parent::beforeValidate($options);
	}
	
	public function beforeAction() {
		parent::beforeAction();
		
		$this->fields['value_type']['visible'] = false;
		$this->fields['name']['visible'] = false;
		$this->fields['default_value']['visible'] = false;
		$this->fields['editable']['visible'] = false;
		$this->fields['order']['visible'] = false;
		$this->fields['visible']['visible'] = false;
		$this->fields['type']['type'] = 'disabled';
		$this->fields['label']['type'] = 'disabled';
		
		if ($this->action == 'edit') {
			$this->fields['description']['visible'] = false;
		}
		
		$this->setVar('portletHeader', $this->Message->getLabel($this->alias.'.title'));
		$this->setVar('header', $this->Message->getLabel($this->alias.'.title'));
		$this->Navigation->addCrumb($this->Message->getLabel($this->alias.'.title'));
	}
	
	public function index($selectedOption=0) {
		$options = $this->find('list', array(
			'fields' => array('type', 'type'),
			'group' => array('type'),
			'order' => array('type'),
			'conditions' => array($this->alias.'.visible' => '1')
		));
		
		if ($selectedOption === 0) {
			$selectedOption = key($options);
		}
		$data = $this->findAllByTypeAndVisible($selectedOption, 1, null, array('order'));
		foreach ($data as $i => $obj) {
			$items = $obj['ConfigItemOption'];
			if (!empty($items)) {
				$data[$i]['ConfigItemOption'] = array();
				foreach ($items as $item) {
					$data[$i]['ConfigItemOption'][$item['value']] = $item['name'];
				}
			}
		}
		if(empty($data)) $this->Message->alert('general.view.noRecords');
		$this->setVar(compact('data', 'options', 'selectedOption'));
	}
	
	public function view($id=0) {
		parent::view($id);
		$type = $this->controller->params->named['type'];
		$data = $this->controller->viewVars['data'];
		$valueType = $data[$this->alias]['value_type'];
		if (!empty($valueType)) {
			switch ($valueType) {
				case 'toggleVal':
					$this->fields['value']['type'] = 'toggleVal';
					break;

				case 'dropdown':
					$options = $this->ConfigItemOption->find('list', array(
						'fields' => array('value', 'name'),
						'conditions' => array('config_item_id' => $id),
						'order' => array('order')
					));
					$this->fields['value']['type'] = 'select';
					$this->fields['value']['options'] = $options;
					break;
				default:
					break;
			}
		}
		$this->setVar('type', $type);
		$this->render = 'view';
	}
	
	public function edit($id=0) {
		if ($this->exists($id)) {
			$this->recursive = 0;
			$data = $this->findById($id);
			$type = $this->controller->params->named['type'];
			
			if ($this->request->is(array('post', 'put'))) {
				// handling toggleVal valueType for configitem (eg auto id prefixes)
				if (array_key_exists('ConfigItem', $this->request->data)) {
					if (array_key_exists('enabled', $this->request->data['ConfigItem']) && array_key_exists('value', $this->request->data['ConfigItem'])) {
						$this->request->data['ConfigItem']['value'] = $this->request->data['ConfigItem']['enabled'].','.$this->request->data['ConfigItem']['value'];
					}	
				}	
				if ($this->save($this->request->data)) {
					if ($this->request->data['ConfigItem']['name'] == 'language') {
						$lang = $this->request->data['ConfigItem']['value'];
						$this->Session->write('System.language', $lang);
					}
					$this->Message->alert('general.edit.success');
					return $this->redirect(array('action' => get_class($this), 'view', $id, 'type' => $type));
				} else {
					$this->Message->alert('general.edit.failed');
				}
			} else {
				$this->request->data = $data;
			}
			$valueType = $this->request->data[$this->alias]['value_type'];
			
			if (!empty($valueType)) {
				switch ($valueType) {
					case 'dropdown':
						$options = $this->ConfigItemOption->find('list', array(
							'fields' => array('value', 'name'),
							'conditions' => array('config_item_id' => $id),
							'order' => array('order')
						));
						$this->setVar('options', $options);
						break;
					case 'time':
						$attr = array('showMeridian' => 'false');
						$this->setVar('attr', $attr);
						break;
						
					default:
						break;
				}
			}
			$this->setVar(compact('type', 'valueType'));
		} else {
			$this->Message->alert('general.view.notExists');
			return $this->redirect(array('action' => get_class($this)));
		}
	}
	
	public function getValue($name) {
		$value = $this->field('value', array('name' => $name));
		return (strlen($value)==0)? $this->getDefaultValue($name):$value;
	}

	public function getDefaultValue($name) {
		return $this->field('default_value', array('name' => $name));
	}
}
