<?php
App::uses('AppController', 'Controller');
/**
 * Locations Controller
 *
 * @property Location $Location
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class LocationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'location';
  public $apiAction;

	public $apiEndPoints = array('nearby_locations','create');


	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('nearby_locations','create');

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
	
	public function nearby_locations(){
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "location","action": "nearby_locations","appGuid": "aonecdad-345hldd-nuhoacfl",
		// 																	"data":  {"latitude": 15.390043,
		// 																	"longitude":73.87734799999998,"distance": 556}}');
		$this->apiAction = 'nearby_locations'; //Distance is in kilometers
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$latitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'latitude');
			$longitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'longitude');
			$distance = $this->ZooterRequest->getRequestParam($result['request_data'], 'distance');
		  $this->apiResponse = $this->Location->getNearbyLocation($latitude,$longitude,$distance);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function create(){
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "location","action": "create","appGuid": "aonecdad-345hldd-nuhoacfl",
		// 																	"data":  {"latitude": 10.6139,"longitude":71.2089,																			
		// 																	"unique_identifier":"utyrtropWDe4vzsRQAIREDBgguy",
		// 																	"place":"bhakti palace,NH1,tarapore"
		// 																}}');
		$this->apiAction = 'create';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$latitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'latitude');
			$longitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'longitude');
			$uniqueIdentifier = $this->ZooterRequest->getRequestParam($result['request_data'], 'unique_identifier');
			$place = $this->ZooterRequest->getRequestParam($result['request_data'], 'place');
		  $this->apiResponse = $this->Location->saveLocation($place,$latitude,$longitude,$uniqueIdentifier);
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
		$this->Location->recursive = 0;
		$this->set('locations', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Location->exists($id)) {
			throw new NotFoundException(__('Invalid location'));
		}
		$options = array('conditions' => array('Location.' . $this->Location->primaryKey => $id));
		$this->set('location', $this->Location->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Location->create();
			if ($this->Location->save($this->request->data)) {
				$this->Session->setFlash(__('The location has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The location could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Location->exists($id)) {
			throw new NotFoundException(__('Invalid location'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Location->save($this->request->data)) {
				$this->Session->setFlash(__('The location has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The location could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Location.' . $this->Location->primaryKey => $id));
			$this->request->data = $this->Location->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Location->id = $id;
		if (!$this->Location->exists()) {
			throw new NotFoundException(__('Invalid location'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Location->delete()) {
			$this->Session->setFlash(__('The location has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The location could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
