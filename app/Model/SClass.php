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

class SClass extends AppModel {
	public $name = 'SClass';
    public $useTable = 'classes';
	
	public $belongsTo = array(
		'SchoolYear',
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
		'ClassLesson',
		'ClassStudent',
		'ClassSubject',
		'ClassTeacher',
		'Timetable',
		'TimetableEntry'
	);

	public $actsAs = array(
		'ControllerAction',
		'Export' => array('module' => 'SClass')
	);
	
	/*
	public $hasMany = array(
		'ClassGrade' => array(
			'className' => 'ClassGrade',
			'foreignKey' => 'class_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ClassLesson' => array(
			'className' => 'ClassLesson',
			'foreignKey' => 'class_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ClassStudent' => array(
			'className' => 'ClassStudent',
			'foreignKey' => 'class_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Timetable' => array(
			'className' => 'Timetable',
			'foreignKey' => 'class_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'TimetableEntry' => array(
			'className' => 'TimetableEntry',
			'foreignKey' => 'class_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
		),
		'ClassEvent' => array(
			'className' => 'ClassEvent',
			'foreignKey' => 'class_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ClassSubject' => array(
			'className' => 'ClassSubject',
			'foreignKey' => 'class_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ClassTeacher' => array(
			'className' => 'ClassTeacher',
			'foreignKey' => 'class_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	*/

    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'name' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('className')
                )
            ),
            'school_year_id' => array(
				'required' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('schoolYear')
				)
            ),
            'seats_total' => array(
                'required' => array(
                    'rule' => 'numeric',
                    'message' => $this->getErrorMessage('totalSeats')
                )
            )
        );
    }
	
	public function getFields($options=array()) {
		parent::getFields();
		
		$yearOptions = $this->SchoolYear->getYearList();
		$this->fields['school_year_id']['labelKey'] = 'SchoolYear';
		$this->fields['school_year_id']['type'] = 'select';
		$this->fields['school_year_id']['options'] = $yearOptions;
		
		if (array_key_exists('action', $options)) {
			 if ($options['action'] == 'edit') {
			 	$this->fields['school_year_id']['type'] = 'disabled';
			 }
		}
		
		return $this->fields;
	}

	public function getClassList($type='name', $order='ASC') {
		$value = 'SClass.' . $type;
		$result = $this->find('list', array(
			'fields' => array('SClass.id', $value),
			'order' => array($value . ' ' . $order)
		));
		return $result;
	}

	public function getClassListSchoolYear(){
		$data = $this->find('all', array(
			'fields' => array('SClass.id', 'SClass.name', 'SchoolYear.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'school_years',
					'alias' => 'SchoolYear',
					'conditions' => array('SchoolYear.id = SClass.school_year_id')
				)
			),
			'order' => array('SchoolYear.name', 'SClass.name')
		));

		$list = array();
		foreach($data as $obj){
			$list[$obj['SClass']['id']] = $obj['SchoolYear']['name'] . ' - ' . $obj['SClass']['name'];
		}

		return $list;
	}

	public function getClassListBySubjectId($id){
		
		$options['joins'] = array(
			array(
				'table' => 'class_subjects',
				'alias' => 'ClassSubject',
				'conditions' => array('ClassSubject.class_id = SClass.id')
			),
			array(
				'table' => 'education_grades_subjects',
				'alias' => 'EducationGradeSubject',
				'conditions' => array('ClassSubject.education_grade_subject_id = EducationGradeSubject.id')
			)
		);
		
		$options['fields'] = array('SClass.id', 'SClass.name');
		$options['conditions'] = array('EducationGradeSubject.education_subject_id' => $id);
		
		$data = $this->find('list', $options);
		
		return $data;
	}
	
	public function autoComplete($className){
		$options['recursive'] = -1;
		$options['fields'] = array('SClass.id', 'SClass.name');
		$options['conditions'] = array('SClass.name LIKE' =>$className.'%');
		
		$data = $this->find('list', $options);
		
		return $data;
	}
	
	public function getClassIdByName($className){
		$options['recursive'] = -1;
		$options['fields'] = array('SClass.id', 'SClass.name');
		$options['conditions'] = array('SClass.name' =>$className);
		
		$data = $this->find('first', $options);
		
		return $data;
	}

	public function getClassInfoListBySchoolYearId($id){
		$options['recursive'] = -1;
		$options['conditions'] = array('SClass.school_year_id' =>$id);
		$options['order'] = array('name ASC');
		$classesList = $this->find('all', $options);
		
		$ClassGrade = ClassRegistry::init('ClassGrade');
		$ClassStudent = ClassRegistry::init('ClassStudent');
		$data = array();
		foreach($classesList as $class){
			$classId = $class['SClass']['id'];
			$classGradeData = $ClassGrade->getGradeListByClassId($classId);
			$classStudentTotalData = $ClassStudent->getTotalStudiedStudentByClassId($classId);
			
			$_tempArr = array();
			$_tempArr['class_id'] = $classId;
			$_tempArr['name'] = $class['SClass']['name'];
			$_tempArr['program'] = $classGradeData;
			$_tempArr['available_seats'] = intval($class['SClass']['seats_total']) - intval($classStudentTotalData[0]['total_students']);
			$_tempArr['total_seats'] = $class['SClass']['seats_total'];
			
			array_push($data, $_tempArr);
		}
		
		return $data;
	}
}
