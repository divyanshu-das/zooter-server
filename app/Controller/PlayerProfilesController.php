<?php
App::uses('AppController', 'Controller');
/**
 * PlayerProfiles Controller
 *
 * @property PlayerProfile $PlayerProfile
 * @property PaginatorComponent $Paginator
 */
class PlayerProfilesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	public $api = 'player_profile';
  public $apiAction;

	public $apiEndPoints = array('edit');


	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('edit');

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

	public function edit() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"player_profile","action":"edit","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"3","batting_arm":"right","bowling_arm":"left",
    //                                 "bowling_style":"medium_pace"}}');
    $this->apiAction = 'edit';
      $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $parameters = json_decode($this->request->data[0], true)['data'];
      $this->apiResponse = $this->PlayerProfile->editPlayerProfile($userId,$parameters);
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
		$this->PlayerProfile->recursive = 0;
		$this->set('playerProfiles', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->PlayerProfile->exists($id)) {
			throw new NotFoundException(__('Invalid player profile'));
		}
		$options = array('conditions' => array('PlayerProfile.' . $this->PlayerProfile->primaryKey => $id));
		$this->set('playerProfile', $this->PlayerProfile->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->PlayerProfile->create();
			if ($this->PlayerProfile->save($this->request->data)) {
				return $this->flash(__('The player profile has been saved.'), array('action' => 'index'));
			}
		}
		$users = $this->PlayerProfile->User->find('list');
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
		if (!$this->PlayerProfile->exists($id)) {
			throw new NotFoundException(__('Invalid player profile'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->PlayerProfile->save($this->request->data)) {
				return $this->flash(__('The player profile has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('PlayerProfile.' . $this->PlayerProfile->primaryKey => $id));
			$this->request->data = $this->PlayerProfile->find('first', $options);
		}
		$users = $this->PlayerProfile->User->find('list');
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
		$this->PlayerProfile->id = $id;
		if (!$this->PlayerProfile->exists()) {
			throw new NotFoundException(__('Invalid player profile'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PlayerProfile->delete()) {
			return $this->flash(__('The player profile has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The player profile could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}
