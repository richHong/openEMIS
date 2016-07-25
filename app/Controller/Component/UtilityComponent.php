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

App::uses('CakeEmail', 'Network/Email');

class UtilityComponent extends Component {
    public $components = array('Message');

	public function sendEmail($toEmail, $subject, $message){
		$Email = new CakeEmail('default');
		$Email->from(array('jsim@kordit.com' => 'Administrator'));
		$Email->to($toEmail);
		$Email->subject($subject);

		$Email->send($message);
		var_dump(debug($Email->smtpError));

	}
	
	public function datepickerStartEndDate($selectedDate, $dateDiff = 7){
		/*$currentWeek = date("W");
		$currentYear = date("Y");
		
		$week_start = new DateTime();
		$week_start->setISODate($currentYear,$currentWeek);
		$weekStart = $week_start->format('Y-m-d');*/
		
		$startDate = empty($selectedDate)? date('Y-m-d'): $selectedDate;
		$endDate = date('Y-m-d', strtotime($startDate." +".($dateDiff-1)." day"));
		
		$data = compact('dateDiff', 'startDate', 'endDate');
		
		return $data;
	}

	public function getFileExtensionList() {
		$ext = array(
			'jpg' => __('Image'),
			'jpeg' => __('Image'),
			'png' => __('Image'),
			'gif' => __('Image'),
			'docx' => __('Document'),
			'doc' => __('Document'),
			'xls' => __('Excel'),
			'xlsx' => __('Excel'),
			'ppt' => __('Powerpoint'),
			'pptx' => __('Powerpoint')
		);
		return $ext;
	}
	
	public function getSetupOptionsData($data,$options=array()){
		if(empty($data)){
			$returnData[] = '-- ' . $this->Message->getLabel('general.noOption') . ' --';
		}
		else{
			if (array_key_exists('allField', $options)) {
				$tmp_array = array('0' => $options['allField']);
				foreach ($data as $key => $val) {
					$tmp_array[$key] = $val;
				}
				$data = $tmp_array;
			}
			$returnData = $data;
		}
		
		return $returnData;
	}
}
