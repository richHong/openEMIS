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

// PHPSM-30: Reuse the label helper in order to support labelling for models
App::uses('LabelHelper', 'View/Helper');
// END PHPSM-30

class MessageComponent extends Component {
    /* -----------------------------------------------------------------------------
     * | PHPSM-30: Reuse the label helper in order to support labelling for models |
     * -----------------------------------------------------------------------------
     */
    public $labelHelper;

	public $components = array('Session');
	public $alertTypes = array(
		'ok' => 'alert-success',
		'error' => 'alert-danger',
		'info' => 'alert-info',
		'warn' => 'alert-warning'
	);
	
	public $messages = array(
		'general' => array(
			'noData' => array('type' => 'info', 'msg' => 'No Data'),
			'notEditable' => array('type' => 'warn', 'msg' => 'This record is not editable.'),
			'error' => array('type' => 'error', 'msg' => 'An unexpected error has been encountered. Please contact the administrator for assistance.'),
			'add' => array(
				'success' => array('type' => 'ok', 'msg' => 'Record has been added successfully.'),
				'failed' => array('type' => 'error', 'msg' => 'Record is not added due to errors encountered.'),
			),
			'edit' => array(
				'notifyAdd' => array('type' => 'ok', 'msg' => 'Please add new record into it.'),
				'success' => array('type' => 'ok', 'msg' => 'Record has been updated successfully.'),
				'failed' => array('type' => 'error', 'msg' => 'Record is not updated due to errors encountered.')
			),
			'view' => array(
				'notExists' => array('type' => 'warn', 'msg' => 'The record does not exist.'),
				'noRecords' => array('type' => 'error', 'msg' => 'There are no records.')
			),
			'delete' => array(
				'success' => array('type' => 'ok', 'msg' => 'Record has been deleted successfully.'),
				'failed' => array('type' => 'error', 'msg' => 'Record is not deleted due to errors encountered.'),
			),
			'search' => array(
				'noResult' => array('type' => 'error', 'msg' => 'Your search returns no result.')
			),
            'translation' => array(
                'success' => array('type' => 'ok', 'msg' => 'The translation file has been compiled successfully.'),
            )
		),
		'security' => array(
			'login' => array(
				'timeout' => array('type' => 'error', 'msg' => 'Your session has timed out. Please login again.'),
				'fail' => array('type' => 'error', 'msg' => 'You have entered an invalid username or password.'),
				'inactive' => array('type' => 'error', 'msg' => 'You are not an authorized user.')
			),
			'noAuthorization' => array('type' => 'error', 'msg' => 'You are not an authorized user.')
		),
		'search' => array(
			'noResult' => array('type' => 'error', 'msg' => 'No result returned from the search.')
		),

        'upload' => array(
            'success' => array(
                'single' => 'The file has been uploaded.',
                'multi' => 'The files have been uploaded.',
            ),

            'error' => array(
                'general' => 'An unexpected error has been encountered. Please contact the administrator for assistance.',
                'uploadSizeError' => 'Please ensure that the file is smaller than the file size limit.',
                'UPLOAD_ERR_NO_FILE' => 'No file was uploaded.',
                'UPLOAD_ERR_FORM_SIZE' => 'Please ensure that the file is smaller than the file size limit.',
                'UPLOAD_ERR_INI_SIZE' => 'Please ensure that the file is smaller than the file size limit.',
                'invalidFileFormat' => 'Invalid file format.',
                'saving' => 'File is not uploaded due to errors encountered.'
            )
        ),

        'SClass' => array(
			'noSchoolYear' => array('type' => 'warn', 'msg' => 'There are no school years.')
		),
		
		'EducationGrade' => array(
			'noProgrammes' => array('type' => 'warn', 'msg' => 'There are no education programmes.')
		),
		
		'EducationGradesSubject' => array(
			'noGrades' => array('type' => 'warn', 'msg' => 'There are no education grades.')
		),
		
		'ClassStudent' => array(
			'isExists' => array('type' => 'error', 'msg' => 'The student is already exists in this class.'),
			'noClass' => array('type' => 'warn', 'msg' => 'The student has not been assigned any classes.')
		),

        // Validation messages, grouped by model name
        'validation' => array(
            'AssessmentItemType' => array(
                'name' => 'Please enter a valid name',
                'year' => 'Please select a year',
                'grade' => 'Please select a grade',
            ),

            'AssessmentResultType' => array(
                'option' => 'Please enter a valid option',
                'min' => 'Please enter a valid minimum value',
                'max' => 'Please enter a valid maximum value'
            ),

            'Attendance' => array(
                'subject' => 'Please select a valid subject name',
                'class' => 'Please select a valid class name',
                'name' => 'Please enter a valid class name'
            ),

            'BehaviourCategory' => array(
                'name' => 'Please enter a valid option'
            ),

            'ClassGrade' => array(
                'grade' => 'Please select a valid grade'
            ),
			
			'ClassAssignment' => array(
				'name' => 'Please enter a valid name'
			),

            'ClassLesson' => array(
                'startTime' => 'Start Time cannot be later than End Time',
                'subject' => 'Please select a valid subject',
                'location' => 'Please select a valid location',
                'teacher' => 'Please select a valid teacher',
                'status' => 'Please select a valid status'
            ),

            'Contact' => array(
                'contact' => 'Please enter a valid Contact No'
            ),

            'ContactType' => array(
                'option' => 'Please enter a valid option'
            ),

            'EducationFee' => array(
                'description' => 'Please enter a valid description',
                'currency' => 'Please enter a valid positive amount',
                'fee_type_id' => 'Please select a type'
            ),

            'EducationGrade' => array(
                'code' => 'Please enter a valid code',
                'name' => 'Please enter a valid name',
                'programme' => 'Please select a programme'
            ),

            'EducationProgramme' => array(
                'code' => 'Please enter a valid code',
                'name' => 'Please enter a valid name',
                'duration' => 'Please enter a valid duration'
            ),

            'EducationSubject' => array(
                'code' => 'Please enter a valid code',
                'name' => 'Please enter a valid name'
            ),

            'Email' => array(
                'email' => 'Please enter a valid email'
            ),

            'Event' => array(
                'name' => 'Please enter a valid name',
                'startDate' => 'Please enter a valid start date',
                'startTime' => 'Please enter a valid start time',
                'endDate' => 'Please enter a valid end date',
                'endTime' => 'Please enter a valid end time',
                'endTimeEarlier' => 'The end date/time cannot be earlier than the start date',
            ),

            'GuardianContact' => array(
                'name' => 'Please enter a valid name',
                'valueEmail' => 'Please enter a valid email',
                'valuePhone' => 'Please enter a valid phone number',
                'value' => 'Please enter a value',
                'contact_type_id' => 'Please select a valid type',
            ),

            'InstitutionSite' => array(
                'address' => 'Please enter a valid address',
                'code' => 'Please enter a valid code',
                'dateOpened' => 'Please enter a valid date opened',
                'email' => 'Please enter a valid email',
                'name' => 'Please enter a valid name',
                'postalCode' => 'Please enter a valid postal code',
                'dateClosedGreater' => 'Date closed must be greater than date opened',
                'areaid' => 'Please enter 3 character Area Id'
            ),

            'LessonStatus' => array(
                'option' => 'Please enter a valid option'
            ),

            'RelationshipCategory' => array(
                'option' => 'Please enter a valid option'
            ),

            'Room' => array(
                'name' => 'Please enter a valid name'
            ),


            'SchoolYear' => array(
                'year' => 'Please enter a valid year',
                'startDate' => 'Please enter a valid start date',
                'endDateGreater' => 'End date must be greater than start date',
                'schoolDay' => 'Please enter a valid school day'
            ),

            'SClass' => array(
                'className' => 'Please enter a valid class name',
                'schoolYear' => 'Please select a valid school year',
                'totalSeats' => 'Please enter a numeric value for total seats',
                'availableSeats' => 'Please enter a numeric value for available seats'
            ),

            'SecurityUser' => array(
                'username' => 'Please enter your username',
                'usernameInUse' => 'This username is already in use',
                'currentPassword' => 'Please enter your current password',
                'currentPasswordIncorrect' => 'Your current password is incorrect',
                'passwordLength' => 'Password must be at least 6 characters',
                'newPassword' => 'Please enter your new password',
                'confirmNewPassword' => 'Please confirm your new password',
                'passwordsMismatch' => 'Both passwords do not match',
                'firstName' => 'Please enter a valid first name',
                'lastName' => 'Please enter a valid last name',
                'email' => 'Please enter a valid email',
                'country' => 'Please select a valid location',
                'gender' => 'Please select a valid gender',
                'openemisid' => 'Please enter a valid OpenEMIS Id.',
                'openemisidUnique' => 'Please enter a unique OpenEMIS Id.',
            ),

            'Staff' => array(
                'startDate' => 'Please enter a start date',
                'category' => 'Please choose a category',
                'status' => 'Please choose a status',
                'type' => 'Please choose a type',
                'openemisid' => 'Please enter a valid OpenEMIS Id.',
            ),

            'StaffAttachment' => array(
                'name' => 'Please enter a valid name',
                'filename' => 'Please select a valid file',
            ),

            'StaffAttendanceType' => array(
                'option' => 'Please enter a valid option',
                'shortForm' => 'Please enter a valid short form'
            ),

            'StaffBehaviour' => array(
                'title' => 'Please enter a valid title',
                'date' => 'Please enter a valid date',
                'description' => 'Please enter a valid description',
                'action' => 'Please enter a valid action',
                'behaviour_category_id' => 'Please select a valid category',
            ),

            'StaffCategory' => array(
                'option' => 'Please enter a valid option'
            ),

            'StaffContact' => array(
                'name' => 'Please enter a valid name',
                'valueEmail' => 'Please enter a valid email',
                'valuePhone' => 'Please enter a valid phone number',
                'value' => 'Please enter a value',
                'contact_type_id' => 'Please select a valid type',
            ),


            'StaffEmployment' => array(
                'date' => 'Please enter an employment date',
                'staff_employment_type_id' => 'Please enter an type'
            ),

            'StaffEmploymentType' => array(
                'option' => 'Please enter a valid option'
            ),

            'StaffStatus' => array(
                'option' => 'Please enter a valid option'
            ),

            'Student' => array(
                'openemisid' => 'Please enter a valid OpenEMIS Id.',
                'student_status_id' => 'Please enter a valid student status',
                'startDate' => 'Please enter a start date'
            ),

            'StudentAttachment' => array(
                'name' => 'Please enter a valid name',
                'filename' => 'Please select a valid file',
            ),

            'StudentAttendanceType' => array(
                'option' => 'Please enter a valid option',
                'shortForm' => 'Please enter a valid short form'
            ),

            'StudentBehaviour' => array(
                'title' => 'Please enter a valid title',
                'date' => 'Please enter a valid date',
                'description' => 'Please enter a valid description',
                'action' => 'Please enter a valid action',
                'behaviour_category_id' => 'Please select a valid category',
            ),

            'StudentCategory' => array(
                'option' => 'Please enter a valid option'
            ),

            'StudentContact' => array(
                'name' => 'Please enter a valid name',
                'valueEmail' => 'Please enter a valid email',
                'valuePhone' => 'Please enter a valid phone number',
                'value' => 'Please enter a value',
                'contact_type_id' => 'Please select a valid type',
            ),

			'StudentIdentity' => array(
				'country' => 'Please select a valid location',
				'date' => 'Please enter a valid date',
				'value' => 'Please enter a valid value',
				'issueDateLater' => 'Issue date cannot be later than expiry date',
				'identity_type_id' => 'Please select a valid type',
			),

			'StaffIdentity' => array(
				'country' => 'Please select a valid location',
				'date' => 'Please enter a valid date',
				'value' => 'Please enter a valid value',
				'issueDateLater' => 'Issue date cannot be later than expiry date',
				'identity_type_id' => 'Please select a valid type',
			),

			'GuardianIdentity' => array(
				'country' => 'Please select a valid location',
				'date' => 'Please enter a valid date',
				'value' => 'Please enter a valid value',
				'issueDateLater' => 'Issue date cannot be later than expiry date',
				'identity_type_id' => 'Please select a valid type',
			),

            'StudentFee' => array(
                'comment' => 'Please enter a valid comment',
                'currency' => 'Please enter a valid positive amount'
            ),

            'StudentStatus' => array(
                'option' => 'Please enter a valid option'
            ),

            'Timetable' => array(
                'name' => 'Please enter a valid timetable name',
                'startDateLater' => 'Start date cannot be later than end date', 
                'value' => 'Please enter a valid value',
            ),

            'TimetableEntry' => array(
                'subject' => 'Please select a valid subject',
                'location' => 'Please select a valid location',
                'teacher' => 'Please select a valid teacher'
            ),

			'StudentCustomField' => array(
				'name' => 'Please enter a valid name',
				'required' => 'Please enter a value.'
			),	

			'StudentCustomValue' => array(
				'under250' => 'Please enter a value under 250 characters.',
				'naturalNumber' => 'Please enter a positive natural number.',
				'required' => 'Please enter a value.',
				'unique' => 'Please enter a unique value'
			),	

			'StudentCustomFieldOption' => array(
				'btw1And250' => 'Please enter a value in between 1 and 250 characters.'
			),

			'StaffCustomField' => array(
				'name' => 'Please enter a valid name',
				'required' => 'Please enter a value.'
			),	

			'StaffCustomValue' => array(
				'under250' => 'Please enter a value under 250 characters.',
				'naturalNumber' => 'Please enter a positive natural number.',
				'required' => 'Please enter a value.',
				'unique' => 'Please enter a unique value'
			),	

			'StaffCustomFieldOption' => array(
				'btw1And250' => 'Please enter a value in between 1 and 250 characters.'
			),

			'GuardianCustomField' => array(
				'name' => 'Please enter a valid name',
				'required' => 'Please enter a value.'
			),	

			'GuardianCustomValue' => array(
				'under250' => 'Please enter a value under 250 characters.',
				'naturalNumber' => 'Please enter a positive natural number.',
				'required' => 'Please enter a value.',
				'unique' => 'Please enter a unique value'
			),	

			'GuardianCustomFieldOption' => array(
				'btw1And250' => 'Please enter a value in between 1 and 250 characters.'
			),

            'ClassResult' => array(
            	'decimal1AndPositive' => ' Please enter a positive numeral with not more than 1 decimal point.'
            )
        )
	);

    /* ----------------------------------------------------------------------------------
     * | PHPSM-30: Override superclass constructor in order to instantiate label helper |
     * ----------------------------------------------------------------------------------
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
        $this->labelHelper = new LabelHelper();
    }
	
	public function get($code) {
		$index = explode('.', $code);
		$message = $this->messages;
		foreach($index as $i) {
			if(isset($message[$i])) {
				$message = $message[$i];
			} else {
				$message = '[Message Not Found]'; // PHMSM-30: Intentionally not translated, since it's unnecessary
				break;
			}
		}
		return !is_array($message) ? __($message) : $message;
	}

    public function alert($code, $settings = array()) {
        $types = $this->alertTypes;

        $_settings = array(
            'type' => key($types),
            'types' => $types,
            'dismissOnClick' => true,
            'params' => array()
        );

        $_settings = array_merge($_settings, $settings);
        $message = $this->get($code);

        if (!array_key_exists($_settings['type'], $types)) {
            $_settings['type'] = key($types);
        } else {
            $_settings['type'] = $message['type'];
        }

        if (!empty($_settings['params'])) {
            $message = vsprintf($message, $_settings['params']);
        }

        $_settings['message'] = __($message['msg']);
        $this->Session->write('_alert', $_settings);
    }
	
	public function alertBox($message, $settings=array()) {
		$types = $this->alertTypes;
		$_settings = array(
			'type' => key($types),
			'types' => $types,
			'dismissOnClick' => true,
			'params' => array()
		);
		$_settings = array_merge($_settings, $settings);
		if(!array_key_exists($_settings['type'], $types)) {
			$_settings['type'] = key($types);
		}
		
		$_settings['message'] = $message;
		$this->Session->write('_alert', $_settings);
	}
	
	public function clearAlert(){
		$this->Session->delete('_alert');	
	}

    /* ---------------------------------------------------------
     * | PHPSM-30: Redirect label requests to the label helper |
     * ---------------------------------------------------------
     */
    public function getLabel($code) {
        return $this->labelHelper->get($code);
    }

    public function getFullName($data, $options=array()) {
        $first_name = "";
        $middle_name = "";
        $last_name = "";
        $full_name = "";

        $model = 'SecurityUser';
        if (array_key_exists('findInModel', $options)) {
        	$model = $options['findInModel'];
        }

       
		if ($data) {
			if (array_key_exists($model, $data)) {
				if (array_key_exists('first_name', $data[$model])) {
					$first_name = trim($data[$model]['first_name']);
				}
				if (array_key_exists('middle_name', $data[$model])) {
					$middle_name = trim($data[$model]['middle_name']);
				}
				if (array_key_exists('last_name', $data[$model])) {
					$last_name = trim($data[$model]['last_name']);
				}
			}
		}

        $name_display_format = $this->Session->read('name_display_format');
        $name_display_format_array = explode(",", $name_display_format);

        foreach ($name_display_format_array as $key => $value) {
            switch ($name_display_format_array[$key]) {
                case "SecurityUser.first_name":
                    if ($first_name!="") $full_name .= $first_name." ";
                    break;
                case "SecurityUser.middle_name":
                    if ($middle_name!="") $full_name .= $middle_name." ";
                    break;
                case "SecurityUser.last_name":
                    if ($last_name!="") $full_name .= $last_name." ";
                    break;
                default: break;
            }
        }

        $data[$model]['full_name'] = trim($full_name);
        return $data[$model]['full_name'];
    }

    public function getAllData() {
        return array_merge($this->messages, $this->labelHelper->getAllData());
    }
}
