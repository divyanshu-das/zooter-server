<?php
App::uses('AppController', 'Controller');
/**
 * WallPostComments Controller
 *
 * @property WallPostComment $WallPostComment
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class WallPostCommentsController extends AppController {

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

	public $api = 'wall_post_comment';
 	public $apiAction;

	public $apiEndPoints = array('add');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add');

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
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"wall_post_comment","action":"add",
    // 													"data":{"wall_post_id":"53","user_id":"14","comment":"testing self notify"},
    // 													"appGuid":"aonecdad-345hldd-nuhoacfl"}');
		$this->apiAction = 'add';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
  	if ($result['validation_result']) {
  		$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
  		$wallPostId = $this->ZooterRequest->getRequestParam($result['request_data'], 'wall_post_id');
  		$comment = $this->ZooterRequest->getRequestParam($result['request_data'], 'comment');
  		// $wallPostId = HashidsComponent::decrypt($wallPostId)[0];
  		$this->apiResponse = $this->WallPostComment->addComment($userId, $wallPostId, $comment);
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
		$this->WallPostComment->recursive = 0;
		$this->set('wallPostComments', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->WallPostComment->exists($id)) {
			throw new NotFoundException(__('Invalid wall post comment'));
		}
		$options = array('conditions' => array('WallPostComment.' . $this->WallPostComment->primaryKey => $id));
		$this->set('wallPostComment', $this->WallPostComment->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->WallPostComment->create();
			if ($this->WallPostComment->save($this->request->data)) {
				$this->Session->setFlash(__('The wall post comment has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The wall post comment could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->WallPostComment->User->find('list');
		$wallPosts = $this->WallPostComment->WallPost->find('list');
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
		if (!$this->WallPostComment->exists($id)) {
			throw new NotFoundException(__('Invalid wall post comment'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->WallPostComment->save($this->request->data)) {
				$this->Session->setFlash(__('The wall post comment has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The wall post comment could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('WallPostComment.' . $this->WallPostComment->primaryKey => $id));
			$this->request->data = $this->WallPostComment->find('first', $options);
		}
		$users = $this->WallPostComment->User->find('list');
		$wallPosts = $this->WallPostComment->WallPost->find('list');
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
		$this->WallPostComment->id = $id;
		if (!$this->WallPostComment->exists()) {
			throw new NotFoundException(__('Invalid wall post comment'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->WallPostComment->delete()) {
			$this->Session->setFlash(__('The wall post comment has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The wall post comment could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
