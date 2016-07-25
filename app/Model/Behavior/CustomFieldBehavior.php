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

class CustomFieldBehavior extends ModelBehavior {
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array();
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
		if (!array_key_exists('module', $this->settings[$Model->alias])) {
			pr('Please set a module for CustomFieldBehavior');die;
		}
	}

	public function getCustomFields(Model $model, $params=array()) {
		$module = $this->settings[$model->alias]['module'];
		$fieldModel = $module . 'CustomField';
		$valueModel = $module . 'CustomValue';
		$fieldOptionModel = $module . 'CustomFieldOption';
		$conditions = array();
		$key = $valueModel . '.' . Inflector::underscore($module.'Id');
		// $conditions[$key] = $controller->Session->read($module . '.id');

		$model->unbindModel(array('hasMany' => array($valueModel), 'belongsTo' => array('ModifiedUser','CreatedUser')));
		$data = $model->find('all', array('conditions' => array($model->alias . '.visible' => 1), 'order' => $model->alias . '.order'));

		foreach ($data as $key => $value) {
			if (array_key_exists($fieldOptionModel, $value)) {
				if (!empty($value[$fieldOptionModel])) {
					$data[$key][$fieldModel]['options']=array();
					foreach ($value[$fieldOptionModel] as $key2 => $value2) {
						$data[$key][$fieldModel]['options'][$value2['id']] = $value2['value'];
					}
				}
				unset($data[$key][$fieldOptionModel]);
			}
		}
		return $data;
	}

	public function getCustomFieldTypes(Model $Model) {
		$types = array(
			1 => $Model->Message->getLabel('CustomField.text'),
			2 => $Model->Message->getLabel('CustomField.textArea'),
			3 => $Model->Message->getLabel('CustomField.number'),
			4 => $Model->Message->getLabel('CustomField.dropdown'),
			// 5 => __('Textarea')
		);
		return $types;
	}

	public function hasIsUnique(Model $Model, $fieldType) {
		switch ($fieldType) {
			case '1': case '3':
				return true;
				break;
			
			default:
				return false;
				break;
		}
	}

	public function getCustomFieldValues(Model $model, $options=array()) {
		$module = $this->settings[$model->alias]['module'];
		$valueModel = $module . 'CustomValue';
		$conditions = array();
		$key = $valueModel . '.' . Inflector::underscore($module.'Id');

		if (array_key_exists('id', $options)) {
			$conditions[$key] = $options['id'];
		}
		$model->unbindModel(
			array(
				'belongsTo' => array('ModifiedUser','CreatedUser')
			)
		);
		$model->bindModel(
			array(
				'hasMany' => array(
					$valueModel => array(
						'conditions' => $conditions
					)
				)
			)
		);
		$valuesData = $model->find('all', array(
				'order' => $model->alias . '.order'
			)
		);

		$processedData = array();
		foreach ($valuesData as $key => $value) {
			if (!empty($value[$valueModel])) {
				array_push($processedData, $value[$valueModel][0]);
			} else {
				array_push($processedData, array());
			}
		}

		return $processedData;
	}

	public function getValueNameFromType(Model $Model, $thisType) {
		switch ($thisType) {
			case '1':
				$valueName = 'text_value';
				break;

			case '2':
				$valueName = 'textarea_value';
				break;

			case '3': case '4':
				$valueName = 'int_value';
				break;
			
			default:
				$valueName = null;
				break;
		}
		return $valueName;
	}

	public function reorder(Model $model, $data, $conditions=array()) {
		$id = $data[$model->alias]['id'];
		$idField = $model->alias . '.id';
		$orderField = $model->alias . '.order';
		$move = $data[$model->alias]['move'];
		$order = $model->field('order', array('id' => $id));
		$idConditions = array_merge(array($idField => $id), $conditions);
		$updateConditions = array_merge(array($idField . ' <>' => $id), $conditions);
		
		$this->fixOrder($model, $conditions);
		if($move === 'up') {
			$model->updateAll(array($orderField => $order-1), $idConditions);
			$updateConditions[$orderField] = $order-1;
			$model->updateAll(array($orderField => $order), $updateConditions);
		} else if($move === 'down') {
			$model->updateAll(array($orderField => $order+1), $idConditions);
			$updateConditions[$orderField] = $order+1;
			$model->updateAll(array($orderField => $order), $updateConditions);
		} else if($move === 'first') {
			$model->updateAll(array($orderField => 1), $idConditions);
			$updateConditions[$orderField . ' <'] = $order;
			$model->updateAll(array($orderField => $orderField . ' + 1'), $updateConditions);
		} else if($move === 'last') {
			$count = $model->find('count', array('conditions' => $conditions));
			$model->updateAll(array($orderField => $count), $idConditions);
			$updateConditions[$orderField . ' >'] = $order;
			$model->updateAll(array($orderField => $orderField . ' - 1'), $updateConditions);
		}
	}
	
	public function fixOrder(Model $model, $conditions) {
		$count = $model->find('count', array('conditions' => $conditions));
		if($count > 0) {
			$list = $model->find('list', array(
				'conditions' => $conditions,
				'order' => array($model->alias . '.order')
			));
			$order = 1;
			foreach($list as $id => $name) {
				$model->id = $id;
				$model->saveField('order', $order++);
			}
		}
	}
	
	public function additionalEdit(Model $model, $controller, $params) {
		// $module = $this->settings[$model->alias]['module'];
		// $fieldModel = $module . 'CustomField';
		// $optionModel = $module . 'CustomFieldOption';
		// $valueModel = $module . 'CustomValue';
		// $key = Inflector::underscore($module.'Id');
		// $keyValue = $controller->Session->read($module . '.id');
		// $fieldKey = Inflector::underscore($fieldModel.'Id');
		
		// if ($controller->request->is('post')) { 
		// 	$arrFields = array('textbox', 'dropdown', 'checkbox', 'textarea');
			
		// 	// Note to Preserve the Primary Key to avoid exhausting the max PK limit
		// 	foreach ($arrFields as $fieldVal) {
		// 		if (!isset($controller->request->data[$valueModel][$fieldVal])) continue;
				
		// 		foreach ($controller->request->data[$valueModel][$fieldVal] as $id => $val) {

		// 			if ($fieldVal == "checkbox") {
		// 				if (count($val['value'])==0) {
		// 					$controller->Message->alert('general.error');
		// 					$error = true;
		// 					break;
		// 				}
						
		// 				$arrCustomValues = $model->{$valueModel}->find('list', array(
		// 					'fields' => array('value'),
		// 					'conditions' => array(
		// 						$valueModel . '.' . $key => $keyValue, 
		// 						$valueModel . '.' . $fieldKey => $id
		// 					)
		// 				));

		// 				$tmp = array();
		// 				if (count($arrCustomValues) > count($val['value'])) //if db has greater value than answer, remove
		// 					foreach ($arrCustomValues as $pk => $intVal) {
		// 						if (!in_array($intVal, $val['value'])) {
		// 							//echo "not in db so remove \n";
		// 							$model->{$valueModel}->delete($pk);
		// 						}
		// 					}
		// 				$ctr = 0;
		// 				if (count($arrCustomValues) < count($val['value'])) //if answer has greater value than db, insert
		// 					foreach ($val['value'] as $intVal) {
		// 						if (!in_array($intVal, $arrCustomValues)) {
		// 							$model->{$valueModel}->create();
		// 							$arrV['value'] = $val['value'][$ctr];
		// 							$arrV[$fieldKey] = $id;
		// 							$arrV[$key] = $keyValue;
		// 							$model->{$valueModel}->save($arrV);
		// 							unset($arrCustomValues[$ctr]);
		// 						}
		// 						$ctr++;
		// 					}
		// 			} else { // if editing reuse the Primary KEY; so just update the record
		// 				$datafields = $model->{$valueModel}->find('first', array(
		// 					'fields' => array('id', 'value'), 
		// 					'conditions' => array(
		// 						$valueModel . '.' . $key => $keyValue, 
		// 						$valueModel . '.' . $fieldKey => $id
		// 					)
		// 				));
						
		// 				if ($datafields) {
		// 					$arrV['id'] = $datafields[$valueModel]['id'];
		// 				} else {
		// 					$model->{$valueModel}->create();
		// 				}
						
		// 				$arrV['value'] = $val['value'];
		// 				$arrV[$fieldKey] = $id;
		// 				$arrV[$key] = $keyValue;
						
		// 				if ($model->{$valueModel}->save($arrV)) {
		// 					$controller->Message->alert('general.edit.success');
		// 				} else {
		// 					$controller->Message->alert('general.error');
		// 				}
		// 			}
		// 		}
		// 	}
		// }

		// $model->bindModel(array(
		// 	'hasMany' => array(
		// 		$optionModel => array(
		// 			'conditions' => array($optionModel . '.visible' => 1),
		// 			'order' => array($optionModel . '.order' => "ASC")
		// 		)
		// 	)
		// ));
		// $model->unbindModel(array('hasMany' => array($valueModel)));
		// $data = $model->find('all', array('conditions' => array($fieldModel . '.visible' => 1), 'order' => $fieldModel . '.order'));
		// $dataValues = $model->{$valueModel}->find('all', array('conditions' => array($valueModel . '.' . $key => $keyValue)));
		// $tmp = array();
		// foreach ($dataValues as $arrV) {
		// 	$tmp[$arrV[$model->alias]['id']][] = $arrV[$valueModel];
		// }
		// $dataValues = $tmp;
		// $controller->set('data', $data);
		// $controller->set('dataValues', $tmp);
	}
	
	public function getRender(Model $model, $controller) {
		// $views = array();
		// $parentId = Inflector::underscore($model->alias) . '_id';
		// $modelOption = $model->alias . 'Option';
		// if ($controller->action == 'view') {
		// 	$data = $controller->viewVars['data'];
		// 	$id = $data[$model->alias]['id'];
		// 	$options = $model->{$modelOption}->find('all', array(
		// 		'conditions' => array($parentId => $id),
		// 		'order' => array("$modelOption.visible" => 'DESC', "$modelOption.order")
		// 	));
		// 	foreach ($options as $obj) {
		// 		$data[$modelOption][] = $obj[$modelOption];
		// 	}
		// 	$controller->set('data', $data);
		// } else if ($controller->action == 'edit') {
		// 	if ($controller->request->is('get')) {
		// 		$data = $controller->request->data;
		// 		$id = $data[$model->alias]['id'];
				
		// 		$options = $model->{$modelOption}->find('all', array(
		// 			'conditions' => array($parentId => $id),
		// 			'order' => array("$modelOption.visible" => 'DESC', "$modelOption.order")
		// 		));
		// 		foreach ($options as $obj) {
		// 			$controller->request->data[$modelOption][] = $obj[$modelOption];
		// 		}
		// 	}
		// }
		
		// return $views;
	}
	
	// public function postAdd(Model $model, $controller) {
	// 	$selectedOption = $controller->params->pass[0];
	// 	$modelOption = $model->alias . 'Option';
	// 	if (isset($controller->request->data['submit'])) {
	// 		$submit = $controller->request->data['submit'];
			
	// 		switch ($submit) {
	// 			case $modelOption:
	// 				//	
	// 				$obj = array('value' => '');
	// 				if (!isset($controller->request->data[$submit])) {
	// 					$controller->request->data[$submit] = array();
	// 				}
					
	// 				$obj['order'] = count($controller->request->data[$submit]);
	// 				$controller->request->data[$submit][] = $obj;
	// 				break;
					
	// 			case 'Save':
	// 				$data = $controller->request->data;
					
	// 				$models = array($modelOption);
	// 				// remove all records that doesn't have values
	// 				foreach ($models as $m) {
	// 					if (isset($data[$m])) {
	// 						$x = $data[$m];
	// 						foreach ($x as $i => $obj) {
	// 							if (empty($obj['value'])) {
	// 								unset($controller->request->data[$m][$i]);
	// 							} else {
	// 								$controller->request->data[$m][$i]['visible'] = 1;
	// 							}
	// 						}
	// 					}
	// 				}
	// 				if ($model->saveAll($controller->request->data)) {
	// 					$controller->Message->alert('general.add.success');
	// 					return $controller->redirect(array('controller' => $controller->name, 'action' => 'view', $selectedOption, $model->getLastInsertID()));
	// 				} else {
	// 					$this->log($model->validationErrors, 'error');
	// 					$controller->Message->alert('general.add.failed');
	// 				}
	// 				break;
				
	// 			default:
	// 				break;
	// 		}
	// 	}
	// 	return true;
	// }
	
	// public function postEdit(Model $model, $controller) {
	// 	$selectedOption = $controller->params->pass[0];
	// 	$modelOption = $model->alias . 'Option';
	// 	if (isset($controller->request->data['submit'])) {
	// 		$submit = $controller->request->data['submit'];
			
	// 		switch ($submit) {
	// 			case $modelOption:
	// 				$obj = array('value' => '', 'visible' => 1);
	// 				if (!isset($controller->request->data[$submit])) {
	// 					$controller->request->data[$submit] = array();
	// 				}
	// 				$obj['order'] = count($controller->request->data[$submit]);
	// 				$controller->request->data[$submit][] = $obj;
	// 				break;
					
	// 			case 'Save':
	// 				$data = $controller->request->data;
	// 				$id = $data[$model->alias]['id'];
	// 				$models = array($modelOption);
	// 				foreach ($models as $m) {
	// 					if (isset($data[$m])) {
	// 						$x = $data[$m];
	// 						foreach ($x as $i => $obj) {
	// 							if (empty($obj['value'])) {
	// 								unset($controller->request->data[$m][$i]);
	// 							}
	// 						}
	// 					}
	// 				}
					
	// 				if ($model->saveAll($controller->request->data)) {
	// 					$controller->Message->alert('general.edit.success');
	// 					return $controller->redirect(array('controller' => $controller->name, 'action' => 'view', $selectedOption, $id));
	// 				} else {
	// 					$this->log($model->validationErrors, 'error');
	// 					$controller->Message->alert('general.edit.failed');
	// 				}
	// 				break;
				
	// 			default:
	// 				break;
	// 		}
	// 	}
	// 	return true;
	// }
}
