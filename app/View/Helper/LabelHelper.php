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

class LabelHelper extends AppHelper {
	public $messages = array(
		// General labels
		'general' => array(
			'2mbLimit' => 'File size should not be larger than 2MB.',
			'academic' => 'Academic',
			'academicDetails' => 'Academic Details',
			'account' => 'Account',
			'action' => 'Action',
			'active' => 'Active',
			'add' => 'Add',
			'addNewEntry' => 'Add New Entry',
			'address' => 'Address',
			'allYears' => 'All Years',
			'attachment' => 'Attachment',
			'attachments' => 'Attachments',
			'attendance' => 'Attendance',
			'attendanceByDay' => 'Attendance by Day',
			'attendanceByLesson' => 'Attendance by Lesson',
			'back' => 'Back',
			'behaviour' => 'Behaviour',
			'behaviourCategory' => 'Behaviour Category',
			'cancel' => 'Cancel',
			'categories' => 'Categories',
			'category' => 'Category',
			'change' => 'Change',
			'changePassword' => 'Change Password',
			'chinese' => 'Chinese',
			'class' => 'Class',
			'compile' => 'Compile',
			'compileTitle' => 'Compile Translation File',
			'compileMsg' => 'Do you wish to compile this language?',
			'optionChooseOne' => 'Select One',
			'close' => 'Close',
			'code' => 'Code',
			'comment' => 'Comment',
			'contact' => 'Contact',
			'contacts' => 'Contacts',
			'contactPerson' => 'Contact Person',
			'created_user_id' => 'Created By',
			'created' => 'Created On',
			'dateClosed' => 'Date Closed',
			'dateOfBehaviour' => 'Date of Behaviour',
			'dateOpened' => 'Date Opened',
			'dateUploaded' => 'Date Uploaded',
			'default' => 'Default',
			'delete' => 'Delete',
			'deleteTitle' => 'Delete',
			'deleteMsg' => 'You are able to delete this record in the database. <br><br>All related information of this record will also be deleted.<br><br>Are you sure you want to do this?',
			'details' => 'Details',
			'description' => 'Description',
			'disabled' => 'Disabled',
			'download' => 'Download',
			'duration' => 'Duration',
			'edit' => 'Edit',
			'email' => 'Email',
			'enabled' => 'Enabled',
			'endTime' => 'End Time',
			'english' => 'English',
			'export' => 'Export',
			'fax' => 'Fax',
			'female' => 'Female',
			'file' => 'File',
			'filename' => 'File Name',
			'fileType' => 'File Type',
			'firstName' => 'First Name',
			'gender' => 'Gender',
			'general' => 'General',
			'grade' => 'Grade',
			'grades' => 'Grades',
			'grading' => 'Grading',
			'guardian' => 'Guardian',
			'guardians' => 'Guardians',
			'id' => 'Identification',
			'identity' => 'Identity',
			'idNoNotFound' => 'OpenEMIS ID or Name is not found',
			'idPlaceholder' => 'OpenEMIS ID, First Name or Last Name',
			'idPlaceholder2' => 'OpenEMIS ID or Name',
			'inactive' => 'Inactive',
			'infrastructure' => 'Infrastructure',
			'international_code' => 'International Code',
			'lastModifiedBy' => 'Last Modified By',
			'lastModifiedOn' => 'Last Modified On',
			'lastName' => 'Last Name',
			'location' => 'Location',
			'logout' => 'Logout',
			'malay' => 'Malay',
			'male' => 'Male',
			'marks' => 'Mark',
			'maths' => 'Maths',
			'max' => 'Max',
			'maximum' => 'Maximum',
			'min' => 'Min',
			'minimum' => 'Minimum',
			'modified_user_id' => 'Modified By',
			'modified' => 'Modified On',
			'myAccount' => 'My Account',
			'name' => 'Name',
			'national_code' => 'National Code',
			'new' => 'New',
			'no' => 'No',
			'noData' => 'No Data',
			'nonTeaching' => 'Non-Teaching', // to be removed
			'noOption' => 'No Option',
			'notSpecified' => 'not specified',
			'option' => 'Option',
			'optionNotFound' => 'Option Not Found',
			'order' => 'Order',
			'password' => 'Password',
			'period' => 'Period',
			'physics' => 'Physics',
			'postalCode' => 'Postal Code',
			'print' => 'Print',
			'profileImage' => 'Profile Image',
			'programme' => 'Programme',
			'programmes' => 'Programmes',
			'relationship' => 'Relationship',
			'remark' => 'Remark',
			'remarks' => 'Remarks',
			'remove' => 'Remove',
			'reportCard' => 'Report Card',
			'reorder' => 'Reorder',
			'result' => 'Result',
			'results' => 'Results',
			'save' => 'Save',
			'saveAsPDF' => 'Save as PDF',
			'schoolDays' => 'School Days',
			'search' => 'Search',
			'searchResults' => 'Search Results',
			'select' => 'Select',
			'selectFile' => 'Select File',
			'short_form' => 'Short Form',
			'startTime' => 'Start Time',
			'status' => 'Status',
			'subject' => 'Subject',
			'subjects' => 'Subjects',
			'to' => 'To',
			'total' => 'Total',
			'teacher' => 'Teacher',
			'teachers' => 'Teachers',
			'teaching' => 'Teaching', // to be removed
			'telephone' => 'Telephone',
			'timeOfBehaviour' => 'Time of Behaviour',
			'timetable' => 'Timetables',
			'title' => 'Title',
			'type' => 'Type',
			'types' => 'Types',
			'username' => 'Username',
			'value' => 'Value',
			'view' => 'View',
			'yes' => 'Yes',
			'backPrevPage' => "Back to previous page",
			'urlNotFound' => 'The requested address %s was not found on this server.',
			'enrollment' => 'Enrollment',
			'daysInMonth' => 'Days in Month'
		),
		
		'date' => array(
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'time' => 'Time',
			'date' => 'Date',
			'today' => 'Today',
			'mon' => 'Mon',
			'monday' => 'Monday',
			'tue' => 'Tue',
			'tuesday' => 'Tuesday',
			'wed' => 'Wed',
			'wednesday' => 'Wednesday',
			'thur' => 'Thur',
			'thursday' => 'Thursday',
			'fri' => 'Fri',
			'friday' => 'Friday',
			'sat' => 'Sat',
			'saturday' => 'Saturday',
			'sun' => 'Sun',
			'sunday' => 'Sunday',
			'january' => 'January',
			'february' => 'February',
			'march' => 'March',
			'april' => 'April',
			'may' => 'May',
			'june' => 'June',
			'july' => 'July',
			'aug' => 'August',
			'september' => 'September',
			'october' => 'October',
			'november' => 'November',
			'december' => 'December',
			'day' => 'Day',
			'month' => 'Month',
			'year' => 'Year',
			'years' => 'Years'
		),

		// Event Module
		'event' => array(
			'add' => 'Add Event',
			'addEvent' => 'Add Event', // will be removed
			'classEvent' => 'Class Event',
			'list' => 'List of Events',
			'listOfEvents' => 'List of Events',
			'schoolEvent' => 'School Event',
			'singularTitle' => 'Event',
			'title' => 'Events',
		),

		// Student Module
		'student' => array(
			'add' => 'Add Student',
			'addAcadProgress' => 'Add Academic Progress',
			'acadProgress' => 'Academic Progress',
			'addStudent' => 'Add Student',
			'all' => 'All Students',
			'alumni' => 'Alumni',
			'assessmentResult' => 'Assessment Result',
			'attendance' => 'Attendance',
			'current' => 'Current Student',
			'dayAttendance' => 'Day Attendance',
			'dropOut' => 'Drop Out',
			'grad' => 'Graduate',
			'lastLogin' => 'Last Login',
			'lessonAttendance' => 'Lesson Attendance',
			'list' => 'List of Students',
			'no' => 'Student No',
			'pass' => 'Pass Student',
			'progressDate' => 'Progress Date',
			'resultDetails' => 'Result Details',
			'state' => 'Student State',
			'studentStatus' => 'Student Status',
			'title' => 'Students'
		),

		// Staff Module
		'staff' => array(
			'add' => 'Add Staff',
			'all' => 'All Staff',
			'category' => 'Staff Category',
			'employment' => 'Employment',
			'list' => 'List of Staff',
			'nonTeaching' => 'Non Teaching',
			'myProfile' => 'My Profile',
			'staff' => 'Staff', // will be removed
			'staffCategory' => 'Staff Category', // will be removed
			'status' => 'Staff Status',
			'teaching' => 'Teaching',
			'title' => 'Staff'
		),

		// Guardian Module
		'guardian' => array(
			'add' => 'Add Guardian',
			'guardian' => 'guardian', // will be removed
			'guardians' => 'Guardians', // may be removed
			'list' => 'List of Guardians',
			'search' => 'Search Guardians',
			'singularTitle' => 'Guardian',
			'title' => 'Guardians'
		),

		'user' => array(
			'myProfile' => "My Profile"
		),

		// Administration Module
		'admin' => array(
			'activeDuration' => 'Active Duration',
			'admin' => 'Administration',
			'addUser' => 'Add User',
			'assessmentItems' => 'Assessment Items',
			'educationProgramme' => 'Education Programme',
			'educationProgrammes' => 'Education Programmes',
			'educationSubjects' => 'Education Subjects',
			'listOfUsers' => 'List of Users',
			'nationalAssessment' => 'National Assessment',
			'nationalAssessments' => 'National Assessments',
			'rcTemplateList' => 'List of Templates',
			'rcTemplates' => 'Report Card Templates', 
			'resultTypes' => 'Result Types',
			'title' => 'Administration',
			'userManagement' => 'User Management'
		),
		
		'Contact' => array(
			'contact_type_id' => 'Type',
			'main' => 'Preferred',
			'name' => 'Name',
			'title' => 'Contacts',
			'cname' => 'Name',
			'value' => 'Value'
		)
		,
		
		'InstitutionSite' => array(
			'validate' => array(
				'date_closed' => 'Date Closed cannot be earlier than Date Opened'
			),
			'photo_content' => 'School Logo',
			'areaid' => "Area ID"
		),
		
		'SClass' => array(
			'title' => 'Classes',
			'list' => 'List of Classes',
			'add' => 'Add Class',
			'seats_total' => 'Total Seats'
		),
		
		'Timetable' => array(
			'title' => 'Timetable'
		),

		'Attendance' => array(
			'title' => 'Attendance'
		),
		
		'ClassGrade' => array(
			'noGrades' => 'There are no grades'
		),
		
		'ClassStudent' => array(
			'title' => 'Students'
		),
		
		'ClassTeacher' => array(
			'title' => 'Teachers'
		),
		
		'ClassSubject' => array(
			'title' => 'Subjects'
		),
		
		'ClassAssignment' => array(
			'title' => 'Assignments'
		),
		
		'ClassResult' => array(
			'title' => 'Results',
			'marks' => 'Mark',
			'grading' => 'Grading'
		),
		
		'ClassLesson' => array(
			'addLesson' => 'Add Lesson',
			'allSubjects' => 'All Subjects',
			'allTeachers' => 'All Teachers',
			'editLesson' => 'Edit Lesson',
			'education_grade_subject_id' => 'Subject',
			'lesson_status_id' => 'Status',
			'name' => 'Lessons',
			'room' => 'Room',
			'room_id' => 'Room',
			'staff_id' => 'Staff',
			'title' => 'Lessons'
		),

		'Finance' => array(
			'title' => 'Finances',
		),
		
		'Student' => array(
			'title' => 'Students',
			'student_status_id' => 'Student Status',
			'openemisid' => 'OpenEMIS ID'
		),
		
		'Staff' => array(
			'title' => 'Staff',
			'staff_status_id' => 'Status',
			'staff_category_id' => 'Category',
			'openemisid' => 'OpenEMIS ID'
		),
		
		'Teacher' => array(
			'title' => 'Teachers'
		),
		
		'StudentBehaviour' => array(
			'name' => 'Behaviours',
			'behaviour_category_id' => 'Category'
		)
		,

		'StaffBehaviour' => array(
			'name' => 'Behaviours',
			'behaviour_category_id' => 'Category'
		)
		,
		
		'StudentAttachment' => array(
			'name' => 'Attachments',
		)
		,

		'StudentAttendanceDay' => array(
			'title' => 'Attendance',
		)
		,
		

		'StaffAttachment' => array(
			'name' => 'Attachments',
		)
		,
		
		'StaffEmployment' => array(
			'name' => 'Employments',
			'staff_employment_type_id' => 'Type'
		)
		,

		'StudentFee' => array(
			'amount' => 'amount',
			'comment' => 'Comment',
			'created_user_id' => 'Recorded By',
			'created' => 'Recorded On',
			'fee' => 'Fee',
			'fitem' => 'Item',
			'name' => 'Fees',
			'outstanding' => 'Outstanding',
			'paid' => 'Paid',
			'payment' => 'Payment',
			'type' => 'type'
		)
		,
		
		'StudentGuardian' => array(
			'name' => 'Guardian',
		),

		'StudentIdentity' => array(
			'expiry_date' => 'Expiry Date',
			'identity_type_id' => 'Type',
			'country_id' => 'Issue Location',
			'issue_date' => 'Issue Date',
			'name' => 'Identities',
			'number' => 'Number'
		),

		'StaffIdentity' => array(
			'expiry_date' => 'Expiry Date',
			'identity_type_id' => 'Type',
			'country_id' => 'Issue Location',
			'issue_date' => 'Issue Date',
			'name' => 'Identities',
			'number' => 'Number'
		),

		'GuardianIdentity' => array(
			'expiry_date' => 'Expiry Date',
			'identity_type_id' => 'Type',
			'country_id' => 'Issue Location',
			'issue_date' => 'Issue Date',
			'name' => 'Identities',
			'number' => 'Number'
		),

		'StudentResult' => array(
			'title' => 'Results',
			'marks' => 'Mark',
			'grading' => 'Grading'
		),

		'StudentReportCard' => array(
			'exam' => 'Exam',
			'overall' => 'Overall',
			'promotion' => 'Promotion',
			'reportDate' => 'Report Date',
			'reportSubtitle' => 'Report Subtitle',
			'reportTitle' => 'Report Title',
			'reportTitleData' => 'Student Report Card',
			'schoolName' => 'School Name',
			'studentClassPosition' => 'Class Position',
			'studentComments' => 'Comments',
			'studentConduct' => 'Conduct',
			'studentGradePercent' => 'Percentage',
			'studentGradePosition' => 'Results',
			'studentGradeResults' => 'School Name',
			'studentGradeTotal' => 'Total'
		),

		'Translation' => array(
			'title' => 'Translations',
		),
		
		'FieldOption' => array(
			'title' => 'Field Options',
			'visible' => 'Status'
		),
		
		'AssessmentResultType' => array(
			'min' => 'Minimum',
			'max' => 'Maximum',
			'name' => 'Type'
		),
		
		'SchoolYear' => array(
			'name' => 'School Year',
			'school_year_id' => 'School Year',
			'school_days' => 'School Days'
		),
		
		'Education' => array(
			'title' => 'Education',
			'structure' => 'Education Structure'
		),
		
		'EducationProgramme' => array(
			'title' => 'Education Programme'
		),

		'EducationFee' => array(
			'amount' => 'Fee',
			'description' => 'Description',
			'fee_type_id' => 'Type',
			'title' => 'Education Fees'
		),
		
		'EducationGrade' => array(
			'title' => 'Education Grade',
			'education_programme_id' => 'Education Programme'
		),
		
		'EducationGradesSubject' => array(
			'title' => 'Education Grades - Subjects'
		),
		
		'EducationSubject' => array(
			'title' => 'Education Subject',
			'name' => 'Name',
			'code' => 'Code'
		),
		
		'Assessment' => array(
			'title' => 'Assessments'
		),
		
		'AssessmentItemType' => array(
			'education_grade_id' => 'Education Grade',
			'visible' => 'Status'
		),
		
		'AssessmentItem' => array(
			'noSubjects' => 'There are no subjects',
			'weighting' => 'Weighting'
		),
		
		'SecurityUser' => array(
			'country_id' => 'Nationality',
			'first_name' => 'First Name',
			'middle_name' => 'Middle Name',
			'last_name' => 'Last Name',
			'full_name' => 'Name',
			// 'identification_no' => 'Identification No',
			'photo_content' => 'Photo',
			'dateOfBirth' => 'Date of Birth',
			'openemisid' => 'OpenEMIS ID'
		),
		
		'ConfigItem' => array(
			'title' => 'System Configurations',
			'label' => 'Name',
			'valueText' => 'Value'
		),
		
		'ConfigItemOption' => array(
			'notFound' => 'Value not found'
		),
		
		'validate' => array(
			'name' => 'Please enter a valid name'
		),

		'Administrator' => array(
			'name' => 'Admins',
			'list' => 'List of Admins',
			'addAdmin' => "Add Admin"
		),

		'CustomField' => array(
			'title' => 'Custom Fields',
			'list' => 'List of Custom Fields',
			'name' => 'Custom Field',
			'dataType' => 'Data Type',
			'customOption' => 'Options',
			'value' => 'Value',
			'text' => 'Text',
			'textArea' => 'Text Area',
			'number' => 'Number',
			'dropdown' => 'Dropdown',
		),

		'StudentCustomField' => array(
			'title' => 'Student Custom Fields',
			'is_mandatory' => 'Mandatory',
			'is_unique' => 'Unique',
		), 

		'StaffCustomField' => array(
			'title' => 'Staff Custom Fields',
			'is_mandatory' => 'Mandatory',
			'is_unique' => 'Unique',
		), 

		'GuardianCustomField' => array(
			'title' => 'Guardian Custom Fields',
			'is_mandatory' => 'Mandatory',
			'is_unique' => 'Unique',
		), 

		'GuardianStudent' => array(
			'name' => 'Students'
		),

		'AssessmentItemResult' => array(
			'marks' => 'Marks'
		),

		'StudentAttendanceLesson' => array(
			'featureNotAvailable' => 'Graph is not available for Attendance by Lesson.'
		),

		'StaffAttendanceDay' => array(
			'staffAttendance' => 'Staff Attendance'
		)
    );
	
	// PHPSM-30: A no-arg constructor is necessary in order to instantiate this class inside MessageComponent.
    public function __construct() {}
	
	public function get($code) {
        $index = explode('.', $code);
        $message = $this->messages;

        foreach($index as $i) {
            if(isset($message[$i])) {
                $message = $message[$i];
            } else {
                $message = false;
                break;
            }
        }
        return !is_bool($message) ? __($message) : $message;
    }

	public function getLabel($model, $key, $attr) {
		$labelKey = $model;
		if (array_key_exists('labelKey', $attr)) {
			$labelKey = $attr['labelKey'];
		}
		$code = $labelKey .'.'. $key;
		$label = $this->get($code);
		
		if($label === false) {
			$label = __(Inflector::humanize($key));
		}
		return $label;
	}

	public function getAllData() {
		return $this->messages;
	}

	public function getCurrencyFormat($value) {
    	if (is_numeric($value)) {
    		return number_format((float)$value, 2, '.', ',');
    	} else return $value;
    }

    public function convertLabelForCustomField($rawLabel) {
    	// just removes the 1st token as it is '---CustomField and the id'
    	$tok = strtok($rawLabel, ' ');
		$tArray = array();
		while ($tok !== false) {
			array_push($tArray, $tok);
			$tok = strtok(' ');	
		}
		array_splice($tArray,0,2);
		return implode($tArray,' ');
	}

	public function getCustomIdFromLabel($label) {
		$tok = strtok($rawLabel, ' ');
		$tok = strtok(' ');	
		return $tok;
	}
}