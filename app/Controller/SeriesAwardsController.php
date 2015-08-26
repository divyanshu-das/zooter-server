<?php
App::uses('AppController', 'Controller');
/**
 * SeriesAwards Controller
 *
 * @property SeriesAward $SeriesAward
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class SeriesAwardsController extends AppController {

 /* Components
 *
 * @var array
 */
  public $components = array('Paginator', 'Session');
  public $api = 'series_award';
  public $apiAction;
  public $apiEndPoints = array('show','delete','update');

  /**
   * beforeFilter callback
   *
   * @return void
   */
  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('show','delete','update');
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

  public function show(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "series_award","action": "show","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'show';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->SeriesAward->showSeriesAward($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }


  public function delete(){
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('{"api": "series_award","action": "delete","appGuid": "aonecdad-345hldd-nuhoacfl","data": {"id": 1}}');
    $this->apiAction = 'delete';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $this->apiResponse = $this->SeriesAward->deleteSeriesAward($id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function update() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));

    $this->request->data = array('
      {
          "api": "series_award",
          "action": "update",
          "appGuid": "aonecdad-345hldd-nuhoacfl",
          "data": {
              "series_id": "1",
              "series_awards": [
                  {
                      "id": 1 ,
                      "award_name": "Player OF The Series 32",
                      "value": 200,
                      "user_id": ""
                  },
                  {
                      "id": 2,
                      "award_name": "Player OF The Series 6",
                      "value": 200,
                      "user_id": 2
                  },
                  {
                      "id":72,
                      "award_name": "Player OF The Series8.1",
                      "value": 200,
                      "user_id": ""
                  }
              ]
          }
      }');

    $this->apiAction = 'update';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);  
    if ($result['validation_result']) { 
      $seriesId = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_id');
      if (! $this->ZooterRequest->validateUpdateParams($result['request_data']['data'], 'series_id', $seriesId)) {
        $result['api_return_code'] = 400;
        $result['message'] = 'Invalid series_id found during update';
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
      } else {
        $seriesAwards = $this->ZooterRequest->getRequestParam($result['request_data'], 'series_awards');
        $this->apiResponse = $this->SeriesAward->updateSeriesAwards($seriesId, $seriesAwards);
     }
    } 
    else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->SeriesAward->recursive = 0;
		$this->set('seriesAwards', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->SeriesAward->exists($id)) {
			throw new NotFoundException(__('Invalid series award'));
		}
		$options = array('conditions' => array('SeriesAward.' . $this->SeriesAward->primaryKey => $id));
		$this->set('seriesAward', $this->SeriesAward->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->SeriesAward->create();
			if ($this->SeriesAward->save($this->request->data)) {
				$this->Session->setFlash(__('The series award has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The series award could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$series = $this->SeriesAward->Series->find('list');
		$users = $this->SeriesAward->User->find('list');
		$awardTypes = $this->SeriesAward->AwardType->find('list');
		$this->set(compact('series', 'users', 'awardTypes'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->SeriesAward->exists($id)) {
			throw new NotFoundException(__('Invalid series award'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SeriesAward->save($this->request->data)) {
				$this->Session->setFlash(__('The series award has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The series award could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('SeriesAward.' . $this->SeriesAward->primaryKey => $id));
			$this->request->data = $this->SeriesAward->find('first', $options);
		}
		$series = $this->SeriesAward->Series->find('list');
		$users = $this->SeriesAward->User->find('list');
		$awardTypes = $this->SeriesAward->AwardType->find('list');
		$this->set(compact('series', 'users', 'awardTypes'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->SeriesAward->id = $id;
		if (!$this->SeriesAward->exists()) {
			throw new NotFoundException(__('Invalid series award'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->SeriesAward->delete()) {
			$this->Session->setFlash(__('The series award has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The series award could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
