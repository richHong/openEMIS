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

class StudentReportCard extends AppModel {

	public $useTable = 'students';
	public $hasMany = array(
		'ClassStudent',
		'AssessmentItemResult',
		'StudentAttendanceDay'
	);

	public $belongsTo = array(
		'SecurityUser',
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
	
	public $actsAs = array(
		'ControllerAction'
	);

	public $accessMapping = array(
		'printable' => 'read'
	);

	public function __construct() {
		parent::__construct();

		$this->validate = array(
		);
	}

	public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb($this->Message->getLabel('general.reportCard'));
	}

	private function getDataForReportCard($selectedYear) {
		$studentId = $this->Session->read('Student.id');

		$InstitutionSite = ClassRegistry::init('InstitutionSite');
		$institutionSiteData = $InstitutionSite->find('first');

		$this->recursive = 0;
		$studentData = $this->findById($studentId);
		$studentData['SecurityUser']['full_name'] = $this->Message->getFullName($studentData);

		$yearOptions = $this->ClassStudent->getStudentYearByStudentId($studentId);
		if (empty($selectedYear)) {
			$selectedYear = key($yearOptions);
		}

		if (isset($selectedYear)) {
			$classInYear = $this->ClassStudent->find('all', 
				array(
					'fields' => array(
					'SClass.name', 'EducationGrade.education_programme_id'
				),
					'recursive' => 0, 
					'conditions' => array(
						'ClassStudent.student_id = '.$studentId,
						'SClass.school_year_id = '.$selectedYear
					)
				)
			);

			$EducationProgramme = ClassRegistry::init('EducationProgramme');
			$programList = $EducationProgramme->getProgrammeList();
			$studentClass = array();
			$studentEducationGrade = array();
			foreach($classInYear as $key => $row) {
				array_push($studentClass, $row['SClass']['name']);
				array_push($studentEducationGrade, $row['EducationGrade']['education_programme_id']);
				
			}
			$studentClass = implode($studentClass,', ');
			$studentEducationGrade = array_unique($studentEducationGrade);

			foreach($studentEducationGrade as $key => $row) {
				if (array_key_exists($row, $programList)) {
					$studentEducationGrade[$key] = $programList[$row];
				}
			}
			$studentEducationGrade = implode($studentEducationGrade,',');
			$assessmentData = $this->AssessmentItemResult->getAssessmentResultByStudentId($studentId,$selectedYear);

			// format the assessmentdata
			$uniqueAssessmentItemType = array();
			foreach ($assessmentData as $key => $row) {
				array_push($uniqueAssessmentItemType, $row['AssessmentItemType']['name']);
			}
			$uniqueAssessmentItemType = array_unique($uniqueAssessmentItemType);
			$assessmentDataByEducationSubject = array();
			foreach($assessmentData as $key => $row) {
				if (!array_key_exists($row['EducationSubject']['name'], $assessmentDataByEducationSubject)) {
					$assessmentDataByEducationSubject[$row['EducationSubject']['name']] = array();
				}
				array_push($assessmentDataByEducationSubject[$row['EducationSubject']['name']], $row);
			}
			
			$SchoolYear = ClassRegistry::init('SchoolYear');
			$SchoolYear->recursive = -1;
			$selectedYearData = $SchoolYear->findById($selectedYear);

			$studentAttendance = $this->StudentAttendanceDay->getStudentAttendanceByDate($studentId, $selectedYearData['SchoolYear']['start_date'],$selectedYearData['SchoolYear']['end_date']);

			$data = array(
				'headers' => array(
					'reportDate' => array(
						'title' => $this->Message->getLabel('StudentReportCard.reportDate'),
						'data' => date('d M Y')
					),
					'schoolName' => array(
						'title' => $this->Message->getLabel('StudentReportCard.schoolName'),
						'data' => $institutionSiteData['InstitutionSite']['name']
					),
					'reportTitle' => array(
						'title' => $this->Message->getLabel('StudentReportCard.reportTitle'),
						'data' => $this->Message->getLabel('StudentReportCard.reportTitleData')
					),
					'reportSubtitle' => array(
						'title' => $this->Message->getLabel('StudentReportCard.reportSubtitle'),
						'data' => $yearOptions[$selectedYear]
					)
				),
				"datablock1" => array(
					'studentName' => array(
						'title' => $this->Message->getLabel('SecurityUser.full_name'),
						'data' => $studentData['SecurityUser']['full_name']
					),
					'openemisid' => array(
						'title' => $this->Message->getLabel('SecurityUser.openemisid'),
						'data' => $studentData['SecurityUser']['openemisid']
					),
					'studentClass' => array(
						'title' => $this->Message->getLabel('general.class'),
						'data' => $studentClass
					),
					'studentProgramme' => array(
						'title' => $this->Message->getLabel('EducationGrade.title'),
						'data' => $studentEducationGrade
					),

				),
				'assessmentData' => $assessmentDataByEducationSubject,
				'uniqueAssessmentItemType' => $uniqueAssessmentItemType,
				"datablock2" => array(
					// 'studentGradeTotal' => array(
					// 		'title' => $this->Message->getLabel('StudentReportCard.studentGradeTotal'),
					// 		'data' => "placeholder"
					// 	),
					// 'studentClassPosition' => array(
					// 		'title' => $this->Message->getLabel('StudentReportCard.studentClassPosition'),
					// 		'data' => "placeholder"
					// 	),
					// 'studentGradePercent' => array(
					// 		'title' => $this->Message->getLabel('StudentReportCard.studentGradePercent'),
					// 		'data' => 'placeholder'
					// 	),
					// 'studentGradePosition' => array(
					// 		'title' => $this->Message->getLabel('StudentReportCard.studentGradePosition'),
					// 		'data' => "placeholder"
					// 	),
					// 'studentGradeResults' => array(
					// 		'title' => $this->Message->getLabel('StudentReportCard.studentGradeResults'),
					// 		'data' => 'placeholder'
					// 	),
					// 'studentPromotion' => array(
					// 		'title' => $this->Message->getLabel('StudentReportCard.promotion'),
					// 		'data' => 'placeholder'
					// 	),
					// 'studentConduct' => array(
					// 		'title' => $this->Message->getLabel('StudentReportCard.studentConduct'),
					// 		'data' => 'placeholder'
					// 	),

					// 'studentComments' => array(
					// 		'title' => $this->Message->getLabel('StudentReportCard.studentComments'),
					// 		'data' => 'placeholder'
					// 	)

				)
			);

			$pdfName = $yearOptions[$selectedYear].'-'.$studentId.'-'.$studentData['SecurityUser']['full_name'];
			$compactData = compact('yearOptions', 'selectedYear', 'data', 'studentAttendance','pdfName');
			$this->setVar($compactData);
			return $compactData;
		} else {
			$this->Message->alert('ClassStudent.noClass');
			$this->setVar($yearOptions);
		}
	}

	public function index($selectedYear=0) {
		$this->getDataForReportCard($selectedYear);
	}

	public function printable($selectedYear) {
		$this->render = false;
		ob_start();
		$data = $this->getDataForReportCard($selectedYear);
		$html = $this->controller->render('StudentReportCard/printable','ajax');
		ob_end_clean();

		$leftRightMargin = 0;
		$configuration = array(
			// mode: 'c' for core fonts only, 'utf8-s' for subset etc.
			'mode' => 'utf8-s',
			// page format: 'A0' - 'A10', if suffixed with '-L', force landscape
			'format' => 'A4',
			 // default font size in points (pt)
			'font_size' => 0,
			// default font
			'font' => NULL,
			// page margins in mm
			'margin_left' => $leftRightMargin,
			'margin_right' => $leftRightMargin,
			'margin_top' => 16,
			'margin_bottom' => 16,
			'margin_header' => 9,
			'margin_footer' => 9
		);
		if ($this->request->is(array('post', 'put'))) {
			if (array_key_exists('submitType', $this->request->data)) {
				if ($this->request->data['submitType'] == 'generatePDF') {
					$pdfName = 'report';
					if (array_key_exists('pdfName', $data)) {
						$pdfName = $data['pdfName'];
					}

					$this->controller->Mpdf->init();
					$mpdf->CSSselectMedia="print";
					//$this->controller->Mpdf->useSubstitutions = true;// tries substitute missing characters in UTF-8(multibyte) documents (failed for this case - error messages).. so.. ignore_invalid_utf8...
					$this->controller->Mpdf->ignore_invalid_utf8 = true;
					$this->controller->Mpdf->WriteHTML($html);
					$this->controller->Mpdf->Output($pdfName.'.pdf', 'D'); //'I'
				}
			}
		}	
	}
	
}
