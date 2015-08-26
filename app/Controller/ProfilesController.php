<?php
App::uses('AppController', 'Controller');
/**
 * Profiles Controller
 *
 * @property Profile $Profile
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ProfilesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'profile';
  public $apiAction;
	public $apiEndPoints = array('edit_personal_info','edit_location');


	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('edit_personal_info','edit_location');

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

	public function edit_personal_info() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"profile","action":"edit_personal_info","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","description":"This is my new description",
    //                                   "first_name":"Nir56","email":"nir56@hotmail.com","mobile":"89763",
    //                                   "profile_image":"/user/image/56","cover_image":"/usr/image/78"
    //                                 }}');
    $this->apiAction = 'edit_personal_info';
      $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $parameters = json_decode($this->request->data[0], true)['data'];
      $this->apiResponse = $this->Profile->editPersonalInfo($userId,$parameters);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function edit_location() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"profile","action":"edit_location","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":  {"user_id":"6","latitude":15.456789,"longitude":76.345678,																			
				// 															"unique_identifier":"jbsdbrtyu",
				// 															"place":"xyz"
    //                                 }}');
    $this->apiAction = 'edit_location';
      $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $latitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'latitude');
      $longitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'longitude');
      $uniqueIdentifier = $this->ZooterRequest->getRequestParam($result['request_data'], 'unique_identifier');
      $place = $this->ZooterRequest->getRequestParam($result['request_data'], 'place');
      $this->apiResponse = $this->Profile->editLocation($userId,$place,$latitude,$longitude,$uniqueIdentifier);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

	public function admin_index() {
		$this->Profile->recursive = 0;
		$this->set('profiles', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Profile->exists($id)) {
			throw new NotFoundException(__('Invalid profile'));
		}
		$options = array('conditions' => array('Profile.' . $this->Profile->primaryKey => $id));
		$this->set('profile', $this->Profile->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Profile->create();
			if ($this->Profile->save($this->request->data)) {
				$this->Session->setFlash(__('The profile has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The profile could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->Profile->User->find('list');
		$cities = $this->Profile->City->find('list');
		$this->set(compact('users', 'cities'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Profile->exists($id)) {
			throw new NotFoundException(__('Invalid profile'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Profile->save($this->request->data)) {
				$this->Session->setFlash(__('The profile has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The profile could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Profile.' . $this->Profile->primaryKey => $id));
			$this->request->data = $this->Profile->find('first', $options);
		}
		$users = $this->Profile->User->find('list');
		$cities = $this->Profile->City->find('list');
		$this->set(compact('users', 'cities'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Profile->id = $id;
		if (!$this->Profile->exists()) {
			throw new NotFoundException(__('Invalid profile'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Profile->delete()) {
			$this->Session->setFlash(__('The profile has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The profile could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
