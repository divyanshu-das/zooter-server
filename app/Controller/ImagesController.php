<?php
App::uses('AppController', 'Controller');
/**
 * Images Controller
 *
 * @property Image $Image
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ImagesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	public $api = 'image';
  public $apiAction;

	public $apiEndPoints = array('edit','create','delete_match_pic');


	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('edit','create','delete_match_pic');

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

	public function create(){
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "image","action": "create","appGuid": "aonecdad-345hldd-nuhoacfl",
		// 																	"data":{"user_id":6,"match_id":1,
  //   																		 "images":[{"caption":"","url":"/img/img27","snap_date_time":"",
  //   																					"location":{"latitude": 21.6139,"longitude":75.2089,																			
		// 																						"unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
		// 																						"place":"bhakti palace,NH1,tarapore"}
  //   																				},
  //   																				{"caption":"","url":"/img/img28","snap_date_time":"",
  //   																					"location":{"latitude": 21.6139,"longitude":75.2089,																			
		// 																						"unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
		// 																						"place":"bhakti palace,NH1,tarapore"},
		// 																						"is_cover_image":true
  //   																				},
  // 																					{"caption":"","url":"/img/img29","snap_date_time":"",
  //   																					"location":{"latitude": 21.6139,"longitude":75.2089,																			
		// 																						"unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
		// 																						"place":"bhakti palace,NH1,tarapore"}
  //   																				}
  //   																			]
		// 																}}');
		$this->apiAction = 'create';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
			$parameters = json_decode($this->request->data[0], true)['data'];
			$images = $this->ZooterRequest->getRequestParam($result['request_data'], 'images');
		  $this->apiResponse = $this->Image->addImages($userId,$images,$parameters);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function edit() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"image","action":"edit","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","id":58,"caption":"This is new ",
    //                                   "snap_date_time":"","location":{"latitude": 21.6139,"longitude":75.2089,																			
				// 																				"unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
				// 																				"place":"bhakti palace,NH1,tarapore"}
    //                                 }}');
    $this->apiAction = 'edit';
      $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $parameters = json_decode($this->request->data[0], true)['data'];
      $this->apiResponse = $this->Image->editImage($userId,$parameters);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function delete_match_pic() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"image","action":"delete_match_pic","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","match_id":1,"id":66
    //                                 }}');
    $this->apiAction = 'delete_match_pic';
      $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $imageId = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $parameters = json_decode($this->request->data[0], true)['data'];
      $this->apiResponse = $this->Image->deleteMatchPic($userId,$matchId,$imageId);
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
		$this->Image->recursive = 0;
		$this->set('images', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Image->exists($id)) {
			throw new NotFoundException(__('Invalid image'));
		}
		$options = array('conditions' => array('Image.' . $this->Image->primaryKey => $id));
		$this->set('image', $this->Image->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Image->create();
			if ($this->Image->save($this->request->data)) {
				$this->Session->setFlash(__('The image has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The image could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$albums = $this->Image->Album->find('list');
		$locations = $this->Image->Location->find('list');
		$users = $this->Image->User->find('list');
		$this->set(compact('albums', 'locations', 'users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Image->exists($id)) {
			throw new NotFoundException(__('Invalid image'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Image->save($this->request->data)) {
				$this->Session->setFlash(__('The image has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The image could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Image.' . $this->Image->primaryKey => $id));
			$this->request->data = $this->Image->find('first', $options);
		}
		$albums = $this->Image->Album->find('list');
		$locations = $this->Image->Location->find('list');
		$users = $this->Image->User->find('list');
		$this->set(compact('albums', 'locations', 'users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Image->id = $id;
		if (!$this->Image->exists()) {
			throw new NotFoundException(__('Invalid image'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Image->delete()) {
			$this->Session->setFlash(__('The image has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The image could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
