<?php
App::uses('AppController', 'Controller');
/**
 * WallPostLikes Controller
 *
 * @property WallPostLike $WallPostLike
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class WallPostLikesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Paginator',
		'Session',
		'Hashids' => array(
    		'salt' => 'yduyciucuycuvytvjhvdtrsescl', // Required
    		'min_hash_length' => 10
    		)
		);

	public $api = 'wall_post_like';
 	public $apiAction;

	public $apiEndPoints = array('toggle_like');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('toggle_like');

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

	public function toggle_like() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));    
		// $this->request->data = array('{"api":"wall_post_like","action":"toggle_like",
		// 															"data":{"wall_post_id":"53","user_id":"18"},
		// 															"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'toggle_like';
  		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
  		if ($result['validation_result']) {
  			$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
  			$wallPostId = $this->ZooterRequest->getRequestParam($result['request_data'], 'wall_post_id');
  			// $wallPostId = HashidsComponent::decrypt($wallPostId)[0];
  			$this->apiResponse = $this->WallPostLike->toggleLike($userId, $wallPostId);
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
		$this->WallPostLike->recursive = 0;
		$this->set('wallPostLikes', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->WallPostLike->exists($id)) {
			throw new NotFoundException(__('Invalid wall post like'));
		}
		$options = array('conditions' => array('WallPostLike.' . $this->WallPostLike->primaryKey => $id));
		$this->set('wallPostLike', $this->WallPostLike->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->WallPostLike->create();
			if ($this->WallPostLike->save($this->request->data)) {
				$this->Session->setFlash(__('The wall post like has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The wall post like could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->WallPostLike->User->find('list');
		$wallPosts = $this->WallPostLike->WallPost->find('list');
		$this->set(compact('users', 'wallPosts'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->WallPostLike->exists($id)) {
			throw new NotFoundException(__('Invalid wall post like'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->WallPostLike->save($this->request->data)) {
				$this->Session->setFlash(__('The wall post like has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The wall post like could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('WallPostLike.' . $this->WallPostLike->primaryKey => $id));
			$this->request->data = $this->WallPostLike->find('first', $options);
		}
		$users = $this->WallPostLike->User->find('list');
		$wallPosts = $this->WallPostLike->WallPost->find('list');
		$this->set(compact('users', 'wallPosts'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->WallPostLike->id = $id;
		if (!$this->WallPostLike->exists()) {
			throw new NotFoundException(__('Invalid wall post like'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->WallPostLike->delete()) {
			$this->Session->setFlash(__('The wall post like has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The wall post like could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
