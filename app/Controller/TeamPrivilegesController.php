<?php
App::uses('AppController', 'Controller');
/**
 * TeamPrivileges Controller
 *
 * @property TeamPrivilege $TeamPrivilege
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class TeamPrivilegesController extends AppController {

/**
 * Components
 *
 * @var array
 */
  public $uses = array('TeamPrivilege');
  public $components = array('Paginator', 'Session');
  public $api = 'team_privilege';
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

    if ( in_array($this->action, $this->apiEndPoints)) {
      $this->autoRender = false;
    }
  }

    /**
     * afterFilter callback
     *
     * @return void
     */
  public function afterFilter() {
    if ( ! empty($this->apiResponse)) {
       $data = $this->ZooterResponse->prepareResponse($this->apiResponse, $this->api, $this->apiAction);
       $this->response->body($data);
     }
  }

  public function show() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "team_privilege","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->TeamPrivilege->showTeamPrivilege($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "team_privilege","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse =  $this->TeamPrivilege->deleteTeamPrivilege($id);
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
        "api": "team_privilege",
        "action": "update",
        "appGuid": "aonecdad-345hldd-nuhoacfl",
        "data": {
          "team_id": 2,
          "team_privileges": [
            {
              "id": 5,
              "user_id": 1,
              "is_admin": 1
            },
            {
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
      $teamId= $this->ZooterRequest->getRequestParam($result['request_data'], 'team_id');
       if ( ! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'team_id', $teamId)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid team_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $teamPrivileges= $this->ZooterRequest->getRequestParam($result['request_data'], 'team_privileges');
        $this->apiResponse = $this->TeamPrivilege->updateTeamPrivileges( $teamId, $teamPrivileges);
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
		$this->TeamPrivilege->recursive = 0;
		$this->set('teamPrivileges', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->TeamPrivilege->exists($id)) {
			throw new NotFoundException(__('Invalid team privilege'));
		}
		$options = array('conditions' => array('TeamPrivilege.' . $this->TeamPrivilege->primaryKey => $id));
		$this->set('teamPrivilege', $this->TeamPrivilege->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->TeamPrivilege->create();
			if ($this->TeamPrivilege->save($this->request->data)) {
				$this->Session->setFlash(__('The team privilege has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team privilege could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$teams = $this->TeamPrivilege->Team->find('list');
		$users = $this->TeamPrivilege->User->find('list');
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
		if (!$this->TeamPrivilege->exists($id)) {
			throw new NotFoundException(__('Invalid team privilege'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->TeamPrivilege->save($this->request->data)) {
				$this->Session->setFlash(__('The team privilege has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The team privilege could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('TeamPrivilege.' . $this->TeamPrivilege->primaryKey => $id));
			$this->request->data = $this->TeamPrivilege->find('first', $options);
		}
		$teams = $this->TeamPrivilege->Team->find('list');
		$users = $this->TeamPrivilege->User->find('list');
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
		$this->TeamPrivilege->id = $id;
		if (!$this->TeamPrivilege->exists()) {
			throw new NotFoundException(__('Invalid team privilege'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TeamPrivilege->delete()) {
			$this->Session->setFlash(__('The team privilege has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The team privilege could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
