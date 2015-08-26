<?php
App::uses('AppController', 'Controller');
/**
 * FanClubs Controller
 *
 * @property FanClub $FanClub
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class FanClubsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = "fan_club";
	public $apiAction;

	public $apiEndPoints = array('create', 'update', 'view');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow('create', 'update', 'view');

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
			parent::afterFilter();
			if(!empty($this->apiResponse)){
	      $data = $this->ZooterResponse->prepareResponse($this->apiResponse, $this->api, $this->apiAction);
	      $this->response->body($data);
	    }
		}

	public function create() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->apiAction = "create";
    // $this->request->data = array('{"api":"fan_club","action":"create","appGuid": "aonecdad-345hldd-nuhoacfl", "apiKey": "b786bddbce6e15ec723c4551727143e259f55b2f","data":{"user_id":21, "name":"Divyanshu Fan club", "image_url" :"https://s3.amazonaws.com/zooternode/1412127147542b59aab9021", "cover_image_url" : "https://s3.amazonaws.com/zooternode/1412127147542b59aab9021", "tagline" : "this is my fan club", "favorite_movies":[{"id":1,"name":"DDLJ"},{"name":"KANK"}],"favorite_music":[{"id":2,"name":"Tujhe Dekha..."},{"name":"Dhoom Again"}],"favorite_hobbies":[{"id":1,"name":"Cycling"},{"name":"Singing"}],"favorite_singers":[{"id":1,"name":"Sonu Nigam"},{"name":"Udit Narayan"}],"favorite_holiday_destinations":[{"id":1,"name":"Thailand"},{"name":"Manali"}],"favorite_shots":[{"id":1,"name":"Cover Drive"},{"name":"Pull Shot"}],"favorite_dishes":[{"id":1,"name":"Tandoori Chicken"},{"name":"Fish Curry"}]}}');
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
      $tagline = $this->ZooterRequest->getRequestParam($result['request_data'], 'tagline');
      $imageUrl = $this->ZooterRequest->getRequestParam($result['request_data'], 'image_url');
      $coverImageUrl = $this->ZooterRequest->getRequestParam($result['request_data'], 'cover_image_url');
      $favorite['movies'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_movies');
      $favorite['music']= $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_music');
      $favorite['hobbies'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_hobbies');
      $favorite['singers'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_singers');
      $favorite['holiday_destinations'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_holiday_destinations');
      $favorite['shots']= $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_shots');
      $favorite['dishes'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_dishes');
      $this->apiResponse = $this->FanClub->createFanClub($userId, $name, $imageUrl, $coverImageUrl, $favorite, $tagline);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

	public function update() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->apiAction = "update";
    // $this->request->data = array('{"api":"fan_club","action":"update","appGuid": "aonecdad-345hldd-nuhoacfl", "apiKey": "b786bddbce6e15ec723c4551727143e259f55b2f","data":{"id":"2", "user_id":21, "name":"Divyanshu Fan club II", "image_url" :"https://s3.amazonaws.com/zooternode/1412127147542b59aab9021", "cover_image_url" : "https://s3.amazonaws.com/zooternode/1412127147542b59aab9021", "tagline" : "this is my fan club", "favorite_movies":[{"id":1,"name":"DDLJ"},{"name":"KANK"}],"favorite_music":[{"id":2,"name":"Tujhe Dekha..."},{"name":"Dhoom Again"}],"favorite_hobbies":[{"id":1,"name":"Cycling"},{"name":"Singing"}],"favorite_singers":[{"id":1,"name":"Sonu Nigam"},{"name":"Udit Narayan"}],"favorite_holiday_destinations":[{"id":1,"name":"Thailand"},{"name":"Manali"}],"favorite_shots":[{"id":1,"name":"Cover Drive"},{"name":"Pull Shot"}],"favorite_dishes":[{"id":1,"name":"Tandoori Chicken"},{"name":"Fish Curry"}]}}');
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
      $tagline = $this->ZooterRequest->getRequestParam($result['request_data'], 'tagline');
      $imageUrl = $this->ZooterRequest->getRequestParam($result['request_data'], 'image_url');
      $coverImageUrl = $this->ZooterRequest->getRequestParam($result['request_data'], 'cover_image_url');
      $favorite['movies'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_movies');
      $favorite['music']= $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_music');
      $favorite['hobbies'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_hobbies');
      $favorite['singers'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_singers');
      $favorite['holiday_destinations'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_holiday_destinations');
      $favorite['shots']= $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_shots');
      $favorite['dishes'] = $this->ZooterRequest->getRequestParam($result['request_data'], 'favorite_dishes');
      $this->apiResponse = $this->FanClub->updateFanClub($id, $userId, $name, $imageUrl, $coverImageUrl, $favorite, $tagline);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

	public function view() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    $this->apiAction = "view";
    // $this->request->data = array('{"api":"fan_club","action":"view","appGuid": "aonecdad-345hldd-nuhoacfl", "apiKey": "b786bddbce6e15ec723c4551727143e259f55b2f","data":{"id":"2", "user_id":21}}');
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $this->apiResponse = $this->FanClub->viewFanClub($id, $userId);
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
		$this->FanClub->recursive = 0;
		$this->set('fanClubs', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->FanClub->exists($id)) {
			throw new NotFoundException(__('Invalid fan club'));
		}
		$options = array('conditions' => array('FanClub.' . $this->FanClub->primaryKey => $id));
		$this->set('fanClub', $this->FanClub->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->FanClub->create();
			if ($this->FanClub->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->FanClub->User->find('list');
		$images = $this->FanClub->Image->find('list');
		$this->set(compact('users', 'images'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->FanClub->exists($id)) {
			throw new NotFoundException(__('Invalid fan club'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FanClub->save($this->request->data)) {
				$this->Session->setFlash(__('The fan club has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fan club could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('FanClub.' . $this->FanClub->primaryKey => $id));
			$this->request->data = $this->FanClub->find('first', $options);
		}
		$users = $this->FanClub->User->find('list');
		$images = $this->FanClub->Image->find('list');
		$this->set(compact('users', 'images'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->FanClub->id = $id;
		if (!$this->FanClub->exists()) {
			throw new NotFoundException(__('Invalid fan club'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FanClub->delete()) {
			$this->Session->setFlash(__('The fan club has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The fan club could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
