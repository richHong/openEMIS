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

class ClassGrade extends AppModel {
	public $belongsTo = array(
		'SClass' => array(
			'className' => 'SClass',
			'foreignKey' => 'class_id',
		),
		'EducationGrade'
	);
	
	public function getGradesForEdit($id) {
		$class = get_class($this);
		$options = array();
		
		$options['recursive'] = -1;
		$options['fields'] = array(
			'EducationProgramme.name', 'EducationGrade.id', 'EducationGrade.name', 'EducationGrade.visible',
			'ClassGrade.id', 'ClassGrade.visible'
		);
		
		$options['joins'] = array(
			array(
				'table' => 'education_programmes',
				'alias' => 'EducationProgramme',
				'conditions' => array('EducationProgramme.id = EducationGrade.education_programme_id')
			),
			array(
				'table' => 'class_grades',
				'alias' => 'ClassGrade',
				'type' => 'LEFT',
				'conditions' => array(
					'ClassGrade.education_grade_id = EducationGrade.id',
					'ClassGrade.class_id = ' . $id
				)
			)
		);
		$options['order'] = array('ClassGrade.id DESC', 'EducationGrade.order');
		
		$data = $this->EducationGrade->find('all', $options);
		return $data;
	}

	public function getGradeIDListByClass($classId) {
		$data = $this->find('list', array(
			'fields' => array('id', 'education_grade_id'),
			'conditions' => array('ClassGrade.class_id' => $classId)
		));
		return $data;
	}

	public function getGradeListByClassId($classId){
		$data = $this->find('all', array(
			'fields' => array('ClassGrade.education_grade_id', 'EducationGrade.name', 'EducationProgramme.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_grades',
					'alias' => 'EducationGrade',
					'conditions' => array('EducationGrade.id = ClassGrade.education_grade_id')
				),
				array(
					'table' => 'education_programmes',
					'alias' => 'EducationProgramme',
					'conditions' => array('EducationProgramme.id = EducationGrade.education_programme_id')
				)
			),
			'conditions' => array('ClassGrade.class_id' => $classId, 'ClassGrade.visible' => 1),
			'order' => array('EducationGrade.name')
		));

		$list = array();
		foreach($data as $obj){
			$list[$obj['ClassGrade']['education_grade_id']] = $obj['EducationProgramme']['name'] . ' - ' . $obj['EducationGrade']['name'];
		}

		return $list;
	}

	public function getGradeListByStudentId($studentId){
		$data = $this->find('all', array(
			'fields' => array('ClassGrade.education_grade_id', 'EducationGrade.name', 'EducationProgramme.name'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'education_grades',
					'alias' => 'EducationGrade',
					'conditions' => array('EducationGrade.id = ClassGrade.education_grade_id')
				),
				array(
					'table' => 'education_programmes',
					'alias' => 'EducationProgramme',
					'conditions' => array('EducationProgramme.id = EducationGrade.education_programme_id')
				),
				array(
					'table' => 'class_students',
					'alias' => 'ClassStudent',
					'conditions' => array('ClassStudent.class_id = ClassGrade.class_id')
				)
			),
			'conditions' => array('ClassStudent.student_id' => $studentId),
			'order' => array('EducationGrade.name')
		));
		return $data;
	}
}
