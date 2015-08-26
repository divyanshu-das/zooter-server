<?php
App::uses('AppController', 'Controller');
/**
 * TeamStaffs Controller
 *
 * @property TeamStaff $TeamStaff
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class TeamStaffsController extends AppController {

/**
 * Components
 *
 * @var array
 */
  public $uses = array('TeamStaff');
	public $components = array('Paginator', 'Session');
	public $api = 'team_staff';
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

		if (in_array($this->action, $this->apiEndPoints)) {
      $this->autoRender = false;
    }
	}

		/**
		 * afterFilter callback
		 *
		 * @return void
		 */
	public function afterFilter() {
		if (!empty($this->apiResponse)) {
      $data = $this->ZooterResponse->prepareResponse($this->apiResponse, $this->api, $this->apiAction);
      $this->response->body($data);
    }
	}


  public function show() { 
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "team_staff","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->TeamStaff->showTeamStaff($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "team_staff","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->TeamStaff->deleteTeamStaff($id);
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
      "api": "team_staff",
      "action": "update",
      "appGuid": "aonecdad-345hldd-nuhoacfl",
      "data": { 
        "team_id":2,
        "team_staffs": [
           {
              "user_id": 1,
              "role": 1,
              "status": 1
          },
          {
              "id":2,
              "user_id": 2,
              "role": 5,
              "status": 3
          },
          {
              "id":"",
              "user_id": 1,
              "role": 5,
              "status": 3
          }
        ]
      }
    }
  ');
		$this->apiAction = 'update';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
  	if ($result['validation_result']) {	
      $teamId = $this->ZooterRequest->getRequestParam($result['request_data'], 'team_id');
       if ( ! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'team_id', $teamId)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid team_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $teamStaffs = $this->ZooterRequest->getRequestParam($result['request_data'], 'team_staffs');
  			$this->apiResponse = $this->TeamStaff->updateTeamStaffs($teamStaffs, $teamId);
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
		$this->TeamStaff->recursive = 0;
		$this->set('teamStaffs', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->TeamStaff->exists($id)) {
			throw new NotFoundException(__('Invalid team staff'));
		}
		$options = array('conditions' => array('TeamStaff.' . $this->TeamStaff->primaryKey => $id));
		$this->set('teamStaff', $this->TeamStaff->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->TeamStaff->create();
			if ($this->TeamStaff->save($this->request->data)) {
				$this->Session->setFlash(__('The team staff has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team staff could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$teams = $this->TeamStaff->Team->find('list');
		$users = $this->TeamStaff->User->find('list');
		$this->set(compact('teams', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->TeamStaff->exists($id)) {
			throw new NotFoundException(__('Invalid team staff'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->TeamStaff->save($this->request->data)) {
				$this->Session->setFlash(__('The team staff has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team staff could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('TeamStaff.' . $this->TeamStaff->primaryKey => $id));
			$this->request->data = $this->TeamStaff->find('first', $options);
		}
		$teams = $this->TeamStaff->Team->find('list');
		$users = $this->TeamStaff->User->find('list');
		$this->set(compact('teams', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->TeamStaff->id = $id;
		if (!$this->TeamStaff->exists()) {
			throw new NotFoundException(__('Invalid team staff'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TeamStaff->delete()) {
			$this->Session->setFlash(__('The team staff has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The team staff could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
