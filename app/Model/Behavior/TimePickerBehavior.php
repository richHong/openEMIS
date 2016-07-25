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

class TimePickerBehavior extends ModelBehavior {
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array();
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
	}
	
	public function beforeSave(Model $model, $options = array()) {
		$format = 'H:i:s';
		$fields = $this->settings[$model->alias];
		foreach($fields as $field) {
			if(isset($model->data[$model->alias][$field]) && !empty($model->data[$model->alias][$field])) {
				$time = $model->data[$model->alias][$field];
				$model->data[$model->alias][$field] = date($format, strtotime($time));
			}
		}
		return parent::beforeSave($model, $options);
	}
	
	public function afterFind(Model $model, $results, $primary = false) {
		$format = 'h:i A';
		$fields = $this->settings[$model->alias];
		foreach($results as $i => $result) {
			foreach($fields as $field) {
				if(isset($result[$model->alias][$field]) && !empty($result[$model->alias][$field])) {
					$time = $result[$model->alias][$field];
					$results[$i][$model->alias][$field] = date($format, strtotime($time));
				}
			}
		}
		return $results;
	}
}