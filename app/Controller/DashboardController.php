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

class DashboardController extends AppController {
	public $institutionSiteId = '1';

	public $uses = array(
		'InstitutionSite',
		'StudentAttendanceDay',
		'StudentAttendanceLesson',
		'StudentAttendanceType',
		'SchoolYear',
		'ClassStudent',
		'EducationGrade'
	);

	public $accessMapping = array(
		'viewMap'=>'read'
	);	
	
	public function beforeFilter() {
		parent::beforeFilter();
	}
	
	public function index() {
		$data = $this->InstitutionSite->find('first');
		$year = date("Y");
		$month = date("m");
		$monthName = date("M");
		// a line chart with the daily attendance by month
		$options = array();//'getPresentOnly'=>true

		$config = ClassRegistry::init('ConfigItem');
		$attendanceView = $config->getValue('attendance_view');

		switch ($attendanceView) {
			case 'Day':
				$attendanceData = $this->StudentAttendanceDay->getAttendanceByMonth($year,$month, $options);
				$modelAttendanceDate = 'StudentAttendanceDay';
				break;
			
			case 'Lesson':
				$attendanceData = array();
				//$this->StudentAttendanceLesson->getAttendanceByMonth($year,$month, $options);
				$modelAttendanceDate = 'ClassLesson';
				break;

			default:
				# code...
				break;
		}
		$attendanceTypeOptions = $this->StudentAttendanceType->find('list');

		// As such the enrollment chart is meant to be enrollment by grade and gender 
		$currentSchoolYear = $this->SchoolYear->getCurrentSchoolYear();

		if(empty($currentSchoolYear)) {
			$currentSchoolYear = $this->SchoolYear->find('first', array('order' => 'start_date desc'));
		}

		$classIdsInYear = array();
		if (array_key_exists('SClass', $currentSchoolYear)) {
			foreach ($currentSchoolYear['SClass'] as $key => $value) {
				array_push($classIdsInYear, $value['id']);
			}
		}

		$studentsInYear = $this->ClassStudent->find(
			'all',
			array(
				'recursive' => -1,
				'fields' => array(
					'EducationGrade.id', 'EducationGrade.name', 'SecurityUser.gender','COUNT(DISTINCT SecurityUser.id) as count'
					),
				'joins' => array(
					array(
						'table' => 'students',
						'alias' => 'Student',
						'conditions' => array('Student.id = ClassStudent.student_id')
					),
					array(
						'table' => 'security_users',
						'alias' => 'SecurityUser',
						'conditions' => array('SecurityUser.id = Student.security_user_id')
					),
					array(
						'table' => 'education_grades',
						'alias' => 'EducationGrade',
						'conditions' => array('EducationGrade.id = ClassStudent.education_grade_id'),
						'order' => 'order asc'
					)

				),
				'conditions' => array(
					'ClassStudent.class_id' => $classIdsInYear
				),
				'group' => 'ClassStudent.education_grade_id, SecurityUser.gender',
			)
		);

		// get all education grades
		$allEducationGrades = $this->EducationGrade->find(
			'list', 
			array(
				'order'=>'order asc'
			)
		);
		$tArray = array();
		$allEducationGradeId = array();
		foreach ($allEducationGrades as $key => $value) {
			array_push($allEducationGradeId, $key);
			array_push($tArray, $value);
		}
		$allEducationGrades = $tArray;
		
		$enrollmentData = array();
		array_push($enrollmentData, array('name'=>$this->Message->getLabel('general.male'), 'data'=>array()));
		array_push($enrollmentData, array('name'=>$this->Message->getLabel('general.female'), 'data'=>array()));

		foreach ($allEducationGradeId as $key => $grade_id) {
			// males
			array_push($enrollmentData[0]['data'], 
				$this->findCountByGradeAndGender($studentsInYear, $grade_id, 'M')
			);
			// females
			array_push($enrollmentData[1]['data'], 
				$this->findCountByGradeAndGender($studentsInYear, $grade_id, 'F')
			);
		}

		$currSchoolYear = (array_key_exists('SchoolYear', $currentSchoolYear))? $currentSchoolYear['SchoolYear']['name']: '';
		$enrolmentTitle = (array_key_exists('SchoolYear', $currentSchoolYear))? $this->Message->getLabel('general.enrollment'). ' - '.$currentSchoolYear['SchoolYear']['name']: $this->Message->getLabel('general.enrollment');

		$this->set('enrollmentYear', $currSchoolYear);
		$this->set('enrollmentGrades', $allEducationGrades);
		$this->set('enrollmentData', $enrollmentData);
		$this->set('enrollmentOptions', array('year'=>$currSchoolYear,'title'=>$enrolmentTitle,'xName'=>$this->Message->getLabel('Student.title')));

		$institutionSiteDataFields = array();
		// array_push($institutionSiteDataFields, array('InstitutionSite','name'));
		array_push($institutionSiteDataFields, array('InstitutionSite','code'));
		array_push($institutionSiteDataFields, array('InstitutionSite','address'));
		array_push($institutionSiteDataFields, array('Country','name'));
		array_push($institutionSiteDataFields, array('InstitutionSite','postal_code'));
		array_push($institutionSiteDataFields, array('InstitutionSite','contact_person'));
		array_push($institutionSiteDataFields, array('InstitutionSite','telephone'));
		array_push($institutionSiteDataFields, array('InstitutionSite','fax'));
		array_push($institutionSiteDataFields, array('InstitutionSite','email'));
		array_push($institutionSiteDataFields, array('InstitutionSite','website'));
		array_push($institutionSiteDataFields, array('InstitutionSite','date_opened'));
		array_push($institutionSiteDataFields, array('InstitutionSite','date_closed'));
		array_push($institutionSiteDataFields, array('InstitutionSite','areaid'));
		array_push($institutionSiteDataFields, array('InstitutionSite','longitude'));
		array_push($institutionSiteDataFields, array('InstitutionSite','latitude'));

		$this->set('data', $data);
		$this->set('attendanceData', $attendanceData);
		$this->set('attendanceTypeOptions', $attendanceTypeOptions);
		$this->set('attendanceOptions', array('title'=>$this->Message->getLabel('general.attendance'). ' - '.$monthName.' '.$year,'yName'=>$this->Message->getLabel('Student.title'),'xName'=>$this->Message->getLabel('general.daysInMonth'),'model'=>$modelAttendanceDate));
		$this->set('attendanceView', $attendanceView);
		$this->set('institutionSiteDataFields', $institutionSiteDataFields);
	}

	private function findCountByGradeAndGender($data,$grade_id,$gender) {
		$found = 0;
		foreach ($data as $key => $value) {
			if ($value['EducationGrade']['id'] == $grade_id && $value['SecurityUser']['gender'] == $gender) {
				$found = $value[0]['count'];
			}
		}
		return $found;
	}

	public function viewMap($id = false) {
		$this->layout = false;
		if ($id)
			$this->institutionSiteId = $id;
		$string = @file_get_contents('http://www.google.com');
		if ($string) {
			$data = $this->InstitutionSite->find('first');
			$this->set('data', $data);
		} else {
			$this->autoRender = false;
		}
		
	}
}
