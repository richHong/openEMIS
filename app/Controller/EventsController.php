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

App::uses('AppController', 'Controller');

class EventsController extends AppController {
	public $uses = array('Event');
	
	public $components = array('Paginator');

	public $accessMapping = array(
		'delete' => 'delete',
		'getEventDates' => 'read'
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Navigation->addCrumb($this->Message->getLabel('event.title'), array('controller' => $this->params['controller'], 'action' => 'index'));
		$this->set('contentHeader', $this->Message->getLabel('event.title'));
		$this->set('model', 'Event');
	}

	public function index() {
		$this->Navigation->addCrumb($this->Message->getLabel('event.listOfEvents'));
		$date = isset($this->params['pass'][0]) ? $this->params['pass'][0] : null;
		$format = 'Y-m-d';
		if(is_null($date)) {
			$date = date($format);
		}
		$this->Event->recursive = -1;
		$data = $this->Event->find('all', array('recursive'=>-1, 'conditions'=>array('"' . $date . '" BETWEEN start_date and end_date'), 'order'=>array('start_date')));
		if(empty($data)) {
			$this->Message->alert('general.view.noRecords', array('type' => 'info'));
		}
		$this->set('data', $data);
		$this->set('date', $date);
	}

	public function view($id = null) {
		if(!$id || !$this->Event->exists($id)) {
            $this->Message->alert('general.view.notExists', array('type' => 'warn'));
            return $this->redirect(array('action' => 'index'));
        } else {
			$model = 'Event';
			$this->Event->recursive = 0;
			$data = $this->Event->findById($id);
			$name = $data[$model]['name'];
			$fields = $this->Event->getFields();
			
			$this->Navigation->addCrumb($name);
			$this->set('contentHeader', $name);
			$this->set('id', $id);
			$this->set('data', $data);
			$this->set('fields', $fields);
			$this->set('model', $model);
		}
    }

    public function add() {
        if ($this->request->is(array('post', 'put'))) {
            $this->Event->create();
			
			$this->request->data['Event']['type'] = 0; // school event
            if ($this->Event->saveAll($this->request->data)) {
                $this->Message->alert('general.add.success');
				$startDate = $this->Event->data['Event']['start_date'];
                return $this->redirect(array('action' => 'index', $startDate));
            } else {
                $this->Message->alert('general.add.failed', array('type' => 'error'));
            }
        }
		$fields = $this->Event->getFields();

        $this->Navigation->addCrumb($this->Message->getLabel('general.add'));
		$this->set('contentHeader', $this->Message->getLabel('event.addEvent'));
		$this->set('fields', $fields);
    }

    public function edit($id = null) {
		if(!$id || !$this->Event->exists($id)) {
            $this->Message->alert('general.view.notExists', array('type' => 'warn'));
            return $this->redirect(array('action' => 'index'));
        } else {
			if($this->request->is(array('post', 'put'))) {
				if ($this->Event->saveAll($this->request->data)) {
					$this->Message->alert('general.add.success');
					return $this->redirect(array('action' => 'view', $id));
				} else {
					$this->Message->alert('general.edit.failed', array('type' => 'error'));
				}
			}
			$model = 'Event';
			$this->Event->recursive = 0;
			$data = $this->Event->findById($id);
			$this->request->data = $data;
			$name = $data[$model]['name'];
			
			$fields = $this->Event->getFields();
			$this->Navigation->addCrumb($name);
			$this->set('contentHeader', $name);
			$this->set('id', $id);
			$this->set('data', $data);
			$this->set('fields', $fields);
		}
    }
	
    public function delete() {
		if($this->request->is('post')){
			$id = isset($this->params['pass'][0]) ? $this->params['pass'][0] : null;
			if(!$id || !$this->Event->exists($id)) {
				$this->Message->alert('general.view.notExists', array('type' => 'warn'));
				return $this->redirect(array('action' => 'index'));
			}
			$startDate = $this->params['pass'][1];
			$this->Event->delete($id);
			$this->Message->alert('general.delete.success');

			return $this->redirect(array('action' => 'index', date("Y-m-d", strtotime($startDate))));
		}
    }
	
	public function getEventDates() {
		$this->autoRender = false;
		if($this->request->is('ajax')) {
			$date = $this->params->query['date'];
			$days = $this->Event->getEventDaysByMonth(date('Y-m',strtotime($date)));
			return json_encode($days);
		}
	}
}
