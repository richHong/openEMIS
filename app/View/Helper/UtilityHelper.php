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
App::uses('String', 'Utility');

class UtilityHelper extends AppHelper {
    public $helpers = array('Label');

	public function ellipsis($string, $length = '30') {
		return String::truncate($string, $length, array('ellipsis' => '...', 'exact' => false));
	}
	
    public function highlight($needle, $haystack){
		$ind = stripos($haystack, $needle);
		$len = strlen($needle);
		if($ind !== false){
			return substr($haystack, 0, $ind) . '<span style="background-color: yellow">' . substr($haystack, $ind, $len) . '</span>' .
				$this->highlight($needle, substr($haystack, $ind + $len));
		} else return $haystack;
	}

	public function getStaffTypeOptions() {
		return array('0' => $this->Label->get('staff.nonTeaching'), '1' => $this->Label->get('general.teaching'));
	}

	public function getEventTypeOptions() {
		return array('1' => $this->Label->get('event.schoolEvent'), '2' => $this->Label->get('event.classEvent'));
	}

	public function getEventType($value) {
		$type = array('1' => $this->Label->get('event.schoolEvent'), '2' => $this->Label->get('event.classEvent'));
		return $type[strtoupper($value)];
	}


	public function getIsoWeeksInYear($year) {
	    $date = new DateTime;
	    $date->setISODate($year, 53);
	    return ($date->format("W") === "53" ? 53 : 52);
	}
	
	public function getAttendanceTypes() {
		$options = array(
			$this->Label->get('general.attendanceByDay'),
			$this->Label->get('general.attendanceByLesson')
		);
		return $options;
	}
}