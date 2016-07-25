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

class SearchComponent extends Component {
	private $controller;
	
	public $components = array('Session', 'Message');
	
	public function initialize(Controller $controller) {
		$this->controller =& $controller;
	}
	
	//called after Controller::beforeFilter()
	public function startup(Controller $controller) {}
	
	//called after Controller::beforeRender()
	public function beforeRender(Controller $controller) {
	}
	
	//called after Controller::render()
	public function shutdown(Controller $controller) {}
	
	//called before Controller::redirect()
	public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {}
	
	public function search($model, $settings=array()) {
		$request = $this->controller->request;
		$params = $request->params;
		
		$_settings = array(
			'sortDefault' => '',
			'settings' => array()
		);
		$_settings = array_merge($_settings, $settings);
		$order = array();
		$class = $model;
		$search = '';
		$sortBy = isset($request->params['named']['sort']) ? $request->params['named']['sort'] : $_settings['sortDefault'];
		$sortOrder = isset($request->params['named']['direction']) ? $request->params['named']['direction'] : 'asc';
		$pageNo = isset($request->params['named']['page']) ? $request->params['named']['page'] : 1;
		$conditions = array();
		if ($this->Session->check($model . '.search.sort.by')) {
			$by = $this->Session->read($model . '.search.sort.by');
			if (!empty($by)) {			
				$sortBy = $this->Session->read($model . '.search.sort.by');
				$sortOrder = $this->Session->read($model . '.search.sort.order');
				
				$order_pieces = explode(",", $sortBy);
				if (sizeof($order_pieces) > 1) {
					// handles more than 1 sort field
					foreach ($order_pieces as $key => $value) {
						$order_pieces[$key] .= ' ' . $sortOrder;
					}
					$order = implode(",", $order_pieces);
				} else {
					$order = $sortBy . ' ' . $sortOrder;
				}
				$this->Session->write($model . '.search.sort.processedOrder', $order);
			}
		}

		if ($request->is(array('post', 'put'))) {
			$readModel = $model;
			if (isset($_settings['usingModel'])) {
				$readModel = $_settings['usingModel'];
			}

			if (!isset($request->data[$readModel]['sortBy'])) {
				$conditions = $request->data;
				$this->Session->write($model . '.search.conditions', $conditions);
			} else {
				$conditions = $this->Session->read($model . '.search.conditions');
			}
			$sortBy = !empty($request->data[$readModel]['sortBy']) ? $request->data[$readModel]['sortBy'] : $_settings['sortDefault'];
			$sortOrder = !empty($request->data[$readModel]['sortOrder']) ? $request->data[$readModel]['sortOrder'] : 'asc';
			$pageNo = !empty($request->data[$model]['pageNo']) ? $request->data[$model]['pageNo'] : 1;

			$_settings['settings']['page'] = $pageNo;
			$this->Session->write($model . '.search.sort.by', $sortBy);
			$this->Session->write($model . '.search.sort.order', $sortOrder);
		} else {
			if ($this->Session->check($model . '.search.conditions')) {
				$conditions = $this->Session->read($model . '.search.conditions');
			}
		}

		$_settings['settings']['order'] = $order;
		$this->controller->Paginator->settings = $_settings['settings'];
		$data = $this->controller->paginate($class, $conditions);

		if (empty($data)) {
			$this->Message->alert('general.search.noResult');
			//$this->Session->delete($model . '.search.conditions');
		}
		
		$this->controller->set('searchValue', $search);
		$this->controller->set('sort', array('by' => $sortBy, 'order' => $sortOrder));
		$this->controller->set('pageNo', $pageNo);
		return $data;
	}

	public function clearSearchParams($model) {
		// clears out the session for list view\
		$this->Session->delete($model.'.search');
	}
}
