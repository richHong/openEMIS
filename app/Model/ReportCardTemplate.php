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
App::uses('AjaxTableComponent', 'Controller/Component');

class ReportCardTemplate extends AppModel {
	public $actsAs = array('ControllerAction',
			'DatePicker' => array('active_from_date', 'active_to_date', 'display_date')
		);
	public $hasMany = array(
		// 'ReportCardTemplateComments',
		// 'ReportCardTemplateSignatures'
	);

	private $ajaxTableComponent;

	
	public function __construct() {
 		parent::__construct();
 		$this->ajaxTableComponent = new AjaxTableComponent(new ComponentCollection());
	}
	
	public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb(
			$this->Message->getLabel('admin.rcTemplates'),
			array(
				'controller' => $this->params['controller'],
				'action' => $this->indexPage
			)
		);
			
		$this->setVar('model', get_class($this));
		$this->setVar('portletHeader', $this->Message->getLabel('admin.rcTemplates'));
		$this->setVar('tabHeader', $this->Message->getLabel('admin.rcTemplates'));
		$this->setVar('fields', $this->getDisplayFields());
	}

	public function getFields($options=array()) {
		parent::getFields();

		// $this->fields['ReportCardTemplateComments'] = array(
		// 	'data-name' => 'ReportCardTemplateComments',
		// 	'type' => 'dataRows',
		// 	'visible' => true
		// );

		// $this->fields['ReportCardTemplateSignatures'] = array(
		// 	'data-name' => 'ReportCardTemplateSignatures',
		// 	'type' => 'dataRows',
		// 	'visible' => true
		// );
		return $this->fields;
	}
	
	public function getDisplayFields() {
		$model = get_class($this);
		$fields = array(
            'model' => $this->alias,
            'fields' => array(
                array('field' => 'title', 'model' => $model),
                array('field' => 'subtitle', 'model' => $model),
                array('field' => 'active_from_date', 'model' => $model, 'type' => 'datepicker', 'dateOptions' => array('id' => 'activeFromDate')),
                array('field' => 'active_to_date', 'model' => $model, 'type' => 'datepicker', 'dateOptions' => array('id' => 'activeToDate')),
                array('field' => 'display_date', 'model' => $model, 'type' => 'datepicker', 'dateOptions' => array('id' => 'displayDate')),
                array(
					'type' => 'checkbox_group_2_col',
					'label' => 'Student Details Display',
					'model' => $model,
					'field' => 'details_json',
					'checkboxes' => array(
						array('field' => 'name', 'value' => 'name'),
						array('field' => 'openemisid', 'value' => 'openemisid'),
						array('field' => 'national_id', 'value' => 'national_id'),
						array('field' => 'age', 'value' => 'age'),
						array('field' => 'gender', 'value' => 'gender'),
						array('field' => 'birth_date', 'value' => 'birth_date'),
						array('field' => 'student_status', 'value' => 'student_status'),
						array('field' => 'class_index_no', 'value' => 'class_index_no'),
						array('field' => 'national_id', 'value' => 'national_id'),
						array('field' => 'class', 'value' => 'programme'),
						array('field' => 'form_teacher', 'value' => 'form_teacher'),
						array('field' => 'cform_teacher', 'value' => 'cform_teacher')
					)
				),
				array(
					'type' => 'checkbox_group_2_col',
					'label' => 'Additional Info',
					'model' => $model,
					'field' => 'details_json',
					'checkboxes' => array(
						array('field' => 'school_name', 'value' => 'school_name'),
						array('field' => 'school_logo', 'value' => 'school_logo'),
						array('field' => 'result_total', 'value' => 'result_total'),
						array('field' => 'result_percentage', 'value' => 'result_percentage'),
						array('field' => 'pass_fail', 'value' => 'pass_fail'),
						array('field' => 'class_position', 'value' => 'class_position'),
						array('field' => 'grade_position', 'value' => 'grade_position'),
						array('field' => 'attendance', 'value' => 'attendance'),
						array('field' => 'promotion', 'value' => 'promotion'),
						array('field' => 'conduct', 'value' => 'conduct')
					)
				),
				$this->getCommentsTableArray(),
				$this->getSignaturesTableArray(),
				$this->getResultColumnsTableArray()
            )
		);

		return $fields;
	}
	
	private function getCommentsTableArray() {
		return array(
			'type' => 'ajax_table',
			'id' => 'comments_table',
			'label' => 'Comments',
			'addLabel' => 'Add Comment',
			'addRowUrl' => '/openemis-school-phpsm/Admin/report_card_template_add_row_comments',
			//'model' => $model,
			'fields' => array(
				array('field' => 'display_order'),
				array('field' => 'title'),
			),
		);
	}
	
	private function getSignaturesTableArray() {
		return array(
			'type' => 'ajax_table',
			'id' => 'signatures_table',
			'label' => 'Signatures',
			'addLabel' => 'Add Signature',
			'addRowUrl' => '/openemis-school-phpsm/Admin/report_card_template_add_row_signatures',
			//'model' => $model,
			'max' => 3,
			'fields' => array(
				array('field' => 'display_order'),
				array('field' => 'title'),
			),
		);
	}
	
	private function getResultColumnsTableArray() {
		return array(
			'type' => 'ajax_table',
			'id' => 'result_columns_table',
			'label' => 'Result Columns',
			'addLabel' => 'Add Result Column',
			'addRowUrl' => '/openemis-school-phpsm/Admin/report_card_template_add_row_result_columns',
			//'model' => $model,
			'min' => 1,
			'fields' => array(
				array('field' => 'display_order'),
				array('field' => 'name'),
				array('field' => 'weightage'),
				array('field' => 'display_marks', 'type' => 'checkbox'),
				array('field' => 'display_grades', 'type' => 'checkbox')
			),
		);
	}

	public function index() {
		$this->Navigation->addCrumb($this->Message->getLabel('admin.rcTemplateList'));

		$data = $this->find('all', array('recursive' => -1));

		if(empty($data)) {
			$this->Message->alert('general.view.noRecords', array('type' => 'info'));
		}
		
		$this->setVar('data', $data);
	}

	public function view($id=0) {
		parent::view($id);
		$this->render = 'view';
	}

	public function edit($id=0) {
		parent::edit($id);
		$this->render = 'edit';
	}

	public function add() {
		parent::add();
		$this->render = 'edit';
	}
	
	public function report_card_template($controller, $params) {
		$controller->Navigation->addCrumb($controller->Message->getLabel('admin.rcTemplateList'));

		$data = $this->find('all', array('recursive' => -1));

		if(empty($data)) {
			$controller->Message->alert('general.view.noRecords', array('type' => 'info'));
		}
		
		$controller->set('data', $data);
	}
	
	public function report_card_template_add($controller, $params) {
		if ($controller->request->is('post')) {
			pr($params); die;
		}
	}
	
	public function report_card_template_add_row_comments($controller, $params) {
		$this->ajaxTableSetup($controller, $this->getCommentsTableArray());
	}
	
	public function report_card_template_add_row_signatures($controller, $params) {
		$this->ajaxTableSetup($controller, $this->getSignaturesTableArray());
	}
	
	public function report_card_template_add_row_result_columns($controller, $params) {
		$this->ajaxTableSetup($controller, $this->getResultColumnsTableArray());
	}
	
	private function ajaxTableSetup($controller, array $table) {
		$this->render = false;
		echo $this->ajaxTableComponent->rowHtml($table);
	}
}
 
