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

class ScheduleComponent extends Component {
 	/*---------------------------------------------------------------------------
	* $data and $param : Both are retriving from the controller
	*----------------------------------------------------------------------------*/
	public $data = array();
	public $param = array();
	
	/*---------------------------------------------------------------------------
	* $fileModel : It is the name of the model that you want. 
	* @var string
	*----------------------------------------------------------------------------*/
	//public $fileModel = 'Timetable';
	
	/*---------------------------------------------------------------------------
	* $editable : It makes the entry able to receive the click event. By default 
	* it sets to true.
	* @var boolean
	*----------------------------------------------------------------------------*/
	public $editable = true;
	
	/*---------------------------------------------------------------------------
	* $addable : It makes the table able to receive the click event. By default 
	* it sets to true.
	* @var boolean
	*----------------------------------------------------------------------------*/
	public $addable = true;
	
	/*---------------------------------------------------------------------------
	* $editFuture : By setting it to true, the timetable will check whether that particuler 
	* entry is a future date. If yes, that entry will be disable.By default is NULL
	* @var boolean
	*----------------------------------------------------------------------------*/
	public $editFuture = NULL;
	
	/*---------------------------------------------------------------------------
	* $showDate : hide or show the dates on the table header
	* @var boolean
	*----------------------------------------------------------------------------*/
	public $showDate = true;
	
	/*---------------------------------------------------------------------------
	* $addEditURL : It's links back to the controller function
	* @var string
	*----------------------------------------------------------------------------*/
	// public $addEditURL = 'Classes/timetable_ajax_add_event';
	public $addEditURL = 'Staff/Timetable/ajax_add_event';
	
	
	/*---------------------------------------------------------------------------
	* $daysOfWeek : It is a list of days in a week with its id. 
	* @var string
	*----------------------------------------------------------------------------*/
	private $daysOfWeek = array(
		1 => 'Monday',
		2 => 'Tuesday',
		3 => 'Wednesday',
		4 => 'Thursday',
		5 => 'Friday',
		6 => 'Saturday',
		7 => 'Sunday'
	);
	
	public $timetableEntryData;
	
	public function initialize(Controller $controller){
		$this->data = $controller->data;
		$this->params = $controller->params; 
	}
	
	public function getTimetableSetupData($timetableId){
		if(empty($this->timetableEntryData)){
			$timetableEntryModel = ClassRegistry::init('TimetableEntry');
			$data = $timetableEntryModel->getAllSelectedClassTimetableEntry($timetableId);
		}
		else{
			$data = $this->timetableEntryData;
		}
	 	
		$setupData = $this->_getBasicSetup($timetableId);
		$setupData['data'] = $data;
		
		return $setupData;
	}
	
	public function getDisplayEntry($data){
		$div = 	"<div id='entry_".$data['id']."' class='entry-holder' onclick='timetable.entry_click(this, ".json_encode($data['editable']).")'>";
		
		if(!empty($data['timedate'])){
			$div .= "<div class='entry-new-date'>".$data['timedate']."</div>";
		}
		if(!empty($data['subject_title'])){
			$div .= "<div class='entry-title'>".$data['subject_title']."</div>";
		}
		if(!empty($data['subject_code'])){
			$div .= "<div class='entry-code'>(".$data['subject_code'].")</div>";
		}
		if(!empty($data['teacher_inCharge'])){
			$div .= "<div class='entry-pic'>".$data['teacher_inCharge']."</div>";
		}
		if(!empty($data['teacher_openemisid'])){
			$div .= "<div class='entry-code'>(".$data['teacher_openemisid'].")</div>";
		}
		
		$div .= "</div>";
		
		return $div;
	}
	
	/*function &getModel() {
		$model = null;
		$name = $this->fileModel;
		
		if($name){
			$model = ClassRegistry::init($name);
			
			
			if (empty($model) && $this->fileModel) {
				//$this->_error('FileUpload::getModel() - Model is not set or could not be found');
				return null;
			}
		}
		return $model;
    } 	*/
	
	/*-------------------------------------
	* @var timestamp = [Format hh:mm:ss]
	* return (int)timeinminutes
	*-------------------------------------*/
	function _convertTimestampToMinutes($timestamp){
		$time = explode(":", $timestamp);
		list($hour, $minutes) = $time;
		
		$totalMins = ($hour*60)+$minutes;
		
		return $totalMins;
	}
 
 	function _calculateTotalRowsGenerated($timetableTimeDiff, $lessonDuration, $lessonBreakInterval){
		$timetableTotalRow = 0;
		$timeCounter = 0;
		
		while($timeCounter < $timetableTimeDiff){
			$timeCounter += $lessonDuration + $lessonBreakInterval;
			$timetableTotalRow ++;
		}
		
		return $timetableTotalRow;
	}
	
	function _getBasicSetup($id){
		$configModel = ClassRegistry::init('ConfigItem');
		
		$lessonDuration = $configModel->getValue('lesson_duration');
		$lessonBreakInterval = $configModel->getValue('break_interval');
		$timetableStartTime = $this->_convertTimestampToMinutes($configModel->getValue('start_time_of_day'));
		$timetableEndTime = $this->_convertTimestampToMinutes($configModel->getValue('end_time_of_day'));

		$timetableTimeDiff = $timetableEndTime - $timetableStartTime;
		
		$timetableTotalRow = $this->_calculateTotalRowsGenerated($timetableTimeDiff, $lessonDuration, $lessonBreakInterval);
		
		$setupData = array(
			'lesson_duration' => $lessonDuration,
			'break_interval' => $lessonBreakInterval,
			'start_time_of_day' => $timetableStartTime,
			'end_time_of_day' => $timetableEndTime,
			'days_of_week' => $this->daysOfWeek,
			'num_of_row' => $timetableTotalRow,
			'editable' => $this->editable,
			'addable' => $this->addable,
			'timetable_id' => $id,
			'showDate' => $this->showDate,
			'addEditURL' => $this->addEditURL,
			'editFuture' => $this->editFuture
		);
		
		return $setupData;
	}
 
 
 //-------------------------------|| Old codes ||----------------------------------------
 
     function findMonday($date="",$format="Y-m-d"){
		if($date=="") 
			$date=date("Y-m-d");
			
		$week = date("W",strtotime($date));
		$year = date("Y",strtotime($date));
		
		//if ($delta <0) 
			//$delta = 6;
		
		return date($format, strtotime($year."W".$week));
	//	return date($format, mktime(0,0,0,date('m'), date('d'), date('Y') ));
	}
	
	function getSubjectTitle($id){
		$subjectOptions = array('1' => 'English', '2' => 'Chinese', '3' => 'Malay', '4' => 'Maths', '5' => 'Physics');
		
		return $subjectOptions[$id];
	}
	
	function getTeacherIncharge($id){
		$teacherOptions = array('1' => 'Mr Teo', '2' => 'Mdm Zhang', '3' => 'Ms Shariza', '4' => 'Ms Serene', '5' => 'Mr Khoo');
		
		return $teacherOptions[$id];
	}
}

?>