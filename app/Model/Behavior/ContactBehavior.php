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

class ContactBehavior extends ModelBehavior {
	public function afterSave(Model $model, $created, $options = array()) {
		if ($model->data[$model->alias]['main'] != '0') {
			// if main was set as 1.. set the rest to 0
			$model->updateAll(
				array($model->alias.'.main' => "0"),
				array(
					$model->alias.'.contact_type_id = ' => $model->data[$model->alias]['contact_type_id'],
					$model->alias.'.id <> ' => $model->id,
					$model->alias.'.security_user_id = ' => $model->data[$model->alias]['security_user_id']
					)
			);
		}
		return parent::afterSave($model, $created, $options);
	}

	public function beforeValidate(Model $model, $options = array()) {
		if (array_key_exists($model->alias, $model->request->data)) {
			if (array_key_exists('contact_type_id', $model->data[$model->alias])) {
				$model->validator()->remove('value');
				switch ($model->data[$model->alias]['contact_type_id']) {
					// 1 -> Mobile, 2 -> Phone, 3 -> Fax, 4 -> Email, 5 -> Other
					case '1': case '2': case '3':
						$model->validator()->add('value', 'required', array(
						    'rule' => 'Numeric',
						    'message' => $model->getErrorMessage('valuePhone'))
						);
						break;
					case '4': 
						$model->validator()->add('value', 'required', array(
					        'rule'    => array('email', true),
					        'message' => $model->getErrorMessage('valueEmail'))
						);
						break;
					default: // 5 -> Other
						$model->validator()->add('value', 'required', array(
						    'rule' => 'notEmpty',
						    'message' => $model->getErrorMessage('value'))
						);
						break;
				}
			}
		}

		$model->validator()->remove('name');
		$model->validator()->add('name', 'required', array(
	        'rule'    => 'notEmpty',
	        'message' => $model->getErrorMessage('value'))
		);

		$model->validator()->remove('contact_type_id');
		$model->validator()->add('contact_type_id', 'required', array(
	        'rule'    => 'notEmpty',
	        'message' => $model->getErrorMessage('contact_type_id'))
		);


		return parent::beforeValidate($model, $options);
	}

	public function getPreferredContact(Model $model, $securityUserID) {
		$mainContacts = $model->find('list', array(
			'fields' => array('contact_type_id', 'value'),
			'recursive' => 0, 
			'conditions' => array(
				'security_user_id' => $securityUserID,
				'main' => 1,
				),
			'order' => array('main desc', 'contact_type_id asc')
		));
		return $mainContacts;
    }

    public function getPreferredPhone(Model $model, $securityUserID) {
    	$mainContacts = $model->find('first', array(
			'conditions' => array(
				'security_user_id' => $securityUserID,
				'main' => 1,
				'contact_type_id' => array(1,2)
				),
			'order' => array('main desc', 'contact_type_id asc')
		));
		return (!empty($mainContacts)?$mainContacts[$model->alias]['value']:null);
    }

    public function getPreferredEmail(Model $model, $securityUserID) {
    	$mainContacts = $model->find('first', array(
			'conditions' => array(
				'security_user_id' => $securityUserID,
				'main' => 1,
				'contact_type_id' => array(4)
				),
			'order' => array('main desc', 'contact_type_id asc')
		));
		return (!empty($mainContacts)?$mainContacts[$model->alias]['value']:null);
    }

    public function getMainContacts(Model $model, $securityUserID, $options=array()) {
    	$mainContacts = $model->find('all', array(
			'recursive' => 0,
			'fields' => array('GuardianContact.value','ContactType.name'),
			'conditions' => array(
				'security_user_id' => $securityUserID,
				'main' => 1
				),
			'order' => array('main desc', 'contact_type_id asc')
		));
		return $mainContacts;
    }
}
