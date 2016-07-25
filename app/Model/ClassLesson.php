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

class ClassLesson extends AppModel {

    public $hasMany = array('StudentAttendanceLesson');
    public $belongsTo = array(
        'SClass' => array(
            'className' => 'SClass',
            'foreignKey' => 'class_id',
            //'conditions' => '',
            //'fields' => '',
            //'order' => ''
        ),
        'Room',
        'EducationGradesSubject' => array('foreignKey' => 'education_grade_subject_id'),
        'Staff',
        'LessonStatus'
    );
    public $actsAs = array(
        'ControllerAction',
        // 'DatePicker' => array('date_of_lesson'),
        // 'TimePicker' => array('time_of_lesson') // for fixing time format from timepicker
    );

    public function __construct() {
        parent::__construct();

        $this->validate = array(
            'start_time' => array(
                'ruleNotLater' => array(
                    'rule' => array('compareDate', 'end_time'),
                    'message' => $this->getErrorMessage('startTime')
                ),
            ),
            'education_grade_subject_id' => array(
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
            'lesson_status_id' => array(
                'rule' => 'checkDropdownData',
                'required' => true,
                'message' => $this->getErrorMessage('status')
            )
        );
    }

    function beforeAction() {
        parent::beforeAction();
        $this->Navigation->addCrumb($this->Message->getLabel('ClassLesson.title'));
        /** EVENTUALLY MUST PUT THIS ENTIRE CHUNK IN GETFIELDS() **/
        $id = $this->controller->Session->read('SClass.id');

        $this->fields['class_id']['type'] = 'hidden';
        $this->fields['class_id']['value'] = $this->controller->Session->read('SClass.id');
        $this->fields['timetable_entry_id']['type'] = 'hidden';
        $this->fields['timetable_entry_id']['value'] = 0;

        $this->fields['start_date']['model'] = 'ClassLesson';
        $this->fields['start_date']['visible'] = true;
        $this->fields['start_date']['type'] = 'date';
        $this->fields['start_time']['type'] = 'time';
        $this->fields['end_time']['type'] = 'time';

        $ClassGrade = ClassRegistry::init('ClassSubject');
        $this->fields['education_grade_subject_id']['type'] = 'select';
        $subjectByClass = $ClassGrade->getSubjectByClass($id);
        $educationGradeSubjectOptions = array();
        foreach ($subjectByClass as $key => $value) {
        	$educationGradeSubjectOptions[$value['ClassSubject']['education_grade_subject_id']] = $value['EducationSubject']['code'].' - '.$value['EducationSubject']['name'];
        }
        $this->fields['education_grade_subject_id']['options'] = $educationGradeSubjectOptions;

        $ClassTeacher = ClassRegistry::init('ClassTeacher');
        $staffByClass = $ClassTeacher->getTeacherByClass($id);
        $staffOptions = array();
        foreach ($staffByClass as $key => $value) {
        	$staffOptions[$value['Staff']['id']] = $this->Message->getFullName($value);
        }
        $this->fields['staff_id']['type'] = 'select';
        $this->fields['staff_id']['options'] = $staffOptions;

        $Room = ClassRegistry::init('Room');
        $roomOptions = $Room->getRoomList();
        $this->fields['room_id']['type'] = 'select';
        $this->fields['room_id']['options'] = $roomOptions;

        $LessonStatus = ClassRegistry::init('LessonStatus');
        $statusOptions = $LessonStatus->getOptions('name', 'order', 'asc', array('visible' => 1));
        $this->fields['lesson_status_id']['type'] = 'select';
        $this->fields['lesson_status_id']['options'] = $statusOptions;

        $order = 1;
        $this->setFieldOrder('start_date', $order++);
        $this->setFieldOrder('start_time', $order++);
        $this->setFieldOrder('end_time', $order++);
        $this->setFieldOrder('education_grade_subject_id', $order++);
        $this->setFieldOrder('staff_id', $order++);
        $this->setFieldOrder('room_id', $order++);
        $this->setFieldOrder('lesson_status_id', $order++);
    }

    function field_comparison($check1, $operator, $field2) {
        foreach ($check1 as $key => $value1) {
            $value2 = $this->data[$this->alias][$field2];
            if (!Validation::comparison($value1, $operator, $value2))
                return false;
        }
        return true;
    }

    function filterEmptyData($data) {
        $newFilteredData = array();

        foreach ($data as $obj) {
            if (!empty($obj)) {
                $newFilteredData[] = $obj;
            }
        }

        return $newFilteredData;
    }

    /*
      function periodTimeDisplayFormat($startTime, $endTime){
      $formatedDate = date("[d/m/Y]", strtotime($startTime));
      $formatedStartTime = date("h:ia", strtotime($startTime));
      $formatedEndTime = date("h:ia", strtotime($endTime));
      $timeslot =  $formatedDate." ".$formatedStartTime." - ".$formatedEndTime;

      return $timeslot;
      } */

    function filterPeriodData($startTime, $endTime) {
        $formatedDate = date("Y-m-d", strtotime($startTime));
        $formatedStartTime = date("h:ia", strtotime($startTime));
        $formatedEndTime = date("h:ia", strtotime($endTime));
        $timeslot = $formatedStartTime . " - " . $formatedEndTime;

        return array('date' => $formatedDate, 'time' => $timeslot);
    }

    public function getLessonPeriod($classId, $timetableSourceData, $startDate, $endDate, $filters = array()) {
        //public function getLessonPeriod($classId, $educationSubjectId = NULL, $timetableSourceData, $startDate, $endDate){
        $options['recursive'] = -1;
        $options['fields'] = array('ClassLesson.*', 'EducationGradesSubject.*');
        $options['conditions'] = array('ClassLesson.class_id' => $classId, 'ClassLesson.start_time >=' => $startDate, 'ClassLesson.start_time <=' => $endDate,);


        if (!empty($filters['education_grade_subject_id'])) {
            $options['conditions']['EducationGradesSubject.id'] = $filters['education_grade_subject_id'];
        }
        if (!empty($filters['education_subject_id'])) {
            $options['conditions']['EducationGradesSubject.education_subject_id'] = $filters['education_subject_id'];
        }
        $options['joins'] = array(
            array(
                'table' => 'education_grades_subjects',
                'alias' => 'EducationGradesSubject',
                'conditions' => array(
                    'EducationGradesSubject.id = ClassLesson.education_grade_subject_id',
                /* 'AND' => array(
                  'ClassLesson.start_time >=' => $startDate,
                  'ClassLesson.start_time <=' => $endDate,
                  ) */
                )
            ),
        );
        //$options['fields'] = array('ClassLesson.*','EducationGradesSubject.*');

        $data = $this->find('all', $options);

        $results = array();

        for ($r = 0; $r < count($timetableSourceData); $r++) {
            $oriClassLesson = $timetableSourceData[$r];

            $oriLessonStartTime = $oriClassLesson['date'] . " " . $oriClassLesson['start_time'];
            $oriLessonEndTime = $oriClassLesson['date'] . " " . $oriClassLesson['end_time'];

            if (empty($data)) {
                $timeslot = $this->filterPeriodData($oriLessonStartTime, $oriLessonEndTime);
                $this->setupLessonPeriodStucture($oriLessonStartTime, $timeslot, $oriClassLesson, $results, $filters);
            } else {
                //Find Adhoc classes where @timetable_entry_id = 0 or NULL;
                //When @timetable_entry_id != 0, look for match classes
                for ($i = 0; $i < count($data); $i++) {
                    $classLesson = $data[$i];

                    if (!empty($classLesson)) {
                        $classLessonStartTime = $classLesson['ClassLesson']['start_time'];
                        $classLessonEndTime = $classLesson['ClassLesson']['end_time'];

                        //Setup the display time method
                        $timeslot = $this->filterPeriodData($classLessonStartTime, $classLessonEndTime);

                        if (empty($classLesson['ClassLesson']['timetable_entry_id'])) {
                            //pr('timetabke entry id == 0');
                            $this->setupLessonPeriodStucture($classLessonStartTime, $timeslot, $classLesson['ClassLesson'], $results, $filters);

                            //set the data to blank when data is found
                            $data[$i] = NULL;
                        } else {
                            if ($classLessonStartTime == $oriLessonStartTime) {
                                $this->setupLessonPeriodStucture($classLessonStartTime, $timeslot, $classLesson['ClassLesson'], $results, $filters);

                                //set the data to blank when data is found
                                $timetableSourceData[$r] = NULL;
                                $data[$i] = NULL;
                            }
                        }
                    }
                }//End for data loop
            }// End if(empty($data))
        }//End timetableSourceData loop
        //clean up the data
        $data = $this->filterEmptyData($data);
        $timetableSourceData = $this->filterEmptyData($timetableSourceData);

        for ($i = 0; $i < count($data); $i++) {
            $classLesson = $data[$i];

            $classLessonStartTime = $classLesson['ClassLesson']['start_time'];
            $classLessonEndTime = $classLesson['ClassLesson']['end_time'];

            //Setup the display time method
            $timeslot = $this->filterPeriodData($classLessonStartTime, $classLessonEndTime);
            $this->setupLessonPeriodStucture($classLessonStartTime, $timeslot, $classLesson['ClassLesson'], $results, $filters);
        }

        for ($r = 0; $r < count($timetableSourceData); $r++) {
            $oriClassLesson = $timetableSourceData[$r];

            $oriLessonStartTime = $oriClassLesson['date'] . " " . $oriClassLesson['start_time'];
            $oriLessonEndTime = $oriClassLesson['date'] . " " . $oriClassLesson['end_time'];

            $timeslot = $this->filterPeriodData($oriLessonStartTime, $oriLessonEndTime);

            $this->setupLessonPeriodStucture($oriLessonStartTime, $timeslot, $oriClassLesson, $results, $filters);
        }
        ksort($results);

        return $results;
    }

    function setupLessonPeriodStucture($startTime, $timeslot, $data, &$results, $filters = array()) {
        if (!empty($filters['staff_id']) && $filters['staff_id'] != $data['staff_id']) {
            //$options['conditions']['ClassLesson.staff_id'] = $filters['staff_id'];
            return;
        }

        $results[strtotime($startTime)] = $timeslot;
        if (!empty($data['id']) && isset($data['id'])) {
            $results[strtotime($startTime)]['id'] = $data['id'];
        }
        $results[strtotime($startTime)]['education_grade_subject_id'] = $data['education_grade_subject_id'];
        $results[strtotime($startTime)]['room_id'] = $data['room_id'];
        $results[strtotime($startTime)]['staff_id'] = $data['staff_id'];
        $results[strtotime($startTime)]['timetable_entry_id'] = empty($data['timetable_entry_id']) ? 0 : $data['timetable_entry_id'];
    }

    public function getSelectedLessonPeriod($classId, $educationSubjectId, $startDate) {
        $options['recursive'] = -1;
        $options['conditions'] = array('ClassLesson.class_id' => $classId, 'ClassLesson.start_time' => $startDate);
        $options['joins'] = array(
            array(
                'table' => 'education_grades_subjects',
                'alias' => 'EducationGradesSubject',
                'conditions' => array(
                    'EducationGradesSubject.id = ClassLesson.education_grade_subject_id',
                    'EducationGradesSubject.education_subject_id' => $educationSubjectId,
                )
            ),
        );
        $data = $this->find('first', $options);

        if (empty($data)) {
            //Nothing found in ClassLessonDB
            $educationGradeSubject = ClassRegistry::init('EducationGradesSubject');

            $educationGradeSubjectData = $educationGradeSubject->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'class_subjects',
                        'alias' => 'ClassSubject',
                        'conditions' => array(
                            'ClassSubject.education_grade_subject_id = EducationGradesSubject.id',
                        )
                    )
                ),
                'conditions' => array('ClassSubject.class_id' => $classId, 'EducationGradesSubject.education_subject_id' => $educationSubjectId)
            ));

            $educationGradeSubjectId = $educationGradeSubjectData['EducationGradesSubject']['id'];
            //pr($educationGradeSubjectData);
            App::import('Model', 'TimetableEntry');
            $TimetableEntry = new TimetableEntry();
            $timetableEntryData = $TimetableEntry->getSingleSelectedClassTimetableEntry(strtotime($startDate), $classId, $educationGradeSubjectId);
            //pr($timetableEntryData);
            $data = $this->copySelectedTimetableEntry($timetableEntryData);
            //pr($data);
        }

        return $data;
    }

    function copySelectedTimetableEntry($timetableEntryData) {
        $data = array();
        $data['start_time'] = $timetableEntryData['TimetableEntry']['date'] . " " . $timetableEntryData['TimetableEntry']['start_time'];
        $data['end_time'] = $timetableEntryData['TimetableEntry']['date'] . " " . $timetableEntryData['TimetableEntry']['end_time'];
        $data['class_id'] = $timetableEntryData['TimetableEntry']['class_id'];
        $data['room_id'] = $timetableEntryData['TimetableEntry']['room_id'];
        $data['education_grade_subject_id'] = $timetableEntryData['TimetableEntry']['education_grade_subject_id'];
        $data['staff_id'] = $timetableEntryData['TimetableEntry']['staff_id'];
        $data['timetable_entry_id'] = $timetableEntryData['TimetableEntry']['id'];
        $data['lesson_status_id'] = 0;
        //pr($data);die;
        $returnData = array();

        $this->validator()->remove('lesson_status_id');
        $this->create();

        if ($this->save($data)) {
            $data['id'] = $this->id;
            $returnData['ClassLesson'] = $data;
        }

        return $returnData;
    }

    public function getTeachersList($classId, $educationSubjectId) {
        $options['joins'] = array(
            array(
                'table' => 'education_grades_subjects',
                'alias' => 'EducationGradeSubject',
                'conditions' => array(
                    'ClassLesson.education_grade_subject_id = EducationGradeSubject.id',
                )
            ),
            array(
                'table' => 'staff',
                'alias' => 'Staff',
                'conditions' => array(
                    'ClassLesson.staff_id = Staff.id',
                )
            ),
            array(
                'table' => 'security_users',
                'alias' => 'SecurityUser',
                'conditions' => array(
                    'SecurityUser.id = Staff.security_User_id',
                )
            ),
        );

        $options['conditions'] = array(
            'ClassLesson.class_id' => $classId,
            'EducationGradeSubject.education_subject_id' => $educationSubjectId
        );
        $options['group'] = array('ClassLesson.staff_id');
        $options['recursive'] = -1;
        $options['fields'] = array('Staff.id', 'SecurityUser.openemisid', 'SecurityUser.first_name', 'SecurityUser.middle_name', 'SecurityUser.last_name');
        $data = $this->find('all', $options);

        return $data;
    }

    public function getTeachersListByClass($classId) {
        $options['joins'] = array(
            array(
                'table' => 'staff',
                'alias' => 'Staff',
                'conditions' => array(
                    'ClassLesson.staff_id = Staff.id',
                )
            ),
            array(
                'table' => 'security_users',
                'alias' => 'SecurityUser',
                'conditions' => array(
                    'SecurityUser.id = Staff.security_User_id',
                )
            ),
        );

        $options['conditions'] = array(
            'ClassLesson.class_id' => $classId
        );
        $options['group'] = array('ClassLesson.staff_id');
        $options['recursive'] = -1;
        $data = $this->find('all', $options);

        return $data;
    }

    public function index($param1=null,$param2=null,$param3=null) {
        $id = $this->controller->Session->read('SClass.id');

        if (empty($id)) {
            return $this->controller->redirect(array('action' => 'index'));
        }

        $weekStart = empty($param1) ? date('Y-m-d') : $param1;
        $weekEnd = date('Y-m-d 23:59:59', strtotime('+6 day', strtotime($weekStart)));

        $ClassGrade = ClassRegistry::init('ClassSubject');
        $this->fields['education_grade_subject_id']['type'] = 'select';
        $subjectByClass = $ClassGrade->getSubjectByClass($id);
        $educationGradeSubjectOptions = array();
        foreach ($subjectByClass as $key => $value) {
        	$educationGradeSubjectOptions[$value['ClassSubject']['education_grade_subject_id']] = $value['EducationSubject']['code'].' - '.$value['EducationSubject']['name'];
        }

        $Room = ClassRegistry::init('Room');
        $roomOptions = $Room->getRoomList();

        $ClassTeacher = ClassRegistry::init('ClassTeacher');
        $staffByClass = $ClassTeacher->getTeacherByClass($id);
        $staffOptions = array();
        foreach ($staffByClass as $key => $value) {
        	$staffOptions[$value['Staff']['id']] = $this->Message->getFullName($value);
        }

        $timetableEntryModel = ClassRegistry::init('TimetableEntry');
        $filterData = array();
        if (isset($param2) && isset($param3)) {
            $filterData['education_grade_subject_id'] = $param2;
            $filterData['staff_id'] = $param3;

            $this->request->data = array('subject_id' => $param2, 'staff_id' => $param3);
        }
        else{
            $this->request->data = array('subject_id' => 0, 'staff_id' => 0);
        }

        $timetableEntrydata = $timetableEntryModel->getClassTimetable($id, $weekStart, $weekEnd, $filterData);

        //pr($timetableEntrydata);
        $lessonData = $this->getLessonPeriod($id, $timetableEntrydata, $weekStart, $weekEnd, $filterData);

        //pr($lessonData);
        if (empty($lessonData)) {
            $this->controller->Message->alert('general.view.noRecords', array('type' => 'info'));
        }

        $endtime = explode(' ', $weekEnd);
        $this->setVar('classId', $id);
        $this->setVar('educationGradeSubjectOptions', $this->controller->Utility->getSetupOptionsData($educationGradeSubjectOptions, array('allField' => $this->Message->getLabel('ClassLesson.allSubjects'))));
        $this->setVar('roomOptions', $roomOptions);
        $this->setVar('staffOptions', $this->controller->Utility->getSetupOptionsData($staffOptions, array('allField' => $this->Message->getLabel('ClassLesson.allTeachers'))));
        $this->controller->Session->write('Class.lessonData', $lessonData);
        if(empty($lessonData)) $this->Message->alert('general.view.noRecords');
        $this->setVar('data', $lessonData);
        $this->setVar('startDate', $weekStart);
        $this->setVar('endDate', $endtime[0]);
        $this->setVar('model', $this->alias);
        $this->request->data['ClassLesson']['startDate'] = $weekStart;
        $this->request->data['ClassLesson']['endDate'] = $endtime[0];
    }

    public function add() {
        $model = $this->alias;
        $dateFormat = 'Y-m-d H:i:s';
        if ($this->request->is(array('post', 'put'))) {
            $startDate = $this->request->data[$model]['start_date'];
            $startTime = $this->request->data[$model]['start_time'];
            $endTime = $this->request->data[$model]['end_time'];
            $this->request->data[$model]['start_time'] = date($dateFormat, strtotime($startDate . ' ' . $startTime));
            $this->request->data[$model]['end_time'] = date($dateFormat, strtotime($startDate . ' ' . $endTime));
        }
        parent::add();
        $this->render = 'edit';
    }

    public function edit($id=0) {
        $model = $this->alias;
        $dateFormat = 'Y-m-d H:i:s';
        if ($this->request->is(array('post', 'put'))) {
            $startDate = $this->request->data[$model]['start_date'];
            $startTime = $this->request->data[$model]['start_time'];
            $endTime = $this->request->data[$model]['end_time'];
            $this->request->data[$model]['start_time'] = date($dateFormat, strtotime($startDate . ' ' . $startTime));
            $this->request->data[$model]['end_time'] = date($dateFormat, strtotime($startDate . ' ' . $endTime));
        }
        parent::edit($id);

        $temp_date = new DateTime($this->request->data[$model]['start_time']);
        $this->request->data[$model]['start_date'] = $temp_date->format('d-m-Y');
        $temp_date = new DateTime($this->request->data[$model]['start_time']);
        $this->request->data[$model]['start_time'] = $temp_date->format('h:i A');
        $temp_date = new DateTime($this->request->data[$model]['end_time']);
        $this->request->data[$model]['end_time'] = $temp_date->format('h:i A');
        $this->render = 'edit';
    }

    public function view($id=0) {
        $data = $this->find('first', array('conditions' => array($this->alias.'.id' => $id)));
        $date = new DateTime($data['ClassLesson']['start_time']);
        $data['ClassLesson']['start_date'] = $date->format('Y-m-d');
        $this->setVar('data', $data);
    }

    public function lesson_edit($controller, $params) { 
        $controller->Navigation->addCrumb($this->Message->getLabel('ClassLesson.editLesson'));
         
        $this->setupAddEditLesson($controller, $params);
        $this->processPostData($controller, $params, 'edit');

        $id = $controller->Session->read('Class.id');
        $controller->set('classId', $id);
       
        if (count($params['pass']) < 3) {
            return $controller->redirect(array('action' => 'index'));
        } else {
            if (!empty($params['pass'][0])) {
                $startDate = date('Y-m-d H:i:s', $params['pass'][0]);
            } else {
                //return $controller->redirect(array('action' => 'index'));
            }

            if (!empty($params['pass'][1])) {
                $educationGradeSubjectId = $params['pass'][1];
            } else {
                //return $controller->redirect(array('action' => 'index'));
            }

            if (!empty($params['pass'][2])) {
                $staffId = $params['pass'][2];
            } else {
               // return $controller->redirect(array('action' => 'index'));
            }

            $data = $this->find('first', array('recurisve' => -1, 'conditions' => array('start_time' => $startDate, 'staff_id' => $staffId, 'education_grade_subject_id' => $educationGradeSubjectId, 'class_id' => $id)));

            if (empty($data)) {
                $listOfClassLesson = $controller->Session->read('Class.lessonData');

                $tempClassData = $listOfClassLesson[$params['pass'][0]];

                $time = explode('-', $tempClassData['time']);
                $startTime = (substr(trim($time[0]), -2) == 'pm') ? 12 + intval(substr(trim($time[0]), 0, 2)) : substr(trim($time[0]), 0, 2);
                $startTime .= ':' . substr(trim($time[0]), 3, 2);

                $endTime = (substr(trim($time[1]), -2) == 'pm') ? 12 + intval(substr(trim($time[1]), 0, 2)) : substr(trim($time[1]), 0, 2);
                $endTime .= ':' . substr(trim($time[1]), 3, 2);

                $currentdata['ClassLesson']['start_date'] = $tempClassData['date'];
                $currentdata['ClassLesson']['startTime'] = $startTime;
                $currentdata['ClassLesson']['endTime'] = $endTime;
                $currentdata['ClassLesson']['education_grade_subject_id'] = $tempClassData['education_grade_subject_id'];
                $currentdata['ClassLesson']['room_id'] = $tempClassData['room_id'];
                $currentdata['ClassLesson']['staff_id'] = $tempClassData['staff_id'];
                $currentdata['ClassLesson']['timetable_entry_id'] = $tempClassData['timetable_entry_id'];
                $currentdata['ClassLesson']['class_id'] = $id;

                $controller->request->data = $currentdata;
            } else {
                $controller->request->data = $data;
                $controller->request->data['ClassLesson']['start_date'] = date('Y-m-d', strtotime($data['ClassLesson']['start_time']));
                $controller->request->data['ClassLesson']['start_time'] = date('h:i A', strtotime($data['ClassLesson']['start_time']));
                $controller->request->data['ClassLesson']['end_time'] = date('h:i A', strtotime($data['ClassLesson']['end_time']));
                $controller->set('id', $controller->request->data['ClassLesson']['id']);
            }
        }
    }

    function setupAddEditLesson() {
        $id = $this->Session->read('SClass.id');
        if (empty($id)) {
            return $this->redirect(array('action' => 'index'));
        }
        $this->setVar('classId', $id);
        $ClassSubject = ClassRegistry::init('ClassSubject');
        $classSubjectData = $ClassSubject->getSubjectByClass($id);
        $educationGradeSubjectOptions = array();
        foreach ($classSubjectData as $classLesson) {
            $educationGradeSubjectOptions[$classLesson['ClassSubject']['education_grade_subject_id']] = $classLesson['EducationSubject']['code'] . ' - ' . $classLesson['EducationSubject']['name'];
        }

        $staffOptions = array();
        $roomOptions = array();
        $educationGradeSubjectOptions = $this->controller->Utility->getSetupOptionsData($educationGradeSubjectOptions);

        $Room = ClassRegistry::init('Room');
        $roomOptions = $Room->getOptions('name', 'order', 'asc', array('visible' => 1));

        $Staff = ClassRegistry::init('Staff');
        $staffOptions = $Staff->getStaffList();

        $LessonStatus = ClassRegistry::init('LessonStatus');
        $statusOptions = $LessonStatus->getOptions('name', 'order', 'asc', array('visible' => 1));
        
        
        $configModel = ClassRegistry::init('ConfigItem');
        $startTime = $configModel->getValue('start_time_of_day');
        $lessonDuration = $configModel->getValue('lesson_duration');
        $this->request->data[$this->name]['startTime'] = $startTime;
        $this->request->data[$this->name]['endTime'] = date('H:i:s',strtotime($startTime)+($lessonDuration*60));

        $staffOptions = $this->controller->Utility->getSetupOptionsData($staffOptions);
        $roomOptions = $this->controller->Utility->getSetupOptionsData($roomOptions);
        $statusOptions = $this->controller->Utility->getSetupOptionsData($statusOptions);

        // handle the date time


        $this->setVar('educationGradeSubjectOptions', $educationGradeSubjectOptions);
        $this->setVar('staffOptions', $staffOptions);
        $this->setVar('roomOptions', $roomOptions);
        $this->setVar('statusOptions', $statusOptions);
    }

    function processPostData($type) {
        $model = $this->alias;
        $dateFormat = 'Y-m-d H:i:s';
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data[$model]['week_number'] = date('W', strtotime($this->request->data['ClassLesson']['start_date']));

            $startDate = $this->request->data[$model]['start_date'];
            $startTime = $this->request->data[$model]['start_time'];
            $endTime = $this->request->data[$model]['end_time'];
            $this->request->data[$model]['start_time'] = date($dateFormat, strtotime($startDate . ' ' . $startTime));
            $this->request->data[$model]['end_time'] = date($dateFormat, strtotime($startDate . ' ' . $endTime));

            if (empty($this->request->data[$model]['id'])) {
                unset($this->request->data[$model]['id']);
            }
            
            if ($debug_msg = $this->saveAll($this->request->data[$model])) {
                if ($type == 'add') {
                    $this->Message->alert('general.add.success');
                } else {
                    $this->Message->alert('general.edit.success');
                }
                return $this->redirect(array('action' => $model.'/index'));
            } else {
                if ($type == 'add') {
                    $this->Message->alert('general.add.failed');
                } else {
                    $this->Message->alert('general.edit.failed');
                }
            }
        }
    }
}
