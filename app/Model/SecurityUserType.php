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

class SecurityUserType extends AppModel {
	public $types = array(
		'1' => 'Admin',
		'2' => 'Staff',
		'3' => 'Student',
		'4' => 'Guardian',
		'5' => 'Teacher'
	);
	
	public $belongsTo = array(
		'SecurityUser'
	);
	
	public function getType($view) {
		return $this->types[$view->params['controller']];
	}
	
	public function paginateJoins($joins, $params) {
		
		return $joins;
	}
	
	public function paginateConditions($params) {
		$conditions = array('OR' => array(), 'AND' => array());
		foreach($params as $model => $values) {
			if(is_array($values)) {
				foreach($values as $name => $val) {
					if(!empty($val)) {
						$key = $model.'.'.$name;
						if($this->endsWith($name, '_id')) {
							$conditions[$key] = $val;
						} else {
							$key .= ' LIKE';
							$conditions['OR'][$key] = '%' . $val . '%';
						}
					}
				}
			} else {
				$conditions['AND'][$model] = $values;
			}
		}
		return $conditions;
	}
	
	public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		$model = $this->name;
		$fields = array('SecurityUser.*');
		$joins = array();
		$data = $this->find('all', array(
			'recursive' => 0,
			'fields' => $fields,
			'joins' => $this->paginateJoins($joins, $conditions),
			'conditions' => $this->paginateConditions($conditions),
			'limit' => $limit,
			'offset' => (($page-1)*$limit),
			'group' => null,
			'order' => $order
		));
		return $data;
	}
	
	public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$joins = array();
		$count = $this->find('count', array(
			'recursive' => 0,
			'joins' => $this->paginateJoins($joins, $conditions),
			'conditions' => $this->paginateConditions($conditions)
		));
		return $count;
	}
}