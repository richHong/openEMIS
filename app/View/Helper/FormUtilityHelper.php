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

App::uses('AppHelper', 'View/Helper');
App::uses('LabelHelper', 'View/Helper');
App::uses('ImageHelper', 'View/Helper');
App::uses('AjaxTableComponent', 'Controller/Component');

class FormUtilityHelper extends AppHelper {
    public $helpers = array('Form', 'Label', 'Html', 'Session', 'Image');
	private $ajaxTableComponent;
    
    // PHPSM-82: Override superclass constructor to use ajax table component.
    public function __construct(View $view , array $settings = array()) {
		parent::__construct($view, $settings);
		$this->ajaxTableComponent = new AjaxTableComponent(new ComponentCollection());
    }
    // end PHPSM-82

	public function getFormOptions($url=array(), $options=array()) {
		if (!isset($url['controller'])) {
			$url['controller'] = $this->_View->params['controller'];
		}
		if (!isset($url['action'])) {
			$url['action'] = $this->_View->get('model');
		}
		$options = array_merge(array(
			'url' => $url,
			'class' => 'form-horizontal',
			'novalidate' => true,
			'inputDefaults' => $this->getFormDefaults()
		), $options);
		
		// to check whether this form has files
		$fields = $this->_View->get('fields');
		if (!empty($fields)) {
			foreach ($fields as $key => $field) {
				if (array_key_exists('type', $field)) {
					if ($field['type'] === 'image' || $field['type'] === 'file_upload') {
						$options['type'] = 'file';
						break;
					}
				}
			}
		}
		return $options;
	}
	
	public function getFormDefaults() {
		$defaults = array(
			'div' => 'form-group',
			'label' => array('class' => 'col-md-2 control-label'),
			'between' => '<div class="col-md-3">',
			'after' => '</div>',
			'class' => 'form-control',
			'error' => array('attributes' => array('class' => 'alert alert-danger form-error'))
		);
		return $defaults;
	}

	public function getRowHTML() {
		return '
			<div class="row">
				<div class="col-md-3 formFieldValue">%s</div>
				<div class="col-md-6 formDataValue">%s</div>
			</div>';
	}
	
	public function getFormButtons($option = array()) {
		$div = isset($option['div'])? $option['div'] :"col-md-offset-2 form-buttons";
		echo '<div class="'.$div.'">';
		echo $this->Form->button($this->Label->get('general.save'), array('type' => 'submit', 'class' => 'btn btn-primary'));
		echo $this->Form->button($this->Label->get('general.cancel'), array('type' => 'reset', 'class' => 'btn btn-primary btn-back'));
		echo '</div>';
	}
	
	public function datepicker($field, $options=array()) {
		$dateFormat = 'dd-mm-yyyy';
		$icon = '<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>';
		$_options = array(
			'id' => 'date',
			'data-date' => date('d-m-Y'),
			'data-date-format' => $dateFormat,
			'data-date-autoclose' => 'true',
			'label' => false,
			'disabled' => false
		);
		if(!empty($options)) {
			$_options = array_merge($_options, $options);
		}
		
		$label = $_options['label'];
		unset($_options['label']);
		$disabled = $_options['disabled'];
		unset($_options['disabled']);
		$wrapper = $this->Html->div('input-group date bootstrap-datepicker', null, $_options);
		$defaults = $this->Form->inputDefaults();
		$inputOptions = array(
			'id' => $_options['id'],
			'type' => 'text',
			'between' => $defaults['between'] . $wrapper,
			'after' => $icon . $defaults['after'],
			'value' => $_options['data-date']
		);
		if($label !== false) {
			$inputOptions['label'] = array('text' => $label, 'class' => $defaults['label']['class']);
		}
		
		if($disabled !== false) {
			$inputOptions['disabled'] = $disabled;
		}
		$html = $this->Form->input($field, $inputOptions);
	
		$_datepickerOptions = array();
		$_datepickerOptions['id'] = $_options['id'];
		if(!empty($_options['startDate'])) {
			$_datepickerOptions['startDate'] = $_options['startDate'];
		}
		if(!empty($_options['endDate'])){
			$_datepickerOptions['endDate'] = $_options['endDate'];
		}
		if($disabled !== false) {
			$_datepickerOptions['disabled'] = $disabled;
		}
		if(!is_null($this->_View->get('datepicker'))) {
			$datepickers = $this->_View->get('datepicker');
			$datepickers[] = $_datepickerOptions;
			$this->_View->set('datepicker', $datepickers);
		} else {
			$this->_View->set('datepicker', array($_datepickerOptions));
		}
		
		return $html;
	}
	
	public function timepicker($field, $options=array()) {
		$id = isset($options['id']) ? $options['id'] : 'time';
		$wrapper = '<div class="input-group bootstrap-timepicker">';
		$icon = '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
		$defaults = $this->Form->inputDefaults();
		$inputOptions = array(
			'id' => $id,
			'type' => 'text',
			'between' => $defaults['between'] . $wrapper,
			'after' => $icon . $defaults['after']
		);
		
		$attr = array_key_exists('attr', $options) ? $options['attr'] : array();

		if(isset($options['class'])){
			$inputOptions['class'] = $options['class'];
		}
		if(isset($options['label'])){
			$inputOptions['label'] = $options['label'];
		}
		if(isset($options['default'])){
			$inputOptions['default'] = $options['default'];
		}
		$html = $this->Form->input($field, $inputOptions);
		if(!is_null($this->_View->get('timepicker'))) {
			$timepickers = $this->_View->get('timepicker');
			$timepickers[$id] = $attr;
			$this->_View->set('timepicker', $timepickers);
		} else {
			$this->_View->set('timepicker', array($id => $attr));
		}
		return $html;
	}
	
	public function link($type, $url=array(), $options=array()) {
		if (!isset($url['action'])) {
			$url['action'] = $this->_View->get('model');
		}
		$label = '<i class="fa fa-%s"></i> %s';

		switch($type) {
			case 'printable': 
				$label = sprintf($label, 'print', $this->Label->get('general.print'));
				break;
			case 'back':
				$label = sprintf($label, 'arrow-left', $this->Label->get('general.back'));
				break;
				
			case 'edit':
				if (array_key_exists('_update', $this->_View->viewVars) && $this->_View->viewVars['_update']) {
					$label = sprintf($label, 'edit', $this->Label->get('general.edit'));
				} else $label = '';
				break;
				
			case 'add':
				if (array_key_exists('_create', $this->_View->viewVars) && $this->_View->viewVars['_create']) {
					$label = sprintf($label, 'plus', $this->Label->get('general.add'));
				} else $label = '';
				break;
				
			case 'reorder':
				$label = sprintf($label, 'reorder', $this->Label->get('general.reorder'));
				break;
				
			case 'delete':
				if (array_key_exists('_delete', $this->_View->viewVars) && $this->_View->viewVars['_delete']) {
					$label = sprintf($label, 'trash-o', $this->Label->get('general.delete'));
				} else $label = '';
				break;
				
			case 'deleteModal':
				$url = '#deleteModal';
				$label = sprintf($label, 'trash-o', $this->Label->get('general.delete'));
				return '<a data-toggle="modal" href="' . $url . '">' . $label . '</a>';
			case 'modal':
				if (isset($url['type'])) {
					$urlType = $url['type'];
					$url = '#'.$urlType.'Modal';
					$modalIcon = '';

					switch($urlType) {
						case 'compile':
							$modalIcon = 'upload alt';
							break;
						default: $modalIcon = '';
					}
					$label = sprintf($label, $modalIcon, $this->Label->get('general.'.$urlType));
				} else {
					$url = '#modal';
					$label = sprintf($label, '', '');
				}
				return '<a data-toggle="modal" href="' . $url . '">' . $label . '</a>';
			case 'select':
				$label = sprintf($label, 'edit', $this->Label->get('general.select'));
				break;

			case 'export':
				if (array_key_exists('_execute', $this->_View->viewVars) && $this->_View->viewVars['_execute']) {
					$label = sprintf($label, 'download', $this->Label->get('general.export'));
				} else $label = '';
				break;
				
			case 'custom':
				$label = sprintf($label, $url['icon'], $url['label']);
                                unset($url['icon']);
                                unset($url['label']);
				break;
		}
		$options = array_merge($options, array('escape' => false));
		return $this->Html->link($label, $url, $options);
	}
	
	public function getDateOptions() {
		$options = array(
			'dateFormat' => 'DMY',
			'empty' => array('day' => $this->Label->get('general.day'), 'month' => $this->Label->get('general.month'), 'year' => $this->Label->get('general.year')),
			'selected' => ''
		);
		return $options;
	}
	
	public function getSort($sort, $field, $label=null) {
		$html = '<th class="sorting %s" by="%s" order="%s">%s</td>';
		if(is_null($label)) {
			$label = $this->Label->get($field);
		}
		if ($sort==null) {
			$sort = array();
		}
		if (array_key_exists('by', $sort)) {
			if($sort['by'] == $field) {
				$class = 'sorting_asc';
				$order = 'asc';
				if($sort['order'] == 'asc') {
					$class = 'sorting_desc';
					$order = 'desc';
				}
				return sprintf($html, $class, $field, $order, $label);
			}
		}
		return sprintf($html, '', $field, 'asc', $label);
	}
	
	public function getStatusOptions() {
		return array('1' => $this->Label->get('general.active'), '0' => $this->Label->get('general.inactive'));
	}
	
	public function getStatus($value) {
		$status = array('1' => '<span class="green">&#10003;</span>', '0' => '<span class="red">&#10008;</span>');

		return $status[$value];
	}
	
	public function getGender($value) {
		$gender = array('M' => $this->Label->get('general.male'), 'F' => $this->Label->get('general.female'));
		return $gender[strtoupper($value)];
	}
	
	public function getStaffType($value) {
        // PHPSM-30: Added the following check in case the staff type is missing from the database record.
        if ($value == null || $value == '') {
            return $this->Label->get('general.notSpecified');
        }

		$type = array('0' => $this->Label->get('staff.nonTeaching'), '1' => $this->Label->get('staff.teaching'));
		return $type[strtoupper($value)];
	}
	
	public function getFileType($name) {
		$temp = explode('.', $name);
		$ext = strtolower(array_pop($temp));
		return $ext;
	}
	
	public function inputVisible($view) {
		echo $view->Form->input('visible', array('options' => $this->getStatusOptions(), 'label' => array('text' => $this->Label->get('general.status'), 'class' => 'col-md-2 control-label')));
	}
	
	public function getUserType($view) {
		$model = ClassRegistry::init('SecurityUserType');
		return $model->getType($view);
	}
	
	public function checkOrCrossMarker($flag, $options=array()) {
		if (array_key_exists('hideCrosses', $options)) {
			if ($options['hideCrosses'] == true) {
				return $flag ? '<span class="green">&#10003;</span>' : '';
			}
		}
		return $flag ? '<span class="green">&#10003;</span>' : '<span class="red">&#10008;</span>';
	}
	
	// PHPSM-82: Additional functions added for convenience, as they can be useful for future add-ons
	public function twoColumnsCheckboxHtml($field) {
		$html = '<div class="form-group">';
        $html .= "<label class=\"col-md-2 control-label\">{$field['label']}</label>";
        $html .= '<div class="col-md-3">';
		
		for ($i = 0; $i < count($field['checkboxes']); $i += 2) {
			$html .= '<div class="checkbox row">';
			$html .= '<div class="checkbox-inline col-xs-5">';
			$html .= '<label>';
			$html .= "<input type=\"checkbox\" name=\"data[{$field['model']}][{$field['field']}][]\" value=\"{$field['checkboxes'][$i]['value']}\">";
			$html .= Inflector::humanize($field['checkboxes'][$i]['field']);
			$html .= '</label>';
			$html .= '</div>'; // end checkbox-inline
			
			if (($i + 1) < count($field['checkboxes'])) {
				$html .= '<div class="checkbox-inline col-xs-5">';
				$html .= '<label>';
				$html .= "<input type=\"checkbox\" name=\"data[{$field['model']}][{$field['field']}][]\" value=\"{$field['checkboxes'][$i + 1]['value']}\">";
				$html .= Inflector::humanize($field['checkboxes'][$i + 1]['field']);
				$html .= '</label>';
				$html .= '</div>'; // end checkbox-inline
			}
			
			$html .= '</div>'; // end checkbox row
		}
		
		$html .= '</div>'; // end col-md-3
		$html .= '</div>'; // end form-group
		
		return $html;
	}
	
	public function ajaxTableHtml($table) {
		return $this->ajaxTableComponent->tableHtml($table);
	}
	// end PHPSM-82

	public function isFieldVisible($attr, $type) {
		$visible = false;

		if (array_key_exists('visible', $attr)) {
			$visibleField = $attr['visible'];

			if (is_bool($visibleField)) {
				$visible = $visibleField;
			} else if (is_array($visibleField)) {
				if (array_key_exists($type, $visibleField)) {
					$visible = isset($visibleField[$type]) ? $visibleField[$type] : true;
				}
			}
		}
		return $visible;
	}

	public function getEditFormElement($options=array()) {
		$key = (array_key_exists('key', $options))? $options['key']: '';
		$formDefaults = (array_key_exists('formDefaults', $options))? $options['formDefaults']: '';
		$field = (array_key_exists('field', $options))? $options['field']: null;
		$data = (array_key_exists('data', $options))? $options['data']: array();
		if ($field==null) return '';

		$html = '';

		$fieldType = isset($field['type']) ? $field['type'] : 'string';
		$visible = $this->isFieldVisible($field, 'edit');

		if ($visible) {
			$fieldModel = array_key_exists('model', $field) ? $field['model'] : $model;
			$fieldName = $fieldModel . '.' . $key;
			$options = array();

			$label = $this->Label->getLabel($fieldModel, $key, $field);
			if(!empty($label)) {
				$options['label'] = array('text' => $label, 'class' => $formDefaults['label']['class']);
			}
			switch ($fieldType) {
				case 'disabled': 
					$options['type'] = 'text';
					$options['disabled'] = 'disabled';
					if (isset($field['options'])) {
						$options['value'] = $field['options'][$this->request->data[$fieldModel][$key]];
					}
					$html .= $this->Form->hidden($fieldName);
					break;
					
				case 'select':
					if (array_key_exists('option', $field) || empty($field['options'])) {
						$options['empty'] = '-- '.$this->Label->get('general.noData').' --';
					} else {
						if (isset($field['default'])) {
							$options['default'] = $field['default'];
						} else {
							$options['empty'] = '-- '.$this->Label->get('general.select').' --';
						}
					}
					if (isset($field['options'])) {
						$options['options'] = $field['options'];
					}
					if (isset($field['empty'])) {
						$options['empty'] = $field['empty'];
					}

					if (!empty($this->request->data)) {
						if(!empty($this->request->data[$fieldModel][$key])) {
							$options['default'] = $this->request->data[$fieldModel][$key];
						}
					}
					break;
					
				case 'text':
					$options['type'] = 'textarea';
					break;

				case 'boolean':
					// $options['style'] = '';
					// $options['label']['class'] = '';
					// $options['label']['style'] = '';
					break;
				
				case 'hidden':
					$options['type'] = 'hidden';
					$options['label'] = false;
					$options['div'] = false;
					break;

				case 'dataRows':
					$html .= $this->element('layout/dataRows', array('dataRowName' => $key));
					break;
					
				case 'image':
					$imgOptions = array();
					$imgOptions['field'] = 'photo_content';
					$imgOptions['width'] = '110';
					$imgOptions['height'] = '110';
					$imgOptions['label'] = $label;
					if (isset($this->data[$fieldModel]['photo_name']) && isset($this->data[$fieldModel]['photo_content'])) {
						$imgOptions['src'] = $this->Image->getBase64($this->data[$fieldModel]['photo_name'], $this->data[$fieldModel]['photo_content']);
					}
					$html .= $this->_View->element('layout/file_upload_preview', $imgOptions);
					break;
					
				case 'date':
					$attr = array('id' => $fieldModel . '_' . $key);
					if (array_key_exists($fieldModel, $this->request->data)) {
						if (array_key_exists($key, $this->request->data[$fieldModel])) {
							$attr['data-date'] = $this->request->data[$fieldModel][$key];
							$attr['data-date'] = date('d-m-Y', strtotime($attr['data-date']));
						}
					}
					if (array_key_exists('attr', $field)) {
						$attr = array_merge($attr, $field['attr']);
					}
					$html .= $this->datepicker($fieldName, $attr);
					break;
					
				case 'time':
					$attr = array('id' => $fieldModel . '_' . $key);
					
					if (array_key_exists('attr', $field)) {
						$attr = array_merge($dateOptions, $field['attr']);
					}
					$html .= $this->timepicker($fieldName, $attr);
					break;
				case 'file':
					$html .= $this->_View->element('layout/attachment');
					break;
				case 'file_upload';
					$attr = array('field' => $key);
					$html .= $this->_View->element('layout/attachment_upload', $attr);
					break;
				default:
					break;
				
			}

			if (isset($field['value'])) {
				$options['value'] = $field['value'];
			} else if (isset($field['default'])) {
				$options['default'] = $field['default'];
			}

			if (!in_array($fieldType, array('image', 'date', 'time', 'file', 'file_upload', 'dataRows'))) {
				$html .= $this->Form->input($fieldName, $options);
			}
		}
		return $html;
	}


	public function displayDataBlock($dataBlock) {
		$html = '';
		foreach($dataBlock as $key => $row) {
			$html .= $this->displayTxtData($row['title'], $row['data']);
		}
		return $html;
	}

	public function getViewFormElement($options=array()) {
		$key = (array_key_exists('key', $options))? $options['key']: '';
		$field = (array_key_exists('field', $options))? $options['field']: null;
		$data = (array_key_exists('data', $options))? $options['data']: array();
		if ($field==null) return '';

		$row = $this->getRowHTML();
		$defaults = $this->getFormDefaults();
		$html = '';
		$fieldType = isset($field['type']) ? $field['type'] : 'string';
		$visible = $this->isFieldVisible($field, 'view');

		if ($visible && $fieldType != 'hidden') {
			$fieldModel = array_key_exists('model', $field) ? $field['model'] : $model;
			//$fieldName = $fieldModel . '.' . $key;
			$label = $this->Label->getLabel($fieldModel, $key, $field);
			$options = array();
			if (!empty($label)) {
				$options['label'] = array('text' => $label, 'class' => $defaults['label']['class']);
			}
			
			if (array_key_exists($key, $data[$fieldModel])) {
				$value = $data[$fieldModel][$key];

				switch ($fieldType) {
					case 'select':
						// for handling of db boolean returning boolean instead of str/num
						$value .= '';
						if (array_key_exists($value, $field['options'])) {
							$value = $field['options'][$value];
						}
						break;

					case 'text':
						$value = nl2br($value);
						break;

					case 'boolean':
						$value = $value ? 'True' : 'False';
						break;

					case 'image':
						//$value = $this->Image->getBase64Image($data[$model][$key . '_name'], $data[$model][$key], $field['attr']);
						break;
						
					case 'download':
						$value = $this->Html->link($value, $field['attr']['url']);
						break;

					case 'modified_user_id':
					case 'created_user_id':
						$dataModel = $field['dataModel'];
						if (isset($data[$dataModel]['first_name']) && isset($data[$dataModel]['last_name'])) {
							$value = $data[$dataModel]['first_name'] . ' ' . $data[$dataModel]['last_name'];
						}
						break;

					case 'time':
						$date = new DateTime($data[$fieldModel][$key]);
	        			$value = $date->format('h:i A');
						break;

					case 'toggleVal': 
						$toggleBool  = strtok($value, ',');
						$toggleVal = strtok(',');
						$value = '';
						$value .= ($toggleBool)? $this->Label->get('general.enabled'): $this->Label->get('general.disabled');
						$value .= ' ('.$toggleVal.')';
						break;

					default:
						break;
				}

				if (strlen(trim($value)) == 0) {
					$value = '&nbsp;';
				}
				$html .= sprintf($row, $label, $value);
			} else {
				pr(sprintf('Field [%s] does not exist in Model [%s]', $key, $fieldModel));
			}
		}
		return $html;
	}

	public function getCustomFieldOptionFormElement($options=array()) {
		$html = '';
		$id = (array_key_exists('id', $options))? $options['id']: null;
		$customFieldId = (array_key_exists('customFieldId', $options))? $options['customFieldId']: null;
		$key = (array_key_exists('key', $options))? $options['key']: null;
		$value = (array_key_exists('value', $options))? $options['value']: null;
		$index = (array_key_exists('index', $options))? $options['index']: null;
		$model = (array_key_exists('model', $options))? $options['model']: null;
		$customFieldOptionModel = (array_key_exists('customFieldOptionModel', $options))? $options['customFieldOptionModel']: null;


		$formOptions = array();
	 	$formDefaults = $this->getFormDefaults();
	 	$fieldName = $customFieldOptionModel . '.' . $index . '.value';
	 	$fieldIdName = $customFieldOptionModel . '.' . $index . '.' . 'id';
	 	$customFieldIdName = $customFieldOptionModel . '.' . $index . '.' . $model.'_custom_field_id';
	 	$formOptions['label'] = array('text' => '');
	 	$formOptions['value'] = $value['value'];
	 	$html .= $this->Form->input($fieldName, $formOptions);
	 	if ($id != null) $html .= $this->Form->input($fieldIdName, array('value' => $id, 'type'=>'hidden'));
	 	if ($customFieldId != null) $html .= $this->Form->input($customFieldIdName, array('value' => $customFieldId, 'type'=>'hidden'));

		return $html;
	}

	public function displayTxtData($fieldName, $fieldData) {
		$defaults = $this->getFormDefaults();
		$html = '';
		$html .= '<div class="row">';
		$html .=	'<div class="col-md-3">'.$fieldName.'</div>';
		$html .=	'<div class="col-md-6">'.$fieldData.'</div>';
		$html .= '</div>';
		return $html;
	}
}
