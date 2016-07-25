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

// education grade id eg Primary 6

class EducationFee extends AppModel {
	public $belongsTo = array(
		'FeeType',
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
		'ControllerAction'
	);

    public function __construct() {
        parent::__construct();

        $this->validate = array(
        	'description' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('description')
                )
            ),
            'amount' => array(
                'ruleRequired' => array(
                    'rule' => 'notNegativeNumber',
                    'required' => true,
                    'message' => $this->getErrorMessage('currency')
                )
            ),
            'fee_type_id' => array(
                'ruleRequired' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('fee_type_id')
                )
            )
        );
    }

    public function notNegativeNumber() {
    	$to_test = $this->data[$this->alias]['amount'];
    	return (is_numeric($to_test) && $to_test>=0);
    }
	
	public function beforeAction() {
		parent::beforeAction();
		$this->setVar('portletHeader', $this->Message->getLabel('EducationFee.title'));
		$this->setVar('tabHeader', $this->Message->getLabel('EducationFee.title'));
		$this->fields['source']['visible'] = false;
		$this->fields['school_year_id']['type'] = 'hidden';
		$this->fields['school_year_id']['value'] = $this->Session->read('EducationFee.school_year_id');
		$this->fields['education_grade_id']['type'] = 'hidden';
		$this->fields['education_grade_id']['value'] = $this->Session->read('EducationFee.education_grade_id');
		$this->fields['fee_type_id']['type'] = 'select';
		$this->fields['fee_type_id']['options'] = $this->FeeType->getOptions();

		// for breadcrumb of internal methods after listing
		$actions = array('view', 'add', 'edit');
		if (in_array($this->action, $actions)) {
			$this->Navigation->addCrumb($this->Session->read('EducationFee.crumbName'));
		}
	}

	public function index($selectedGrade=0) {
		$school_year = $this->Session->read($this->alias.'.school_year_id');

		if ($selectedGrade==0 && $this->Session->check('EducationFee.education_grade_id')) {
			return $this->redirect(
				array('action' => $this->alias,'index',$this->Session->read('EducationFee.education_grade_id'))
				);
		}

		$this->Session->write('EducationFee.education_grade_id', $selectedGrade);

		$educationGradeName = $this->EducationGrade->find(
			'first',
			array(
				'fields' => array('EducationProgramme.name', 'EducationGrade.name'),
				'recursive' => 0,
				'conditions' => array(
					'EducationGrade.id' => $this->Session->read('EducationFee.education_grade_id')
				)
			)
		);
		
		$educationProgrammeName = $educationGradeName['EducationProgramme']['name'];
		$educationGradeName = $educationGradeName['EducationGrade']['name'];
		$SchoolYear = ClassRegistry::init('SchoolYear');
		$schoolYearName = $SchoolYear->find(
			'first',
			array(
				'fields' => array('name'),
				'recursive' => -1,
				'conditions' => array(
					'SchoolYear.id' => $this->Session->read('EducationFee.school_year_id')
				)
			)
		);

		$schoolYearName = $schoolYearName['SchoolYear']['name'];
		$crumbName = $schoolYearName . ' - ' .$educationProgrammeName . ' - ' .$educationGradeName;
		$this->Navigation->addCrumb($crumbName);
		$this->Session->write('EducationFee.crumbName', $crumbName);

		$data = $this->find(
			'all',
			array(
				'recursive' => 0,
				'conditions' => array(
					'school_year_id' => $school_year,
					'education_grade_id' => $selectedGrade
				)
			)
		);

		if(empty($data)) $this->Message->alert('general.view.noRecords', array('type' => 'info'));

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

	public function getByYearAndGrade($schoolYear, $educationGrade) {
		$data = $this->find(
			'all',
			array(
				'recursive' => 0,
				'fields' => array(
					'FeeType.name',
					'EducationFee.amount'
				),
				'conditions' => array(
						'EducationFee.school_year_id'=>$schoolYear,
						'EducationFee.education_grade_id'=>$educationGrade
				),
				'order' => 'FeeType.id asc'
			)
		);
		return $data;
	}

	public function getEducationFeeByStudent($studentId, $options=array()) {
		$ClassStudent = ClassRegistry::init('ClassStudent');
		$ClassStudent->recursive = 1;
		$classStudentData = $ClassStudent->find(
			'all',
			array(
				'fields' => array(
					'SClass.id', 'SClass.name'
				),
				'conditions' => array(
					'ClassStudent.student_id' => $studentId
				)
			)
		);

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
		$rawClassData = $SClass->find(
			'all',
			array(
				'fields' => array(
					'SClass.id', 'SClass.name', 'SClass.created',
					'SchoolYear.id', 'SchoolYear.name'
				),
				'recursive' => 1,
				'conditions' => array(
					'SClass.id' => $classesStudentIsIn
				),
				'order' => array('SchoolYear.name desc')
			)
		);

		if (array_key_exists('educationGradeOptions', $options)) {
			$educationGradeOptions = $options['educationGradeOptions'];
		} else {
			$EducationGrade = ClassRegistry::init('EducationGrade');
			$educationGradeOptions = $EducationGrade->getGradeList();
		}

		$EducationFee = ClassRegistry::init('EducationFee');
		
		foreach($rawClassData as $key => $classData) {
			foreach($classData['ClassGrade'] as $key2 => $classGradeData) {
				if (array_key_exists($rawClassData[$key]['ClassGrade'][$key2]['education_grade_id'], $educationGradeOptions)) {
					$rawClassData[$key]['ClassGrade'][$key2]['name'] = $educationGradeOptions[$rawClassData[$key]['ClassGrade'][$key2]['education_grade_id']];
				}

				$rawClassData[$key]['ClassGrade'][$key2]['fees'] = $EducationFee->getEducationFeesTotalAmount(
					$rawClassData[$key]['SchoolYear']['id'], 
					$rawClassData[$key]['ClassGrade'][$key2]['education_grade_id']
				);
			}
		}
		return $rawClassData;
	}

	public function getEducationFeeByStudentForTxn($studentId) {
		$EducationGrade = ClassRegistry::init('EducationGrade');
		$educationGradeOptions = $EducationGrade->getGradeList();

		$rawClassData = $this->getEducationFeeByStudent($studentId, array('educationGradeOptions'=>$educationGradeOptions));
		$data = array();
		foreach($rawClassData as $key => $classData) {
			foreach($classData['ClassGrade'] as $key2 => $classGradeData) {
				$temp_array = array();
				$temp_array['date'] = $rawClassData[$key]['SClass']['created'];
				$temp_array['item'] = 
					$rawClassData[$key]['SchoolYear']['name'].' - '.
					$rawClassData[$key]['SClass']['name'].' - '.
					$educationGradeOptions[$rawClassData[$key]['ClassGrade'][$key2]['education_grade_id']]
				;
				$temp_array['fee'] = $rawClassData[$key]['ClassGrade'][$key2]['fees'];
				
				array_push($data, $temp_array);
			}
		}
		return $data;
	}

	public function getEducationFees($school_year_id, $education_grade_id) {
		return $this->find(
			'all',
			array(
				'conditions' => array(
					'EducationFee.school_year_id' => $school_year_id,
					'EducationFee.education_grade_id' => $education_grade_id
				)
			)
		);
	}

	public function getEducationFeesTotalAmount($school_year_id, $education_grade_id) {
		$data = $this->find(
			'first',
			array(
				'fields' => 'sum(EducationFee.amount) as total_amount',
				'conditions' => array(
					'EducationFee.school_year_id' => $school_year_id,
					'EducationFee.education_grade_id' => $education_grade_id
				)
			)
		);
		return ($data[0]['total_amount']!='') ? $data[0]['total_amount'] : 0;;
	}
}
