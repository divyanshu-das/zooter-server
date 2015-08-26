<?php
App::uses('AppController', 'Controller');
/**
 * Albums Controller
 *
 * @property Album $Album
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class AlbumsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	public $api = 'album';
  public $apiAction;

	public $apiEndPoints = array('create');


	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('create');

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
		// $this->request->data = array('{"api": "album","action": "create","appGuid": "aonecdad-345hldd-nuhoacfl",
		// 																	"data":{"user_id":6,"album":{"name":"first album",
		// 																					"location":{"latitude": 21.6139,"longitude":75.2089,																			
		// 																						"unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
		// 																						"place":"bhakti palace,NH1,tarapore"}
  //   																				 },
  //   																		 "images":[{"caption":"","url":"/img/img22","snap_date_time":"",
  //   																					"location":{"latitude": 21.6139,"longitude":75.2089,																			
		// 																						"unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
		// 																						"place":"bhakti palace,NH1,tarapore"}
  //   																				},
  //   																				{"caption":"","url":"/img/img23","snap_date_time":"",
  //   																					"location":{"latitude": 21.6139,"longitude":75.2089,																			
		// 																						"unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
		// 																						"place":"bhakti palace,NH1,tarapore"},
		// 																						"is_cover_image":true
  //   																				},
  // 																					{"caption":"","url":"/img/img24","snap_date_time":"",
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
			$album = $this->ZooterRequest->getRequestParam($result['request_data'], 'album');
			$images = $this->ZooterRequest->getRequestParam($result['request_data'], 'images');
		  $this->apiResponse = $this->Album->createAlbum($userId,$album,$images);
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
		$this->Album->recursive = 0;
		$this->set('albums', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Album->exists($id)) {
			throw new NotFoundException(__('Invalid album'));
		}
		$options = array('conditions' => array('Album.' . $this->Album->primaryKey => $id));
		$this->set('album', $this->Album->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Album->create();
			if ($this->Album->save($this->request->data)) {
				$this->Session->setFlash(__('The album has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The album could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->Album->User->find('list');
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
		if (!$this->Album->exists($id)) {
			throw new NotFoundException(__('Invalid album'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Album->save($this->request->data)) {
				$this->Session->setFlash(__('The album has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The album could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Album.' . $this->Album->primaryKey => $id));
			$this->request->data = $this->Album->find('first', $options);
		}
		$users = $this->Album->User->find('list');
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
		$this->Album->id = $id;
		if (!$this->Album->exists()) {
			throw new NotFoundException(__('Invalid album'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Album->delete()) {
			$this->Session->setFlash(__('The album has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The album could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
