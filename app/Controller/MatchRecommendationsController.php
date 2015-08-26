<?php
App::uses('AppController', 'Controller');
/**
 * MatchRecommendations Controller
 *
 * @property MatchRecommendation $MatchRecommendation
 * @property PaginatorComponent $Paginator
 */
class MatchRecommendationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'match_recommendation';
  public $apiAction;

	public $apiEndPoints = array('get_users_to_recommend','add_users_to_recommend','search_users_to_recommend',
                                'get_recommended_matches');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('get_users_to_recommend','add_users_to_recommend','search_users_to_recommend',
                        'get_recommended_matches');

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

	public function get_users_to_recommend() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_recommendation","action":"get_users_to_recommend","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","match_id":"10","num_of_records":"7"
    //                                 }}');
    $this->apiAction = 'get_users_to_recommend';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $numOfRecords = $this->ZooterRequest->getRequestParam($result['request_data'], 'num_of_records');
      $this->apiResponse = $this->MatchRecommendation->getUsersForMatchRecommendation($userId,$matchId,$numOfRecords);
    } else {
      	$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

	public function add_users_to_recommend() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_recommendation","action":"add_users_to_recommend","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id": 6,"match_id": 4,
    //                                   "recommended_to":[{"id": 14},{"id": 13}]
    //                                 }}');
    $this->apiAction = 'add_users_to_recommend';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $recommendedTo = $this->ZooterRequest->getRequestParam($result['request_data'], 'recommended_to');
      $this->apiResponse = $this->MatchRecommendation->addUsersForMatchRecommendation($userId,$matchId,$recommendedTo);
    } else {
      	$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

  public function search_users_to_recommend() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_recommendation","action":"search_users_to_recommend","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","match_id":"10","input":"nir","num_of_records":"7"
    //                                 }}');
    $this->apiAction = 'search_users_to_recommend';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $matchId = $this->ZooterRequest->getRequestParam($result['request_data'], 'match_id');
      $input = $this->ZooterRequest->getRequestParam($result['request_data'], 'input');
      $numOfRecords = $this->ZooterRequest->getRequestParam($result['request_data'], 'num_of_records');
      $this->apiResponse = $this->MatchRecommendation->searchUsersForMatchRecommendation($userId,$matchId,$input,$numOfRecords);
    } else {
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function get_recommended_matches() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"match_recommendation","action":"get_recommended_matches","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","num_of_records":"6"
    //                                 }}');
    $this->apiAction = 'get_recommended_matches';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $numOfRecords = $this->ZooterRequest->getRequestParam($result['request_data'], 'num_of_records');
      $this->apiResponse = $this->MatchRecommendation->getRecommendedMatches($userId,$numOfRecords);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

}
