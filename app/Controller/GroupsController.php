<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Group $Group
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class GroupsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'group';
  public $apiAction;

	public $apiEndPoints = array('add', 'get_eligible_users');

/**
 * beforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'get_eligible_users');

		if(in_array($this->action, $this->apiEndPoints)){
      $this->autoRender = false;
    }
	}

/**
 * afterFilter callback
 *
 * @return void
 */
	public function afterFilter() {
		if(!empty($this->apiResponse)){
      $data = $this->ZooterResponse->prepareResponse($this->apiResponse, $this->api, $this->apiAction);
      $this->response->body($data);
    }
	}

	public function add() {
		// $this->request->data = array('{
  //     "api": "group",
  //     "action": "add_group",
  //     "data": {
  //         "user_id": "5",
  //         "apiKey": "ce4127191419d557ffa44b5453a30617d1f8e981",
  //         "name": "test group three",
  //         "image": "abc.jpg",
  //         "members": [
  //             {
  //                 "user_id": "1"
  //             },
  //             {
  //                 "user_id": "7"
  //             },
  //             {
  //                 "user_id": "5"
  //             }
  //         ]
  //     },
  //     "appGuid": "aonecdad-345hldd-nuhoacfl"
  //   }');
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		$this->apiAction = 'add';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
      $image = $this->ZooterRequest->getRequestParam($result['request_data'], 'image');
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $members = $this->ZooterRequest->getRequestParam($result['request_data'], 'members');
      $this->apiResponse = $this->Group->addGroup($name, $image, $userId, $members);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

	public function get_eligible_users() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api":"group","action":"get_eligible_users","appGuid":"aonecdad-345hldd-nuhoacfl","data":{"user_id":"5"}}');
		$this->apiAction = 'get_eligible_users';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $this->apiResponse = $this->Group->getEligibleUsersList($userId);
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
		$this->Group->recursive = 0;
		$this->set('groups', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
		$this->set('group', $this->Group->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->Group->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
			$this->request->data = $this->Group->find('first', $options);
		}
		$users = $this->Group->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Invalid group'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Group->delete()) {
			$this->Session->setFlash(__('The group has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The group could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
