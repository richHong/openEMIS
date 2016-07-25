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

class StudentResult extends AppModel {
	public $useTable = 'assessment_item_results';
	public $actsAs = array('ControllerAction','Export' => array('module' => 'Student'));

	public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb($this->Message->getLabel('general.results'));
		$this->setVar('tabHeader', $this->Message->getLabel('StudentResult.title'));
	}

	
	public function index($selectedYear=0) {
		$studentId = $this->Session->read('Student.id');
		$yearOptions = ClassRegistry::init('AssessmentItemResult')->getYearOptions($studentId);

		if(!empty($yearOptions)) {
			// $selectedYear = key($yearOptions);
			// $this->setVar('yearOptions', $yearOptions);
			// $this->setVar('selectedYear', $selectedYear);
			// $typeOptions = array($this->Message->getLabel('admin.nationalAssessments'), $this->Message->getLabel('class.assignments'));
			// $options = array();
			// $options['selectedYear'] = $selectedYear;
			// $this->Session->write('StudentResult.selectedYear', $selectedYear);
			// $data = $this->getListData($options);

			// $this->setVar('typeOptions', $typeOptions);
			// $this->setVar('data', $data);		
			if ($selectedYear == 0) $selectedYear = key($yearOptions);
			$AssessmentItemResult = ClassRegistry::init('AssessmentItemResult');
	        $data = $AssessmentItemResult->getAssessmentResultOverviewByStudentId($studentId,$selectedYear);

	        $EducationGrade = ClassRegistry::init('EducationGrade');
	        $educationGradeList = $EducationGrade->find('list');

	        foreach ($data as $key => $value) {
	        	$data[$key]['EducationGradesSubject']['name'] = $educationGradeList[$value['EducationGradesSubject']['education_grade_id']];
	        }


	                
	        $typeOptions = array($this->Message->getLabel('admin.nationalAssessments'), $this->Message->getLabel('class.assignments'));
			$this->setVar('typeOptions', $typeOptions);
	        if(empty($data)) $this->Message->alert('general.view.noRecords');
	        
	        $this->setVar('yearOptions', $yearOptions);
			$this->setVar('selectedYear', $selectedYear);
	        $this->setVar('data', $data);
			$this->setVar('studentId', $studentId);
		} else {
			$this->Message->alert('general.view.notExists', array('type' => 'info'));
		}
	}

	public function reportGetFieldNames() {
		return $this->getFieldNamesFromData($this->reportData);
	}

	public function reportGetData() {
		$AssessmentItemResult = ClassRegistry::init('AssessmentItemResult');
		$options = array();
		$options['selectedYear'] = $this->Session->read('StudentResult.selectedYear');
		$this->reportData = $this->getListData($options);
		return $this->reportData;
	}

	public function getListData($options=array()) {
		$studentId = $this->Session->read('Student.id');
		$selectedYear = null;
		if (isset($options['selectedYear'])) {
			$selectedYear = $options['selectedYear'];
		}
		$data = ClassRegistry::init('AssessmentItemResult')->getAssessmentResultOverviewByStudentId($studentId,$selectedYear);
		return $data;
	}
}
?>
