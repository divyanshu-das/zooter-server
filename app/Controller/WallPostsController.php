<?php
App::uses('AppController', 'Controller');
/**
 * WallPosts Controller
 *
 * @property WallPost $WallPost
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class WallPostsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'wall_post';
 	public $apiAction;

	public $apiEndPoints = array('share');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('share');

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

	public function share() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"wall_post_comment","action":"share",
    // 													"data":{"wall_post_id":"71","user_id":"16","text":"first text"},
    // 													"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'share';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
  	if ($result['validation_result']) {
  		$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
  		$wallPostId = $this->ZooterRequest->getRequestParam($result['request_data'], 'wall_post_id');
  		$sharedText = $this->ZooterRequest->getRequestParam($result['request_data'], 'text');
  		// $wallPostId = HashidsComponent::decrypt($wallPostId)[0];
  		$this->apiResponse = $this->WallPost->share($userId, $wallPostId, $sharedText);
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
		$this->WallPost->recursive = 0;
		$this->set('wallPosts', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->WallPost->exists($id)) {
			throw new NotFoundException(__('Invalid wall post'));
		}
		$options = array('conditions' => array('WallPost.' . $this->WallPost->primaryKey => $id));
		$this->set('wallPost', $this->WallPost->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->WallPost->create();
			if ($this->WallPost->save($this->request->data)) {
				$this->Session->setFlash(__('The wall post has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The wall post could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->WallPost->User->find('list');
		$locations = $this->WallPost->Location->find('list');
		$this->set(compact('users', 'locations'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->WallPost->exists($id)) {
			throw new NotFoundException(__('Invalid wall post'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->WallPost->save($this->request->data)) {
				$this->Session->setFlash(__('The wall post has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The wall post could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('WallPost.' . $this->WallPost->primaryKey => $id));
			$this->request->data = $this->WallPost->find('first', $options);
		}
		$users = $this->WallPost->User->find('list');
		$locations = $this->WallPost->Location->find('list');
		$this->set(compact('users', 'locations'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->WallPost->id = $id;
		if (!$this->WallPost->exists()) {
			throw new NotFoundException(__('Invalid wall post'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->WallPost->delete()) {
			$this->Session->setFlash(__('The wall post has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The wall post could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
