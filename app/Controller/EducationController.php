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

class EducationController extends AppController {
	public $uses = Array(
		'EducationProgramme',
		'EducationGrade',
		'EducationGradesSubject',
		'EducationSubject'
	);
	
	public $modules = array(
		'EducationProgramme',
		'EducationGrade',
		'EducationGradesSubject',
		'EducationSubject'
	);

	public $accessMapping = array(
		'reorder' => 'update'
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Navigation->addCrumb($this->Message->getLabel('admin.title'), array('controller' => 'Admin', 'action' => 'view'));
		
		$options = array(
			'index' => $this->Message->getLabel('Education.structure'),
			'EducationProgramme' => $this->Message->getLabel('EducationProgramme.title'),
			'EducationGrade' => $this->Message->getLabel('EducationGrade.title'),
			'EducationGradesSubject' => $this->Message->getLabel('EducationGradesSubject.title'),
			'EducationSubject' => $this->Message->getLabel('EducationSubject.title')
		);
		$this->set('options', $options);
		$this->set('tabElement', '../Admin/tabs');
		$this->set('contentHeader', $this->Message->getLabel('admin.title'));
		$this->set('portletHeader', $this->Message->getLabel('Education.structure'));
	}
	
	public function index() {
		$this->Navigation->addCrumb($this->Message->getLabel('Education.structure'));//  array('controller' => 'Education', 'action' => 'index')
		
		$this->EducationGradesSubject->recursive = 0;
		$programmes = $this->EducationProgramme->find('all', array('order' => array('EducationProgramme.order')));
		foreach($programmes as $i => $obj) {
			$grades = $obj['EducationGrade'];
			$rowspan = count($grades) > 0 ? 0 : 1;
			foreach($grades as $j => $grade) {
				$rowspan += 1;
				if(!empty($grade['code'])) {
					$programmes[$i]['EducationGrade'][$j]['name'] .= ' (' . $grade['code'] . ')';
				}
				$subjects = $this->EducationGradesSubject->findAllByEducationGradeId($grade['id']);
				$rowspan += count($subjects) > 0 ? count($subjects)-1 : 0;
				$programmes[$i]['EducationGrade'][$j]['EducationSubject'] = $subjects;
			}
			$programmes[$i]['EducationProgramme']['rowspan'] = $rowspan;
			if(!empty($obj['EducationProgramme']['code'])) {
				$programmes[$i]['EducationProgramme']['name'] .= ' (' . $obj['EducationProgramme']['code'] . ')';
			}
		}
		if(empty($programmes)) $this->Message->alert('general.view.noRecords');
		$this->set('selectedPage', 'index');
		$this->set('data', $programmes);
		$this->set('header', $this->Message->getLabel('Education.structure'));
	}
	
	public function edit($model) {
		if(!in_array($model, $this->modules)) {
			return $this->redirect(array('action' => 'index'));
		}
		
		$conditions = array();
		if(!empty($this->request->params['named'])) {
			$conditions = array_merge($conditions, $this->request->params['named']);
		}
		$data = $this->{$model}->find('all', array(
			'conditions' => $conditions,
			'order' => array($model.'.order')
		));
		$header = $this->Message->getLabel($model . '.title');
		$this->set('selectedOption', $model);
		$this->set(compact('data', 'header', 'model', 'conditions'));
		
		$this->Navigation->addCrumb($header);
	}
	
	public function reorder($model) {
		if ($this->request->is(array('post', 'put'))) {
			$conditions = array();
			$redirect = array('action' => 'edit', $model);
			$data = $this->request->data;
			if(!empty($this->request->params['named'])) {
				$conditionId = key($this->request->params['named']);
				$selectedSubOption = current($this->request->params['named']);
				$conditions[$conditionId] = $selectedSubOption;
				$redirect = array_merge($redirect, $conditions);
			}
			$this->{$model}->moveOrder($data, $conditions);
			return $this->redirect($redirect);
		}
	}
} 
