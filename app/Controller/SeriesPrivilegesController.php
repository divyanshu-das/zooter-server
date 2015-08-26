<?php
App::uses('AppController', 'Controller');
/**
 * SeriesPrivileges Controller
 *
 * @property SeriesPrivilege $SeriesPrivilege
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class SeriesPrivilegesController extends AppController {

/**
 * Components
 *
 * @var array
 */

	public $uses = array('SeriesPrivilege');
	public $components = array('Paginator', 'Session');
	public $api = 'series_privilege';
  public $apiAction;
	public $apiEndPoints = array('show','delete','update');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('show','delete','update');

		if (in_array($this->action, $this->apiEndPoints)){
      $this->autoRender = false;
    }
	}

		/**
		 * afterFilter callback
		 *
		 * @return void
		 */
	public function afterFilter() {
		if (!empty($this->apiResponse)){
       $data = $this->ZooterResponse->prepareResponse($this->apiResponse, $this->api, $this->apiAction);
       $this->response->body($data);
     }
	}

public function show(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "series_privilege","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->SeriesPrivilege->showSeriesPrivilege($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "series_privilege","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->SeriesPrivilege->deleteSeriesPrivilege($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function update() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    
    $this->request->data = array('
    {
        "api": "series_privilege",
        "action": "update",
        "appGuid": "aonecdad-345hldd-nuhoacfl",
        "data": {
          "series_id": 1,
          "series_privileges": [
            {
              "id": 1,
              "series_id": 1,
              "user_id": 1,
       				"is_admin": 1
            },
            {
              "series_id": 1,
              "user_id": 1,
              "is_admin": 0
            }
          ]
        }
      }
    ');
    $this->apiAction = 'update';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
    if ($result['validation_result']) { 
      $seriesId= $this->ZooterRequest->getRequestParam($result['request_data'], 'series_id');
      if (! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'series_id', $seriesId)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid series_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $seriesPrivileges= $this->ZooterRequest->getRequestParam($result['request_data'], 'series_privileges');
        $this->apiResponse = $this->SeriesPrivilege->updateSeriesPrivileges( $seriesId, $seriesPrivileges);
      }
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
    
  }

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->SeriesPrivilege->recursive = 0;
		$this->set('seriesPrivileges', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->SeriesPrivilege->exists($id)) {
			throw new NotFoundException(__('Invalid series privilege'));
		}
		$options = array('conditions' => array('SeriesPrivilege.' . $this->SeriesPrivilege->primaryKey => $id));
		$this->set('seriesPrivilege', $this->SeriesPrivilege->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->SeriesPrivilege->create();
			if ($this->SeriesPrivilege->save($this->request->data)) {
				$this->Session->setFlash(__('The series privilege has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The series privilege could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$series = $this->SeriesPrivilege->Series->find('list');
		$users = $this->SeriesPrivilege->User->find('list');
		$this->set(compact('series', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->SeriesPrivilege->exists($id)) {
			throw new NotFoundException(__('Invalid series privilege'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SeriesPrivilege->save($this->request->data)) {
				$this->Session->setFlash(__('The series privilege has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The series privilege could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('SeriesPrivilege.' . $this->SeriesPrivilege->primaryKey => $id));
			$this->request->data = $this->SeriesPrivilege->find('first', $options);
		}
		$series = $this->SeriesPrivilege->Series->find('list');
		$users = $this->SeriesPrivilege->User->find('list');
		$this->set(compact('series', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->SeriesPrivilege->id = $id;
		if (!$this->SeriesPrivilege->exists()) {
			throw new NotFoundException(__('Invalid series privilege'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->SeriesPrivilege->delete()) {
			$this->Session->setFlash(__('The series privilege has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The series privilege could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
