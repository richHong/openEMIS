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

class StudentFee extends AppModel {
	public $belongsTo = array(
		'Student',
		'SchoolYear',
		'EducationGrade',
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
		'ControllerAction', 'Export' => array('module' => 'Student')
	);

	public $accessMapping = array(
		'listing' => 'read'
	);

    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'comment' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('comment')
                )
            ),
            'paid' => array(
                'ruleRequired' => array(
                    'rule' => 'notNegativeNumber',
                    'required' => true,
                    'message' => $this->getErrorMessage('currency')
                )
            )
        );
    }

    public function notNegativeNumber() {
    	$to_test = $this->data[$this->alias]['paid'];
    	return (is_numeric($to_test) && $to_test>=0);
    }
	
	public function beforeAction() {
		parent::beforeAction();
		$this->Navigation->addCrumb($this->Message->getLabel('StudentFee.name'));

		$this->fields['school_year_id']['type'] = 'hidden';
		$this->fields['school_year_id']['value'] = $this->Session->read('StudentFee.school_year_id');
		$this->fields['education_grade_id']['type'] = 'hidden';
		$this->fields['education_grade_id']['value'] = $this->Session->read('StudentFee.education_grade_id');
		$this->fields['student_id']['type'] = 'hidden';
		$this->fields['student_id']['value'] = $this->Session->read('Student.id');
		$this->fields['created']['labelKey'] = $this->alias;
		$this->fields['created_user_id']['labelKey'] = $this->alias;

		$tabHeader = $this->Message->getLabel($this->alias.'.name');
		$this->setVar('tabHeader', $tabHeader);

		// for breadcrumb of internal methods after listing
		$actions = array('view', 'add', 'edit');
		if (in_array($this->action, $actions)) {
			$this->Navigation->addCrumb($this->Session->read('StudentFee.crumbName'));
		}		
	}

	public function getStudentPaymentByStudentId($studentId) {
		//only used by /transaction at the moment... but the functionality is disabled
		$this->unBindModel(	
			array(
				'belongsTo' => array('Student', 'SchoolYear', 'EducationGrade')
			)
		);
		$studentPayment = $this->find('all');
		
		$data = array();
		foreach($studentPayment as $key => $row) {
			$tempArray = array();
			$tempArray['id'] = $row['StudentFee']['id'];
			$tempArray['date'] = $row['StudentFee']['created'];
			$tempArray['item'] = $row['StudentFee']['comment'];
			$tempArray['payment'] = number_format((float)$row['StudentFee']['paid'], 2, '.', '');
			array_push($data, $tempArray);
		}

		return $data;
	}

	public function sortComparatorDate($a, $b) {
	    if (strtotime($a['date']) == strtotime($b['date'])) {
	        return 0;
	    }
	    return ($a['date'] > $b['date']) ? -1 : 1;
	}

	// keeping method to show a transaction log for this.. to activate remove the _ so it can find its ctp
	public function _transaction() {
		$studentId = $this->Session->read('Student.id');

		$EducationFee = ClassRegistry::init('EducationFee');
		$educationFeeData = $EducationFee->getEducationFeeByStudentForTxn($studentId);

		$studentPaymentData = $this->getStudentPaymentByStudentId($studentId);		

		$data = array_merge($educationFeeData,$studentPaymentData);
		usort($data, array(&$this, "sortComparatorDate"));

		$total_fees = 0;
		$total_payments = 0;
		foreach($data as $key => $row) {
			$total_fees += (array_key_exists('fee', $row) ? $row['fee'] : 0);
			$total_payments += (array_key_exists('payment', $row) ? $row['payment'] : 0);
		}

		$this->setVar('data', $data);
		$this->setVar('total_fees', $total_fees);
		$this->setVar('total_payments', $total_payments);
		$this->setVar('outstanding_payments', ($total_fees-$total_payments));
	}

	public function listing() {
		$EducationGrade = ClassRegistry::init('EducationGrade');
		$educationGradeOptions = $EducationGrade->getGradeList();

		$data = $this->getListData(array('educationGradeOptions'=>$educationGradeOptions));

		if(empty($data)) {
			$this->Message->alert('general.view.noRecords', array('type' => 'info'));
		}
		
		$this->setVar('data', $data);
		$this->setVar('educationGradeOptions', $educationGradeOptions);
	}

	public function reportGetFieldNames() {
		$fieldNames = $this->getFieldNamesFromData($this->reportData);
		unset($fieldNames['SClass.id']);
		unset($fieldNames['SchoolYear.id']);
		unset($fieldNames['ClassGrade.id']);
		unset($fieldNames['ClassGrade.class_id']);
		unset($fieldNames['ClassGrade.education_grade_id']);
		unset($fieldNames['ClassGrade.visible']);
		return $fieldNames;
	}

	public function reportGetData() {
		$EducationGrade = ClassRegistry::init('EducationGrade');
		$educationGradeOptions = $EducationGrade->getGradeList();
		$data = array();
		$rawData = $this->getListData(array('educationGradeOptions'=>$educationGradeOptions));

		$this->reportData = array();
		foreach ($rawData as $key => $value) {
			foreach ($rawData[$key]['ClassGrade'] as $key2 => $value2) {
				$tArray = $value;
				$tArray['ClassGrade'] = $value2;
				array_push($this->reportData, $tArray);
			}
		}
		return $this->reportData;
	}



	public function getListData($options=array()) {
		if (array_key_exists('educationGradeOptions', $options)) {
			$educationGradeOptions = $options['educationGradeOptions'];
		}

		$studentId = $this->Session->read('Student.id');
		$ClassStudent = ClassRegistry::init('ClassStudent');
		$ClassStudent->recursive = 1;
		$classStudentData = $ClassStudent->find(
			'all',
			array(
				'fields' => array(
					'SClass.id', 'SClass.name', 'ClassStudent.education_grade_id'
				),
				'conditions' => array(
					'ClassStudent.student_id' => $studentId
				)
			)
		);

		$studentClassGrade = array();
		foreach($classStudentData as $key => $row) {
			if (!array_key_exists($row['SClass']['id'], $studentClassGrade)) {
				$studentClassGrade[$row['SClass']['id']] = array();
			}
			array_push($studentClassGrade[$row['SClass']['id']], $row['ClassStudent']['education_grade_id']);
		}

		$classesStudentIsIn = array();
		foreach($classStudentData as $key => $row) {
			array_push($classesStudentIsIn, $row['SClass']['id']);
		}


		$SClass = ClassRegistry::init('SClass');
		$SClass->unBindModel(
			array(
				'hasMany' => array('ClassLesson', 'ClassStudent', 'ClassSubject', 'ClassTeacher', 'Timetable', 'TimetableEntry')
			)
		);
		$data = $SClass->find(
			'all',
			array(
				'fields' => array(
					'SchoolYear.id', 'SchoolYear.name'
				),
				'recursive' => 1,
				'conditions' => array(
					'SClass.id' => $classesStudentIsIn
				),
				// $conditions,
				'order' => array('SchoolYear.end_date desc')
			)
		);

		

		$EducationFee = ClassRegistry::init('EducationFee');
		foreach($data as $key => $classData) {
			foreach($classData['ClassGrade'] as $key2 => $classGradeData) {
				// need to remove the grades that the student is not in.. the sql is going to be very complex if done on sql end... so just removing them here
				if (!in_array($classGradeData['education_grade_id'], $studentClassGrade[$classData['SClass']['id']])) {
					unset($data[$key]['ClassGrade'][$key2]);
					continue;
				}

				if (array_key_exists($data[$key]['ClassGrade'][$key2]['education_grade_id'], $educationGradeOptions)) {
					$data[$key]['ClassGrade'][$key2]['name'] = $educationGradeOptions[$data[$key]['ClassGrade'][$key2]['education_grade_id']];
				}

				$data[$key]['ClassGrade'][$key2]['fees'] = $EducationFee->getEducationFeesTotalAmount(
					$data[$key]['SchoolYear']['id'], 
					$data[$key]['ClassGrade'][$key2]['education_grade_id']
				);
				$data[$key]['ClassGrade'][$key2]['paid'] = $this->getStudentFeePaidTotalAmount(
					$data[$key]['SchoolYear']['id'], 
					$data[$key]['ClassGrade'][$key2]['education_grade_id'],
					$studentId
				);

				$tdata = $this->EducationGrade->find(
					'all',
					array(
						'fields' => array(
							'EducationProgramme.name'
						),
						'recursive' => 0,
						'conditions'=> array(
							'EducationGrade.id = '.$data[$key]['ClassGrade'][$key2]['education_grade_id']
						)
					)
					
				);
				$data[$key]['ClassGrade'][$key2]['programme_name'] = $tdata[0]['EducationProgramme']['name'];
			}
		}

		

		return $data;
	}

	public function index($schoolYear=0, $educationGrade=0) {
		if ($schoolYear==0 || $educationGrade==0) {
			if ($this->Session->check('StudentFee.school_year_id') && $this->Session->check('StudentFee.education_grade_id')) {
				$schoolYear = $this->Session->read('StudentFee.school_year_id');
				$educationGrade = $this->Session->read('StudentFee.education_grade_id');

				return $this->redirect(
				array('action' => $this->alias,'index', $schoolYear, $educationGrade)
				);

			}
		}

		$studentId = $this->Session->read('Student.id');
		$this->Session->write('StudentFee.school_year_id', $schoolYear);
		$this->Session->write('StudentFee.education_grade_id', $educationGrade);

		$this->unBindModel(
			array(
				'belongsTo' => array('Student', 'SchoolYear')
			)
		);
		$data = $this->find(
			'all',
			array(
				// 'recursive' => 1,
				'fields' => array(
					'StudentFee.*',
					'CreatedUser.first_name','CreatedUser.middle_name','CreatedUser.last_name'
				),
				'conditions' => array(
					'StudentFee.student_id' => $studentId,
					'StudentFee.school_year_id' => $schoolYear,
					'StudentFee.education_grade_id' => $educationGrade
				)
			)
		);

		$this->SchoolYear->recursive = -1;
		$schoolYearName = $this->SchoolYear->findById($schoolYear);
		$schoolYearName = $schoolYearName['SchoolYear']['name'];

		$this->EducationGrade->recursive = 0;
		$educationGradeName = $this->EducationGrade->findById($educationGrade);
		$educationProgrammeName = $educationGradeName['EducationProgramme']['name'];
		$educationGradeName = $educationGradeName['EducationGrade']['name'];
		
		$this->Session->write('StudentFee.crumbName', $schoolYearName.' - '.$educationProgrammeName.' - '.$educationGradeName);

		$this->Navigation->addCrumb($this->Session->read('StudentFee.crumbName'));

		foreach($data as $key => $row) { 
			$data[$key]['CreatedUser']['full_name'] = $this->Message->getFullName($data[$key],array('findInModel'=>'CreatedUser'));
		}
		$this->Message->getFullName($data,array('findInModel'=>'CreatedUser'));

		$EducationFee = ClassRegistry::init('EducationFee');
		$educationFeeData = $EducationFee->getByYearAndGrade($schoolYear, $educationGrade);

		$this->setVar('data', $data);
		$this->setVar('educationFeeData', $educationFeeData);
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
		if (isset($this->request->data['StudentFee']['id'])) unset($this->request->data['StudentFee']['id']);
		parent::add();
		$this->render = 'edit';
	}

	public function getStudentFeePaidTotalAmount($school_year_id, $education_grade_id, $studentId) {
		$data = $this->find(
			'first',
			array(
				'fields' => 'sum(StudentFee.paid) as total_paid',
				'conditions' => array(
					'StudentFee.school_year_id' => $school_year_id,
					'StudentFee.education_grade_id' => $education_grade_id,
					'StudentFee.student_id' => $studentId

				)
			)
		);
		return ($data[0]['total_paid']!='') ? $data[0]['total_paid'] : 0;;
	}
}
