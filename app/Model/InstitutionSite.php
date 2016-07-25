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
App::uses('MessageComponent', 'Controller/Component');

class InstitutionSite extends AppModel {
	public $actsAs = array(
		'Year' => array('date_opened' => 'year_opened', 'date_closed' => 'year_closed'),
		'DatePicker' => array('date_opened', 'date_closed')
	);
	
	public $belongsTo = array(
		'Country',
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
            'code' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('code')
                )
            ),
            'address' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('address')
                )
            ),
            'postal_code' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('postalCode')
                )
            ),
			'areaid' => array(
				'ruleAreaId' => array(
					'allowEmpty' => true,
					'rule' => 'checkAreaId',
					'message' => $this->getErrorMessage('areaid')
				)
			),
            'email' => array(
                'emailVal' => array(
                    'rule'    => array('email', true),
                    'allowEmpty'=>true,
                    'message' => $this->getErrorMessage('email')
                )
            ),
            'date_opened' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => $this->getErrorMessage('dateOpened')
                )
            )
			,
            'date_closed' => array(
                'comparison' => array(
                    'rule' => array('fieldComparison', '>', 'date_opened'),
                    'allowEmpty' => true,
                    'message' => $this->getErrorMessage('dateClosedGreater')
                )
            )
        );
    }
	
	public function getFields($options=array()) {
		parent::getFields();
		$this->fields['photo_name']['visible'] = false;
		$this->fields['photo_content']['type'] = 'image';
		$this->fields['photo_content']['visible'] = array('edit' => true);
		$this->fields['year_opened']['visible'] = false;
		$this->fields['year_closed']['visible'] = false;
		$this->fields['country_id']['type'] = 'select';
		$this->fields['country_id']['options'] = $this->Country->getOptions();
		return $this->fields;
	}

	public function checkAreaId() {
		if(ctype_alpha($this->data[$this->alias]['areaid'])) {
			// check if under 3 characters
			if (strlen($this->data[$this->alias]['areaid'])<=3) {
				return true;
			} 
		}
		return false;
	}
}
