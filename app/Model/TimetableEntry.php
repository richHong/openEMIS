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
/**
 * TimetableEntry Model
 *
 * @property Class $Class
 * @property Room $Room
 * @property EducationGradeSubject $EducationGradeSubject
 * @property Staff $Staff
 * @property Timetable $Timetable
 */
class TimetableEntry extends AppModel {


    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'education_subject_id' => array(
                'rule' => 'checkDropdownData',
                'required' => true,
                'message' => $this->getErrorMessage('subject')
            ),
            'room_id' => array(
                'rule' => 'checkDropdownData',
                'required' => true,
                'message' => $this->getErrorMessage('location')
            ),
            'staff_id' => array(
                'rule' => 'checkDropdownData',
                'required' => true,
                'message' => $this->getErrorMessage('teacher')
            ),
        );
    }
	
	/* Jamie Sim */
	public function populateClassLessons($timetableId, $affectedStartDate, $modifiedDate = NULL){
		if(!$modifiedDate){
			$modifiedDate = date('Y-m-d');
		}
    	
    	$data = $this->find('all', array(
			'fields' => array('TimetableEntry.id', 'TimetableEntry.class_id', 'TimetableEntry.room_id', 'TimetableEntry.education_grade_subject_id', 'TimetableEntry.staff_id', 'TimetableEntry.start_time', 'TimetableEntry.end_time', 'TimetableEntry.day_of_week'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'timetables',
					'alias' => 'Timetable',
					'conditions' => array('Timetable.id = TimetableEntry.timetable_id')
				)
			),
			'conditions' => array('Timetable.id' => $timetableId),	
			'order' => array('TimetableEntry.day_of_week', 'TimetableEntry.start_time','TimetableEntry.id')
		));
   
 		//var_dump($modifiedDate);                                                 
 		//var_dump($affectedStartDate);
    	
 		//$affectedStartDate = '2013-09-13';
		$dateDiff = strtotime($modifiedDate) - (strtotime($affectedStartDate));
		$dateDiff =  floor($dateDiff/3600/24);
    	//var_dump($dateDiff);

    	if($dateDiff<0){
    		$dateDiff = 0;
    	}

    	$i = 0;     
		for($i=0; $i<intval($dateDiff);$i++){
			$currentDateTime = strtotime("+" . $i . " day" , strtotime($affectedStartDate));
			$currentDate = date('Y-m-d',  $currentDateTime);
			$currentDay = date("N", $currentDateTime);
			foreach($data as $obj){
				if($currentDay == $obj['TimetableEntry']['day_of_week']){
					$this->addClassLessonByDate($obj['TimetableEntry']['id'], $currentDate, $obj['TimetableEntry']['class_id']);
				}
	    		
	    	}
	    }
    }

    /* Jamie Sim */

    public function addClassLessonByDate($timetableEntryId, $lessonDate, $classId = NULL){
    	
    	if(!empty($lessonDate)){
    		$condition = array('TimetableEntry.id' => $timetableEntryId);
    		
    		if(!empty($classId)){
    			$condition = array('TimetableEntry.id' => $timetableEntryId, 'TimetableEntry.class_id' => $classId);
    		}

	    	$data = $this->find('all', array(
				'fields' => array('TimetableEntry.id', 'TimetableEntry.class_id', 'TimetableEntry.room_id', 'TimetableEntry.education_grade_subject_id', 'TimetableEntry.staff_id', 'ClassLesson.id', 'ClassLesson.start_time', 'ClassLesson.end_time', 'TimetableEntry.start_time', 'TimetableEntry.end_time', 'TimetableEntry.day_of_week'),
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'class_lessons',
						'type' => 'LEFT',
						'alias' => 'ClassLesson',
						'conditions' => array('TimetableEntry.id = ClassLesson.timetable_entry_id', 'ClassLesson.start_time >=' => $lessonDate, 'ClassLesson.start_time <' => date("Y-m-d", strtotime("+1 day", strtotime($lessonDate))))
					)
				),
				'conditions' => $condition,	
				'order' => array('ClassLesson.start_time', 'TimetableEntry.day_of_week', 'TimetableEntry.start_time')
			));

			$cl = ClassRegistry::init('ClassLesson');

	    	foreach($data as $obj){
				$startTime = $lessonDate . ' ' . $obj['TimetableEntry']['start_time'];
    			$endTime = $lessonDate . ' ' . $obj['TimetableEntry']['end_time'];
    			$classId = $obj['TimetableEntry']['class_id'];
				$roomId= $obj['TimetableEntry']['room_id']; 
				$subjectId = $obj['TimetableEntry']['education_grade_subject_id'];
				$staffId = $obj['TimetableEntry']['staff_id'];
				$timeEntryId= $obj['TimetableEntry']['id'];


			

				if(!isset($obj['ClassLesson']['id'])){
					
			    	/*$query = sprintf("INSERT INTOclass_lessons (start_time, end_time, class_id, room_id, education_grade_subject_id, staff_id, timetable_entry_id, modified_user_id, modified, created_user_id, created) ".
								 "VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', NULL, NULL, '%s', NOW())", $startTime, $endTime, $classId, $roomId, $subjectId, $staffId, $timeEntryId, 0);
					//$ret = $this->query($query); 
					*/

					$data = array(
						'ClassLesson'=>
							array(
								'start_time' => $startTime,
								'end_time' => $endTime,
								'class_id' => $classId,
								'room_id' => $roomId,
								'education_grade_subject_id' => $subjectId,
								'staff_id' => $staffId,
								'timetable_entry_id' => $timeEntryId
							)
					);

					$cl->saveAll($data);

					//pr('INSERT');
				}
			}
		}
    }
    

	function createDateRangeArray($startDate, $endDate){
		$startDateTimestamp = strtotime($startDate);
		$endDateTimestamp = strtotime($endDate);
		
		$secInDay = 24*60*60;
		
		$results = array();
		
		while($startDateTimestamp <= $endDateTimestamp){
			
			$tempData = array();
			$tempData['date'] = date('Y-m-d', $startDateTimestamp);
			$tempData['day_of_week'] = date('N', $startDateTimestamp);
			
			array_push($results, $tempData);
			
			$startDateTimestamp+=$secInDay;
		}
		
		return $results;
	}

	public function getClassTimetable($classId, $startDate, $endDate, $filters = array()){
		
		$options['recursive'] = -1;
		$options['fields'] = array('Timetable.*','TimetableEntry.*','EducationGradeSubject.*');
		$options['order'] = array('TimetableEntry.day_of_week ASC', 'TimetableEntry.start_time ASC');
		$options['joins'] = array(
			array(
				'table' => 'timetables',
				'type' => 'LEFT',
				'alias' => 'Timetable',
				'conditions' => array(
					'Timetable.id = TimetableEntry.timetable_id',
					
					/*'AND' => array(
						'Timetable.start_date <=' => $startDate,
						'Timetable.end_date >=' => $endDate
					),*/
					/*'AND' => array(
						array(
							'OR' => array(
								'Timetable.start_date <=' => $startDate,
								'Timetable.start_date >' => $startDate,
							)
						)
					)*/
				)
			),
			array(
				'table' => 'education_grades_subjects',
				'type' => 'LEFT',
				'alias' => 'EducationGradeSubject',
				'conditions' => array(
					'EducationGradeSubject.id = TimetableEntry.education_grade_subject_id',
				)
			)
		);
		
		$options['conditions'] = array(
			'Timetable.class_id' => $classId,
			'AND' => array(
				'Timetable.start_date <=' => $startDate,
				'Timetable.end_date >=' => $startDate
			)
		);
		if(!empty($filters['education_grade_subject_id'])){
			$options['conditions']['EducationGradeSubject.id'] = $filters['education_grade_subject_id'];
		}
		if(!empty($filters['education_subject_id'])){
			$options['conditions']['EducationGradeSubject.education_subject_id'] = $filters['education_subject_id'];
		}
		if(!empty($filters['staff_id'])){
			$options['conditions']['TimetableEntry.staff_id'] = $filters['staff_id'];
		}
		
		$data = $this->find('all', $options);
	//pr($data);die;
		$results = array();
		
		if(!empty($data)){
			//$dateRange = $this->createDateRangeArray($data[0]['Timetable']['start_date'],$data[0]['Timetable']['end_date']);
			$dateRange = $this->createDateRangeArray($startDate,$endDate);
		//	pr($dateRange);
			foreach($dateRange as $day){
				foreach($data as $obj){
					$timetableEndDate = strtotime($obj['Timetable']['end_date'].' 23:59:59');
					$checkEndDate = strtotime($day['date']);
					//echo $obj['TimetableEntry']['day_of_week'] . ' == '.$day['day_of_week'].'<br/>';
					//echo $timetableEndDate . ' >= '.$checkEndDate.'<br/>';
					if($obj['TimetableEntry']['day_of_week'] == $day['day_of_week'] /*&& $timetableEndDate >= $checkEndDate*/  ){
						/*echo $obj['TimetableEntry']['day_of_week'] . ' == '.$day['day_of_week'].'<br/>';
						echo $timetableEndDate . ' >= '.$checkEndDate.'<br/>';
						echo 'in'.'<br/>';*/
						$tempData = array();
						$tempData['date'] = $day['date'];
						$tempData['start_time'] = $obj['TimetableEntry']['start_time'];
						$tempData['end_time'] = $obj['TimetableEntry']['end_time'];
						$tempData['education_grade_subject_id'] = $obj['TimetableEntry']['education_grade_subject_id'];
						$tempData['class_id'] = $obj['TimetableEntry']['class_id'];
						$tempData['timetable_entry_id'] = $obj['TimetableEntry']['id'];
						$tempData['staff_id'] = $obj['TimetableEntry']['staff_id'];
						$tempData['room_id'] = $obj['TimetableEntry']['room_id'];
						$tempData['timetable_entry_id'] = $obj['TimetableEntry']['id'];
						array_push($results, $tempData);
					}
				}
			}
		}
		// pr($results);
		return $results;
	}
	
	public function getSingleSelectedClassTimetableEntry($selectedStartTime, $classId, $educationGradeSubjectId){
		$startTime = date("H:i:s", $selectedStartTime);
		$dayOfWeek = date("N", $selectedStartTime);
		//pr('-->>'.$dayOfWeek);
		$data = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'class_id' => $classId,
				'start_time' => $startTime,
				'day_of_week' => $dayOfWeek,
				'education_grade_subject_id' => $educationGradeSubjectId
			)
		));
		
		$data['TimetableEntry']['date'] = date("Y-m-d", $selectedStartTime);
		
		return $data;
	}
	
	public function sortTimetableEntriesData($data){
		$newData = array();
		foreach($data as $key => $TimetableEntry){
			$newData[$TimetableEntry['date']] = date("j F Y", strtotime($TimetableEntry['date']));
		}
		return $newData;
	}
	
	public function getAllSelectedClassTimetableEntry($id, $type = 'timetable_id'){
		$data = $this->find('all', array(
				'recursive' => -1,
				'fields' => array('TimetableEntry.*','EducationSubject.*','Room.name', 'SecurityUser.first_name', 'SecurityUser.middle_name', 'SecurityUser.last_name', 'SecurityUser.openemisid'),
				'conditions' => array(
					//'TimetableEntry.day_of_week BETWEEN  ? AND ?' => array(1, 7),
					//'TimetableEntry.class_id =' => $id,
					'TimetableEntry.'.$type.' =' => $id
				),
				'joins' => array(
					array(
						'table' => 'education_grades_subjects',
						'type' => 'LEFT',
						'alias' => 'EducationGradeSubject',
						'conditions' => array('EducationGradeSubject.id = TimetableEntry.education_grade_subject_id')
					),
					array(
						'table' => 'education_subjects',
						'type' => 'LEFT',
						'alias' => 'EducationSubject',
						'conditions' => array('EducationGradeSubject.education_subject_id = EducationSubject.id')
					),
					array(
						'table' => 'rooms',
						'type' => 'LEFT',
						'alias' => 'Room',
						'conditions' => array('TimetableEntry.room_id = Room.id')
					),
					array(
						'table' => 'staff',
						'alias' => 'Staff',
						'conditions' => array('Staff.id = TimetableEntry.staff_id')
					),
					array(
						'table' => 'security_users',
						'alias' => 'SecurityUser',
						'conditions' => array('SecurityUser.id = Staff.security_user_id')
					)
				),
				'order' => array('TimetableEntry.day_of_week' => 'asc','TimetableEntry.start_time' => 'asc' )
			)
		);
		
		return $data;
	}
	
	public function getSelectedTimetableEntryFullDetails($id){
	//	$data = $this->findById($id);
		$data = $this->find('first', array(
				'conditions' => array('TimetableEntry.id'=>$id),
				'recursive' => -1,
				'fields' => array('TimetableEntry.*', 'EducationSubject.*'),
				'joins' => array(
					array(
						'table' => 'education_grades_subjects',
						'type' => 'LEFT',
						'alias' => 'EducationGradeSubject',
						'conditions' => array('EducationGradeSubject.id = TimetableEntry.education_grade_subject_id')
					),
					array(
						'table' => 'education_subjects',
						'type' => 'LEFT',
						'alias' => 'EducationSubject',
						'conditions' => array('EducationGradeSubject.education_subject_id = EducationSubject.id')
					)
				)
			)
		);
		
		return $data;
	}


	function getDistinctClassLesson($classId, $startDate, $endDate, $day, $subjectId, $staffId){
		$startWeek = date('N', strtotime($startDate));
		$endWeek = date('N', strtotime($endDate));
		$startWeekNum = date("W", strtotime($startDate));
		$endWeekNum = date("W", strtotime($endWeek));

		$diff = strtotime($endDate, 0) - strtotime($startDate, 0);
		$diff = floor($diff / 604800);

		if($diff>=1){
			$startWeek = 1;
			$endWeek = 7;
		}

		$conditions = array('ClassLesson.class_id'=>$classId, 'ClassLesson.start_time >= "' . $startDate . '"', 'ClassLesson.start_time <= "' . $endDate . '"');
		if(!empty($subject_id)){
			$conditions['ClassLesson.subject_id'] = $subject_id;
		}
		if(!empty($teacher_id)){
			$conditions['ClassLesson.staff_id'] = $staff_id;
		}
		$dbo = $this->getDataSource();
		$subQuery = $dbo->buildStatement(
	        array(
	            'fields' => array('ClassLesson.id', 'ClassLesson.start_time', 'ClassLesson.end_time', 'ClassLesson.staff_id', 'ClassLesson.education_grade_subject_id', 'ClassLesson.room_id', 'ClassLesson.timetable_entry_id', 'ClassLesson.week_number'),
	            'table' => $dbo->fullTableName('class_lessons'),
	            'alias' => 'ClassLesson',
	            'limit' => null,
	            'offset' => null,
	            'conditions' => $conditions,
	            'order' =>array('ClassLesson.start_time', 'ClassLesson.end_time'),
	            'group' => null
	        ),
	        $this->TimetableEntry
	    );
	    $query = $subQuery;

	    $classLessonData = $this->query($query);


	    $conditions = array('TimetableEntry.day_of_week BETWEEN  ? AND ?' => array($startWeek, $endWeek), 'TimetableEntry.class_id =' => $classId);
		if(!empty($day)){
			$conditions['TimetableEntry.day_of_week'] = $day;
		}
		if(!empty($subject_id)){
			$conditions['TimetableEntry.subject_id'] = $subject_id;
		}
		if(!empty($teacher_id)){
			$conditions['TimetableEntry.staff_id'] = $staff_id;
		}
	 	$this->getDataSource();
       	$subQuery = $dbo->buildStatement(
           array(
               'fields' => array('TimetableEntry.id', 'TimetableEntry.start_time', 'TimetableEntry.end_time', 'TimetableEntry.day_of_week', 'TimetableEntry.staff_id', 'TimetableEntry.education_grade_subject_id', 'TimetableEntry.id', 'TimetableEntry.room_id'),
               'table' => $dbo->fullTableName($this),
               'alias' => 'TimetableEntry',
               'limit' => null,
               'offset' => null,
               'conditions' => $conditions,
               'order' =>array('TimetableEntry.day_of_week', 'TimetableEntry.start_time', 'TimetableEntry.end_time'),
               'group' => null
           ),
           $this->TimetableEntry
       	);

    	$query = $subQuery;

		$timeTableEntryData = $this->query($query);


		$diff = strtotime($endDate, 0) - strtotime($startDate, 0);
		$diff = floor($diff /3600/24);

		$data = array();
		for($i=0;$i<$diff;$i++){
			foreach($timeTableEntryData as $obj){
				$temp = $obj['TimetableEntry'];
				$formatDate = date('Y-m-d', strtotime('+' . $i . ' day', strtotime($startDate)));
				$week = date('N', strtotime($formatDate));
				$dataTemp = array();
				if($temp['day_of_week'] == date('N', strtotime($formatDate))){
					$dataTemp = $temp;
					$dataTemp['start_time'] =  $formatDate . ' ' . $temp['start_time'];	
				}
				foreach($classLessonData as $obj2){
					$temp2 = $obj2['ClassLesson'];	
					$removeKey = false;
					if($temp2['timetable_entry_id'] == $temp['id'] && $formatDate == date('Y-m-d', strtotime($temp2['start_time']))){
						$dataTemp = $temp2;
						$dataTemp['day_of_week'] = 0; 
						$removeKey = true;
					}else if($temp2['timetable_entry_id'] == $temp['id'] && $temp2['week_number'] == date('W', strtotime($formatDate))){
						$dataTemp = array();
						break;
					}else if($temp2['timetable_entry_id'] == 0 && $formatDate == date('Y-m-d', strtotime($temp2['start_time']))){
						$dataInsertTemp = $temp2;
						$dataInsertTemp['day_of_week'] = 0; 
						$data[]['Lesson'] = $dataInsertTemp;
						$removeKey = true;
					}
					if($removeKey){
						unset($classLessonData[0]);
						break;
					}
				}
				if(!empty($dataTemp)){
					$data[]['Lesson'] = $dataTemp;
				}	

			}
	   }

		usort($data, array(&$this, "cmp"));
    	//$query = "SELECT * FROM (" . $query . ")Lesson ORDER BY day_of_week, start_time, end_time";
       	return $data;
	}


	public function cmp($a, $b){ 
	    return strcmp(strtotime($a['Lesson']['start_time']), strtotime($b['Lesson']['start_time'])); 
	}
	
	public function getAllSelectedClassTimetableEntryByStaffId($staffId, $timetableId){
		$data = $this->find('all', array(
				'recursive' => -1,
				'fields' => array('TimetableEntry.*','EducationSubject.*','Room.name', 'SecurityUser.first_name', 'SecurityUser.middle_name', 'SecurityUser.last_name', 'SecurityUser.openemisid'),
				'conditions' => array(
					//'TimetableEntry.day_of_week BETWEEN  ? AND ?' => array(1, 7),
					//'TimetableEntry.class_id =' => $id,
					'TimetableEntry.staff_id' => $staffId,
					'TimetableEntry.timetable_id' => $timetableId
				),
				'joins' => array(
					array(
						'table' => 'education_grades_subjects',
						'type' => 'LEFT',
						'alias' => 'EducationGradeSubject',
						'conditions' => array('EducationGradeSubject.id = TimetableEntry.education_grade_subject_id')
					),
					array(
						'table' => 'education_subjects',
						'type' => 'LEFT',
						'alias' => 'EducationSubject',
						'conditions' => array('EducationGradeSubject.education_subject_id = EducationSubject.id')
					),
					array(
						'table' => 'rooms',
						'type' => 'LEFT',
						'alias' => 'Room',
						'conditions' => array('TimetableEntry.room_id = Room.id')
					),
					array(
						'table' => 'staff',
						'alias' => 'Staff',
						'conditions' => array('Staff.id = TimetableEntry.staff_id')
					),
					array(
						'table' => 'security_users',
						'alias' => 'SecurityUser',
						'conditions' => array('SecurityUser.id = Staff.security_user_id')
					)
				),
				'order' => array('TimetableEntry.day_of_week' => 'asc','TimetableEntry.start_time' => 'asc' )
			)
		);
		
		return $data;
	}
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'SClass' => array(
			'className' => 'classes',
			'foreignKey' => 'class_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Room' => array(
			'className' => 'Room',
			'foreignKey' => 'room_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EducationGradesSubject' => array(
			'className' => 'EducationGradesSubject',
			'foreignKey' => 'education_grade_subject_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Staff' => array(
			'className' => 'Staff',
			'foreignKey' => 'staff_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Timetable' => array(
			'className' => 'Timetable',
			'foreignKey' => 'timetable_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/*public $validate = array(
		'education_grades_subject_id' => array(
			'rule'		=> 'notEmpty',
			'required'	=> true,
		),
		'staff_id' => array(
			'rule'		=> 'notEmpty',
			'required'	=> true,
		),
		'room_id' => array(
			'rule'		=> 'notEmpty',
			'required'	=> true,
		)
	);
	*/
}
?>
