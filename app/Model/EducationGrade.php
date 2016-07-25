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

class EducationGrade extends AppModel {
	public $belongsTo = array(
		'EducationProgramme',
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
		'ClassGrade',
		'ClassStudent',
		'EducationGradesSubject',
		'AssessmentItemType'
	);
	public $actsAs = array('ControllerAction', 'Reorder');

	public function __construct() {
		parent::__construct();

		$this->validate = array(
			'code' => array(
				'required' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('code')
				)
			),
			'name' => array(
				'required' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('name')
				)
			),
			'education_programme_id' => array(
				'required' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('programme')
				)
			)
		);
	}
	
	public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb($this->Message->getLabel($this->alias . '.title'));
		
		$this->fields['education_programme_id']['type'] = 'select';
		$this->fields['education_programme_id']['options'] = $this->EducationProgramme->getOptions();
		$this->fields['order']['visible'] = false;
		$this->fields['visible']['labelKey'] = 'general';
		$this->fields['visible']['type'] = 'select';
		$this->fields['visible']['default'] = 1;
		$this->fields['visible']['options'] = array(1 => __('Active'), 0 => __('Inactive'));
		
		$this->setVar('selectedPage', get_class($this));
		$this->setVar('header', $this->Message->getLabel($this->alias . '.title'));
	}

	public function index($selectedProgramme=0) {
		$programmeOptions = $this->EducationProgramme->getOptions();
		$this->recursive = 0;
		$data = array();
		if(!empty($programmeOptions)) {
			if(!array_key_exists($selectedProgramme, $programmeOptions)) {
				$selectedProgramme = key($programmeOptions);
			}
			$data = $this->findAllByEducationProgrammeId($selectedProgramme, null, array($this->alias . '.order'));
		} else {
			$this->Message->alert($this->alias . '.noProgrammes');
		}
		if(empty($data)) $this->Message->alert('general.view.noRecords');
		$this->setVar('selectedSecondary', $selectedProgramme);
		$this->setVar('secondaryOptions', $programmeOptions);
		$this->setVar(compact('data'));
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

	public function getGradeList($type='name', $order='ASC') {
		$value = 'EducationGrade.' . $type;
		$orderField = 'EducationGrade.order';
		$result = $this->find('list', array(
			'fields' => array('EducationGrade.id', $value),
			'order' => array($orderField . ' ' . $order)
		));
		return $result;
	}
	
	public function getProgrammeGradeOptions($visible = true) {
		$conditions = array();
		if($visible) {
			$conditions = array('EducationProgramme.visible' => 1, 'EducationGrade.visible' => 1);
		}
		$list = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $conditions,
			'order' => array('EducationProgramme.order', 'EducationGrade.order')
		));
		$data = array();
		foreach($list as $obj) {
			$grade = $obj['EducationGrade'];
			$programme = $obj['EducationProgramme'];
			$data[$grade['id']] = $programme['name'] . ' - ' . $grade['name'];
		}
		return $data;
	}
}
