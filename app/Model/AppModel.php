<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('MessageComponent', 'Controller/Component');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	// ControllerAction properties
	public $Session;
	public $Message;
	public $Navigation;
	public $controller;
	public $request;
	public $action;
	public $fields;
	public $render = true;
	// End ControllerAction

	public function __construct() {
		parent::__construct();
		$this->Message = new MessageComponent(new ComponentCollection());
	}
	
	public function setField($field, $obj, $order=0) {
		$fields = $this->fields;
		if (empty($fields)) {
			$fields = $this->getFields();
		}
		$key = $field;
		if (array_key_exists($field, $fields)) {
			$key = $obj[$field]['model'] . '.' . $field;
		}
		$fields[$key] = $obj[$field];
		$fields[$key]['order'] = count($fields);
		$this->fields = $fields;
		$this->setFieldOrder($key, $order);
	}
	
	public function getFields($options=array()) {
		$fields = $this->schema();
		$belongsTo = $this->belongsTo;
		
		$i = 0;
		foreach($fields as $key => $obj) {
			$fields[$key]['order'] = $i++;
			$fields[$key]['visible'] = true;
			if (!array_key_exists('model', $fields[$key])) {
				$fields[$key]['model'] = $this->alias;
			}
		}
		
		$fields['id']['type'] = 'hidden';
		$defaultFields = array('modified_user_id', 'modified', 'created_user_id', 'created', 'order');
		foreach ($defaultFields as $field) {
			if (array_key_exists($field, $fields)) {
				if ($field == 'modified_user_id') {
					$fields[$field]['type'] = $field;
					$fields[$field]['dataModel'] = 'ModifiedUser';
				}
				if ($field == 'created_user_id') {
					$fields[$field]['type'] = $field;
					$fields[$field]['dataModel'] = 'CreatedUser';
				}
				$fields[$field]['visible'] = array('view' => true, 'edit' => false);
				$fields[$field]['labelKey'] = 'general';
			}
		}
		if (array_key_exists('name', $fields)) {
			$fields['name']['labelKey'] = 'general';
		}

		$this->fields = $fields;
		return $fields;
	}
	
	public function setFieldOrder($field, $order) {
		$fields = $this->fields;
		$found = false;
		foreach ($fields as $key => $obj) {
			if ($found && $key !== $field) {
				$fields[$key]['order'] = $fields[$key]['order'] + 1;
			} else {
				if ($field === $key) {
					$found = true;
					$fields[$key]['order'] = $order;
				} else if ($fields[$key]['order'] == $order) {
					$found = true;
					$fields[$key]['order'] = $order + 1;
				}
			}
		}		
		$fields[$field]['order'] = $order;
		uasort($fields, array($this->alias, 'sortFields'));
		$this->fields = $fields;
	}
	
	public static function sortFields($a, $b) {
		return $a['order'] >= $b['order'];
	}
	
	// Common Validation Functions
	public function fieldComparison($check1, $operator, $field2) {
        foreach($check1 as $key=>$value1) {
            $value2 = $this->data[$this->alias][$field2];
            if (!Validation::comparison($value1, $operator, $value2))
                return false;
        }
        return true;
    }
	
	public function compareDate($field = array(), $compareField = null) {
		$startDate = new DateTime(current($field));
		$endDate = new DateTime($this->data[$this->name][$compareField]);
		return $endDate > $startDate;
	}
	// End Common Validation Functions

	public function endsWith($haystack, $needle) {
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}

	public function getStatusOptions() {
		return array('1' => $this->Message->getLabel('general.active'), '0' => $this->Message->getLabel('general.inactive'));
	}

	public function getGenderOptions() {
		return array('M' => $this->Message->getLabel('general.male'), 'F' => $this->Message->getLabel('general.female'));
	}

	public function getYesnoOptions() {
		return array(0 => $this->Message->getLabel('general.no'), 1 => $this->Message->getLabel('general.yes'));
	}

	public function getStaffTypeOptions() {
		return array('0' => $this->Message->getLabel('staff.nonTeaching'), '1' => $this->Message->getLabel('staff.teaching'));
	}

	public function beforeSave($options = array()) {
		$userId = session_id() !== '' ? CakeSession::read('Auth.User.id') : 0;

		if(empty($this->data[$this->alias][$this->primaryKey])) {
			unset($this->data[$this->alias]['modified']);
			if(!is_null($userId) && !isset($this->data[$this->alias]['created_user_id'])) {
				$this->data[$this->alias]['created_user_id'] = $userId;
			}
		} else {
			if(!is_null($userId) && !isset($this->data[$this->alias]['modified_user_id'])) {
				$this->data[$this->alias]['modified_user_id'] = $userId;
			}
		}
		return true;
	}

	public function findOptions($options=array()) {
		$class = get_class($this);
		$conditions = !isset($options['conditions']) ? array() : $options['conditions'];
		$order = !isset($options['order']) ? array($class . '.order') : $options['order'];

		$list = $this->find('all', array(
			'recursive' => -1,
			'conditions' => $conditions,
			'order' => $order
		));
		return $list;
	}

	public function getOptions($value='name', $orderBy='order', $order='asc', $conditions=array()) {
		$value = sprintf('%s.%s', $this->alias, $value);
		$data = $this->find('list', array(
			'fields' => array($this->alias . '.id', $value),
			'conditions' => $conditions,
			'order' => array(sprintf('%s.%s %s', $this->alias, $orderBy, $order))
		));
		return $data;
	}

	public function checkDropdownData($check){
		$value = array_values($check);
		$value = $value[0];
		
		return !empty($value);
	}

	public function getErrorMessage($key) {
		return $this->Message->get('validation.' . get_class($this) . '.' . $key);
	}
}
