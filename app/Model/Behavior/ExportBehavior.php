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

class ExportBehavior extends ModelBehavior {
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array();
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
		// if (!array_key_exists('module', $this->settings[$Model->alias])) {
		// 	pr('Please set a module for ExportBehavior');die;
		// }
	}

	public function reportGetTitle(Model $Model) {
		$title = null;
		$roleName = (array_key_exists('module', $this->settings[$Model->alias]))? $this->settings[$Model->alias]['module']: null;
		switch ($roleName) {
			case 'Student': case 'Staff': case 'Guardian': case 'SClass':
				if (isset($Model->Session)) {

					if ($Model->Session->check('Report.'.$roleName.'.reportHeader')) {
						$data = $Model->Session->read('Report.'.$roleName.'.reportHeader');
						$title = array();
						foreach ($data as $key => $value) {
							array_push($title, array($key,$value));
						}
					}
				}
				break;
			default:
				break;
		}
		return $title;
	}

	public function getFieldNamesFromFields(Model $Model, $fields) {
		App::import('helper', 'Label');
		$LabelHelper = new LabelHelper();
		$tArray = array();

		$ignoreType = array('image','hidden');
		foreach ($fields as $key => $value) {
			if (array_key_exists('type', $value)) {
				if (in_array($value['type'], $ignoreType)) continue;
			}
			if (array_key_exists('labelKey', $value)) {
				$prefix = $value['labelKey'];
			} else {
				$prefix = $value['model'];
			}
			$tArray[$value['model'].'.'.$key] = $Model->getFieldName($prefix,$key);
		}
		return $tArray;
	}

	public function getFieldNamesFromData(Model $Model, $rawData) {
		$fieldNames = array();
		if (!empty($rawData)) {
			foreach ($rawData as $key => $value) {
				$data['data'][$key] = array();
				foreach ($rawData[$key] as $key1 => $value1) {
					foreach ($rawData[$key][$key1] as $key2 => $value2) {
						if ($key == 0) {
							$fieldNames[$key1.'.'.$key2] = $Model->getFieldName($key1,$key2);
						}
					}
				}	
			}
		}
		return $fieldNames;
	}

	public function getFieldName(Model $Model,$modelName, $fieldName) {
		App::import('helper', 'Label');
		$LabelHelper = new LabelHelper();
		$thisLabel = '';
		$thisLabel = $LabelHelper->get($modelName.'.'.$fieldName);
		if ($thisLabel == '') {
			$thisLabel = Inflector::humanize($fieldName);
		}
		return $thisLabel;
	}

	public function handleOptionsInData(Model $Model,$fields,$data) {
		$optionKey = array();
		foreach ($fields as $key => $value) {
			if (array_key_exists('options', $value)) {
				$optionKey[$key] = $value['options'];
			}
		}
		foreach ($data as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				foreach ($value2 as $key3 => $value3) {
					if (in_array($key3, array('modified_user_id','created_user_id'))) {
						if (array_key_exists('ModifiedUser', $data[$key1])) {
							$data[$key1][$key2][$key3] = $data[$key1]['ModifiedUser']['first_name'].' '.$data[$key1]['ModifiedUser']['last_name'];
						}
						if (array_key_exists('CreatedUser', $data[$key1])) {
							$data[$key1][$key2][$key3] = $data[$key1]['CreatedUser']['first_name'].' '.$data[$key1]['CreatedUser']['last_name'];
						}
					}
					if (array_key_exists($key3, $optionKey)) {
						if (array_key_exists($value3, $optionKey[$key3])) {
							$data[$key1][$key2][$key3] = $optionKey[$key3][$value3];
						}
					}
					
				}
			}
		}
		return $data;
	}

	public function export(Model $Model, $return = false) {
		if (!method_exists($Model, 'reportGetFieldNames')) {
			die('reportGetFieldNames must be implemented.');
		}
		if (!method_exists($Model, 'reportGetData')) {
			die('reportGetData must be implemented.');
		}
		$data = array();
		// order is important because sometimes the data is needed to discover the fieldnames
		$data['data'] = $Model->reportGetData();
		$data['fieldNames'] = $Model->reportGetFieldNames();
		if (method_exists($Model, 'reportGetTitle')) {
			$data['title'] = $Model->reportGetTitle();
		} else {
			$data['title'] = $this->reportGetTitle($Model);
		}
		$this->exportCSV($Model,$data,$return);
	}

	public function exportCSV(Model $Model, $data=array(),$return = false) {
		$debug = 0;

		if (array_key_exists('fileName', $data)) {
			$fileName = $data['fileName'];
		}
		if (array_key_exists('fieldNames', $data)) {
			$fieldNames = $data['fieldNames'];
		}
		if (array_key_exists('data', $data)) {
			$fieldData = $data['data'];
		}
		if (array_key_exists('title', $data)) {
			$title = $data['title'];
		}

		if (!isset($fieldNames) || !isset($data)) {
			die('error: p');
		}

		if (!isset($fileName)) {
			$fileName = $Model->alias;
			$nowTime = time();
			$nowTime = date('Y-m-d H:i:s');
			$fileName .= ' - '.$nowTime;
		}

		if (!$debug) $view = new View($Model->controller);
		if (!$debug) $csv = $view->loadHelper('Csv');
		if (!$debug) $csv->newCsv($fileName . '.csv', $return);

		if (isset($title)) {
			foreach ($title as $key => $value) {
				if (!$debug) $csv->setRow($value);
			}
			if (!$debug) $csv->setRow(array(''));	
		}

		if (!$debug) $csv->setRow(array_values($fieldNames));

		$keys = array_keys($fieldNames);
		foreach ($keys as $i => $key) {
			$keys[$i] = explode('.', $key);
		}

		foreach ($fieldData as $data) {
			$row = array();
			foreach ($keys as $key) {
				$model = $key[0];
				$field = $key[1];
				$row[] = $data[$model][$field];
			}
			if (!$debug) $csv->setRow($row);
		}

		if($return){
			return $csv->get_file_contents();
		}
		if (!$debug) $csv->output();
		exit;

	}


}