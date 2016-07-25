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

App::uses('AppHelper', 'View/Helper');

class TimetableHelper extends AppHelper {
    public $helpers = array('Label');

    public function minutesToTime($minutes, $is24Hours = false){
		$hours = floor($minutes / 60);
		$mins = $minutes - ($hours*60);
	
		if($hours >= 12){
			$meridiem = "pm";
		}
		else{
			$meridiem = "am";
		}
		
		if(!$is24Hours)
			$hours = ($hours > 12)? $hours-12 : $hours;
			
		$mins = ($mins < 10)? "0".$mins : $mins;
		
		return ($is24Hours)? ($hours.":".$mins) : ($hours.":".$mins.$meridiem);
		
	}
	
	public function timeToMinutes($timeStr){
		$totalMinutes = 0;
		$times = explode(':',$timeStr);
		
		if(isset($times[0])){
			$totalMinutes += $times[0]*60;
		}
		if(isset($times[1])){
			$totalMinutes += $times[1];
		}
		
		return $totalMinutes;
	}
	
	public function getSubjectTitle($id){
		$subjectOptions = array(
            '1' => $this->Label->get('general.english'),
            '2' => $this->Label->get('general.chinese'),
            '3' => $this->Label->get('general.malay'),
            '4' => $this->Label->get('general.maths'),
            '5' => $this->Label->get('general.physics')
        );
		
		return $subjectOptions[$id];
	}
	
	public function getTeacherIncharge($id){
		$teacherOptions = array('1' => 'Mr Teo', '2' => 'Mdm Zhang', '3' => 'Ms Shariza', '4' => 'Ms Serene', '5' => 'Mr Khoo');
		
		return $teacherOptions[$id];
	}
}

?>