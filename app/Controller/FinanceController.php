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

class FinanceController extends AppController {
	public $uses = array(
		'EducationGrade',
		'EducationFee'
	);
	public $modules = array(
		'EducationGrade',
		'EducationFee'
	);

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Navigation->addCrumb($this->Message->getLabel('admin.title'), array('controller' => 'admin', 'action' => 'view'));
		$this->Navigation->addCrumb($this->Message->getLabel('EducationFee.title'), array('controller' => 'Finance', 'action' => 'index'));
		
		$this->set('tabElement', '../Admin/tabs');
		$this->set('contentHeader', $this->Message->getLabel('admin.title'));
		$this->set('portletHeader', $this->Message->getLabel('EducationFee.title'));
	}

	public function index($selectedYear=0) {
		$SchoolYear = ClassRegistry::init('SchoolYear');
		$yearOptions = $SchoolYear->find('list', array('conditions' => array('visible' => 1), 'order' => 'start_year desc'));

		if (empty($selectedYear)) {
			$selectedYear = key($yearOptions);
		}

		$this->Session->write('EducationFee.school_year_id', $selectedYear);

		$data = $this->EducationGrade->find(
			'all',
			array(
				'fields' => array(
					'EducationGrade.id', 
					'EducationGrade.name', 
					'EducationProgramme.name', 
					'sum(EducationFee.amount) AS fee'
				),
				'recursive' => 0,
				'joins' => array(
					array(
						'type' => 'left',
						'table' => 'education_fees',
						'alias' => 'EducationFee',
						'conditions' => array(
							'EducationGrade.id = EducationFee.education_grade_id',
							'EducationFee.school_year_id' => $selectedYear
						)
					)
				),
				'conditions' => array(
					'EducationGrade.visible' => 1
				),
				'group' => array('EducationGrade.name'),
				'order' => array('EducationGrade.id asc')
			)
		);

		foreach ($data as $key => $value) {
			$data[$key][0]['fee'] = ($value[0]['fee']!='') ? $value[0]['fee'] : 0;
		}

		$tabHeader = $this->Message->getLabel('EducationFee.title');
		$model = 'EducationFee';
		$this->set(compact('data', 'yearOptions', 'selectedYear', 'model', 'tabHeader'));
	}
}