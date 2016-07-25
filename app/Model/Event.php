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

class Event extends AppModel {
	public $belongsTo = array(
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
		'DatePicker' => array('start_date', 'end_date'),
		'TimePicker' => array('start_time', 'end_time') // for fixing time format from timepicker
	);

	public function __construct() {
		parent::__construct();

		$this->validate = array(
			'name' => array(
				'required' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('name')
				)
			),
			'start_date' => array(
				'required' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('startDate')
				)
			),
			'start_time' => array(
				'required' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('startTime')
				)
			),
			'end_date' => array(
				'required' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('endDate')
				)
			),
			'end_time' => array(
				'required' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'message' => $this->getErrorMessage('endTime')
				),
				'ruleCompare' => array(
					'rule' => 'compareDates',
					'message' => $this->getErrorMessage('endTimeEarlier')
				)
			)
		);
	}
	
	public function getFields($options=array()) {
		parent::getFields($options);
		$this->fields['type']['type'] = 'hidden';
		return $this->fields;
	}
	
	public function compareDates() {
		$startDate = $this->data[$this->alias]['start_date'];
		$startTime = $this->data[$this->alias]['start_time'];
		$startTimestamp = strtotime($startDate . ' ' . $startTime);
		$endDate = $this->data[$this->alias]['end_date'];
		$endTime = $this->data[$this->alias]['end_time'];
		$endTimestamp = strtotime($endDate . ' ' . $endTime);
		
		return $endTimestamp > $startTimestamp;
	}

	public function getClassEventByClassId($classId) {
		$data = $this->find('all', array(
			'fields' => array('Event.id', 'Event.name', 'Event.start_date', 'Event.end_date'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'class_events',
					'alias' => 'ClassEvent',
					'conditions' => array('ClassEvent.event_id = Event.id')
				)
			),
			'conditions' => array('ClassEvent.class_id' => $classId, 'Event.type' => 2),
			'order' => array('Event.start_date')
		));
		return $data;
	}
	
	public function getEventDaysByMonth($month) {
		$data = $this->find('all', array(
			'recursive' => -1,
			'fields' => array('DISTINCT Event.start_date as START_DATE, Event.end_date as END_DATE'),
			'conditions' => array("'" . $month . "' BETWEEN DATE_FORMAT(start_date, '%Y-%m') and  DATE_FORMAT(end_date, '%Y-%m')"),
			'order' => array('Event.start_date')
		));

		//pr($data);

		$days = array();
		if(!empty($data)) {
			foreach($data as $day) {
				//$days[] = $day['Event']['START_DATE'];
				$startDay = $day['Event']['START_DATE'];
				$endDay = $day['Event']['END_DATE'];
				if(date('m',strtotime($day['Event']['END_DATE']))!=date('m', strtotime($month))){
					$endDay = date('Y-m-t', strtotime($startDay));
				}
				if(date('m', strtotime($month)) != date('m', strtotime($day['Event']['START_DATE']))){
					$startDay = date('Y-m-1', strtotime($month));
				}
				while($startDay<=$endDay){
					if(!in_array(date('j', strtotime($startDay)), $days)){
						$days[] = date('j', strtotime($startDay));
					}
					$startDay = date('Y-m-d', strtotime('+1 days', strtotime($startDay)));
				}
			}
		}
		return $days;
	}
}
