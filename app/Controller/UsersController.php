<?php
App::uses('AppController', 'Controller');
App::uses('UserLevel', 'Lib/Enum');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'user';
  public $apiAction;

	public $apiEndPoints = array('register', 'authenticate', 'forgot_password', 'is_correct_user',
                               'reset_password', 'zoot', 'get_news_feed', 'autocomplete', 'social_login',
                                'twitter_login', 'twitter_user_exists', 'zooter_verification',
                                'prepare_dashboard','follow_match','accept_request','search_box',
                                'prepare_profile_about','prepare_profile_career','prepare_profile_social',
                                'search_friends','update_last_seen_notification_time','filter_social',
                                'update_last_seen_request_time','update_last_seen_message_time',
                                'prepare_profile_activities','search_social','prepare_notification_area',
                                 'view_profile', 'get_user_details', 'edit_profile');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('register', 'authenticate', 'forgot_password', 'is_correct_user','reset_password',
                         'zoot', 'get_news_feed', 'autocomplete', 'social_login', 'twitter_login',
                         'twitter_user_exists', 'zooter_verification','prepare_dashboard','search_box',
                         'follow_match','accept_request','prepare_profile_about','prepare_profile_career',
                         'prepare_profile_social','prepare_profile_activities','search_friends',
                         'update_last_seen_notification_time','update_last_seen_request_time','filter_social',
                         'update_last_seen_message_time','search_social','prepare_notification_area', 'view_profile',
                          'login', 'get_user_details', 'edit_profile');

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

  public function twitter_login() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    $this->apiAction = 'twitter_login';
    // $this->request->data = array('{"api": "user","action": "twitter_login","data": {"first_name": "Divyanshu","last_name": "Das","email": "divyanshu@niiu.in","twitter_oauth_key": "95983127-mC9r0Aim4IxjVUsHA8ZuFfQKAhSdDMcQVOCG8wDCM","twitter_oauth_secret": "iZlA5fNOSUptKesyXIG33EIWblPWFiMCADDksKTaDQLrF", "type_id":1},"appGuid": "aonecdad-345hldd-nuhoacfl"}');
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $email = $this->ZooterRequest->getRequestParam($result['request_data'], 'email');
      $twitter_oauth_key = $this->ZooterRequest->getRequestParam($result['request_data'], 'twitter_oauth_key');
      $twitter_oauth_secret = $this->ZooterRequest->getRequestParam($result['request_data'], 'twitter_oauth_secret');
      $first_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'first_name');
      $last_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'last_name');
      $type_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'type_id');
      $this->apiResponse = $this->User->doTwitterLogin($email, $twitter_oauth_key, $twitter_oauth_secret, $first_name, $last_name, $type_id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function twitter_user_exists() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    $this->apiAction = 'twitter_user_exists';
    // $this->request->data = array('{"api":"user","action":"twitter_user_exists","data":{"twitter_oauth_key":"95983127-mC9r0Aim4IxjVUsHA8ZuFfQKAhSdDMcQVOCG8wDCM","twitter_oauth_secret":"iZlA5fNOSUptKesyXIG33EIWblPWFiMCADDksKTaDQLrF"},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $twitter_oauth_key = $this->ZooterRequest->getRequestParam($result['request_data'], 'twitter_oauth_key');
      $twitter_oauth_secret = $this->ZooterRequest->getRequestParam($result['request_data'], 'twitter_oauth_secret');
      $this->apiResponse = $this->User->twitterUserExists($twitter_oauth_key, $twitter_oauth_secret);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function zooter_verification() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    $this->apiAction = 'zooter_verification';
    // $this->request->data = array('{"api":"user","action":"zooter_verification","data":{"twitter_handle":"DD_ROCK_D_WORLD"},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $twitter_handle = $this->ZooterRequest->getRequestParam($result['request_data'], 'twitter_handle');
      $this->apiResponse = $this->User->ZooterVerification($twitter_handle);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function social_login() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    $this->apiAction = 'social_login';
    // $this->request->data = array('{"api":"user","action":"social_login","data":{"email":"divyanshu.das@gmail.com","first_name":"Divyanshu","last_name":"Das","name":"Divyanshu Das","facebook_id":"10204799093364017","facebook_access_token":"CAAFqpA7iX4MBAEnfDKtAiaP09vdd4L4VzYTMEEhqFtNHwZB4dSMoqgOZBCpmcbGhuleWPQQPojFVid6SHTaVVT5IiZBrXKWLwywYzICZAz7liEf6jjejjXOBVwbtUnUGhbT77A4TZBLhx9fGXU6pZCcz4sUZCyg5gDtvzq5enOO2XAcp6LaXUyyjzcg392sSuw7TPBjVq4slWIZCZAZB1RYmit", "type_id":"2"},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $email = $this->ZooterRequest->getRequestParam($result['request_data'], 'email');
      $facebook_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'facebook_id');
      $facebook_access_token = $this->ZooterRequest->getRequestParam($result['request_data'], 'facebook_access_token');
      $first_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'first_name');
      $last_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'last_name');
      $type_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'type_id');
      $this->apiResponse = $this->User->doSocialLogin($email, $facebook_id, $facebook_access_token, $first_name, $last_name, $type_id);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function register() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"register","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                               "data":{"email":"nir2_zooterone@mailinator.com", "username":"nir_zooter3",
    //                                "password":"zooter3", "type_id":"1", "phone":"6356987892",
    //                                 "firstName":"nir3","lastName":"zooter3", "birthDate":null,"gender":null,
    //                                  "location_name":"xyz", "location_unique_identifier":"jbsdbrtyu",
    //                                   "location_latitude":"15.456789", "location_longitude":"76.345678"}}');
    $this->apiAction = 'register';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $email = $this->ZooterRequest->getRequestParam($result['request_data'], 'email');
      $username = $this->ZooterRequest->getRequestParam($result['request_data'], 'username');
      $password = $this->ZooterRequest->getRequestParam($result['request_data'], 'password');
      $phone = $this->ZooterRequest->getRequestParam($result['request_data'],  'phone');
      $phone = null;
      $typeId = $this->ZooterRequest->getRequestParam($result['request_data'],  'type_id');
      $name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
      $gender = $this->ZooterRequest->getRequestParam($result['request_data'], 'gender');
      $birthDate = $this->ZooterRequest->getRequestParam($result['request_data'], 'birthDate');
      $location_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_name');
      $location_unique_identifier = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_unique_identifier');
      $location_latitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_latitude');
      $location_longitude = $this->ZooterRequest->getRequestParam($result['request_data'], 'location_longitude');
      $this->apiResponse = $this->User->registerUser($email, $username, $password, $phone, $typeId, 
                                                      $name, $gender, $birthDate, 
                                                      $location_name,$location_latitude,$location_longitude,
                                                      $location_unique_identifier);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
	}

   public function authenticate() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
  	// $this->request->data = array('{"api":"user", "action":"authenticate", "appGuid":"aonecdad-345hldd-nuhoacfl",
   //                                 "data" : {"email":"nir2_zooterone@mailinator.com", "username":"nir_zooter3",
   //                                  "password":"zooter3"}}');
  	$this->apiAction = 'authenticate';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
  	if ($result['validation_result']) {
  		$email = $this->ZooterRequest->getRequestParam($result['request_data'], 'email');
  		$username = $this->ZooterRequest->getRequestParam($result['request_data'], 'username');
      $password = $this->ZooterRequest->getRequestParam($result['request_data'], 'password');
      $this->apiResponse = $this->User->authenticateUser($email, $username, $password);
  	} else {
  		$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
  	}
  }

  public function zoot() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
  	// $this->request->data = array('{"api":"user","action":"zoot","apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981",
   //                                  "data":{"user_id":"6",                                   
   //                                  "zoot":"A status match Update zoot",
   //                                  "category":"Match","type":"status_update",
   //                                  "location":{"latitude": 21.6139,"longitude":75.2089,                                      
   //                                              "unique_identifier":"gyyrtropWDe4vzsRQAIREDBgguy",
   //                                              "place":"bhakti palace,NH1,tarapore"},
   //                                  "image":"/img/pic44.png",
   //                                  "video" : {"name":"anniversary74","url":"video/anii139.avi","size":"173"},
   //                                  "posted_on_id":1,"hyperlink":"/newlink/retro/49"},
   //                                  "appGuid":"aonecdad-345hldd-nuhoacfl"}');

  	$this->apiAction = 'zoot';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
  	if ($result['validation_result']) {
  		$user_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
  		$zoot = $this->ZooterRequest->getRequestParam($result['request_data'], 'zoot');  
      $category = $this->ZooterRequest->getRequestParam($result['request_data'], 'category'); 
      $type = $this->ZooterRequest->getRequestParam($result['request_data'], 'type');     
  		$location = $this->ZooterRequest->getRequestParam($result['request_data'], 'location');
  		$imageUrl = $this->ZooterRequest->getRequestParam($result['request_data'], 'image');
      $video = $this->ZooterRequest->getRequestParam($result['request_data'], 'video');
      $hyperLink = $this->ZooterRequest->getRequestParam($result['request_data'], 'hyperlink');
  		$posted_on_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'posted_on_id');
  		$this->apiResponse = $this->User->WallPostSent->saveZoot($category, $type, $user_id, $zoot, $location,$imageUrl,
                                                               $video,$posted_on_id,$hyperLink);
  	} else {
  		$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
  	}
  }

  public function forgot_password() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
  	// $this->request->data = array('{"api":"user", "action":"forgot_password", "appGuid":"aonecdad-345hldd-nuhoacfl", "data" : {"email":"amateurone@mailinator.com"}}');
  	$this->apiAction = 'forgot_password';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$email = $this->ZooterRequest->getRequestParam($result['request_data'], 'email');
			$this->apiResponse = $this->User->forgotPassword($email);
		} else {
  		$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
  }

  public function is_correct_user() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
  	// $this->request->data = array('{"api":"user", "action":"is_correct_user", "appGuid":"aonecdad-345hldd-nuhoacfl", "data" : {"user_id" : "123", "password_reset" : "vfvfdsvert"}}');
  	$this->apiAction = 'is_correct_user';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
  	if ($result['validation_result']) {
  		$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
  		$passwordReset = $this->ZooterRequest->getRequestParam($result['request_data'], 'password_reset');
  		$this->apiResponse = $this->User->isCorrectUser($userId, $passwordReset);
  	} else {
  		$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
  	}
  }

   public function reset_password() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
  	// $this->request->data = array('{"api":"user", "action":"reset_password", "appGuid":"aonecdad-345hldd-nuhoacfl", "data" : {"user_id" : "123", "password" : "testerone"}}');
  	$this->apiAction = 'reset_password';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
  	if ($result['validation_result']) {
  		$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
  		$password = $this->ZooterRequest->getRequestParam($result['request_data'], 'password');
  		$this->apiResponse = $this->User->resetPassword($userId, $password);
  	} else {
  		$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
  	}
  }

  public function get_user_details() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user", "action":"get_user_details", "appGuid":"aonecdad-345hldd-nuhoacfl", "data" : {"user_id" : "25", "profile_id" : "25"}}');
    $this->apiAction = 'get_user_details';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $profileId = $this->ZooterRequest->getRequestParam($result['request_data'], 'profile_id');
      $this->apiResponse = $this->User->getUserDetailsToEdit($userId, $profileId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function edit_profile() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"edit_profile","appGuid":"aonecdad-345hldd-nuhoacfl","data":{"id":"25","name":"Data Collector 1","gender":"1","date_of_birth":"15 January, 1994","phone":"6356987892","description":"Hello, Hola, Hahahah","image":"https://zooterupload.s3.amazonaws.com/143876101155c1c03384d9b.jpg"}}');
    $this->apiAction = 'edit_profile';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
      $name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
      $gender = $this->ZooterRequest->getRequestParam($result['request_data'], 'gender');
      $dateOfBirth = $this->ZooterRequest->getRequestParam($result['request_data'], 'date_of_birth');
      $phone = $this->ZooterRequest->getRequestParam($result['request_data'], 'phone');
      $description = $this->ZooterRequest->getRequestParam($result['request_data'], 'description');
      $image = $this->ZooterRequest->getRequestParam($result['request_data'], 'image');
      $this->apiResponse = $this->User->editProfile($id, $name, $gender, $dateOfBirth, $phone, $description, $image);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function get_news_feed() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
  	// $this->request->data = array('{"api":"user", "action":"get_news_feed", "appGuid":"aonecdad-345hldd-nuhoacfl", "data" : {"user_id" : "5", "apiKey" : "ce4127191419d557ffa44b5453a30617d1f8e981"}}');
  	$this->apiAction = 'get_news_feed';
  	$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
  	if ($result['validation_result']) {
  		$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
  		$this->apiResponse = $this->User->getNewsFeed($userId);
  	} else {
  		$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
  	}
  }

  public function autocomplete() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"autocomplete","data":{"user_id":"5","apiKey":"ce4127191419d557ffa44b5453a30617d1f8e981","searchWord":"a"},"appGuid":"aonecdad-345hldd-nuhoacfl"}');
    $this->apiAction = 'autocomplete';
      $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $searchWord = $this->ZooterRequest->getRequestParam($result['request_data'], 'searchWord');
      $this->apiResponse = $this->User->autocomplete($userId, $searchWord);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function prepare_notification_area() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"prepare_notification_area","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6"
    //                                 }}');
    $this->apiAction = 'prepare_notification_area';
      $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $this->apiResponse = $this->User->prepareNotificationArea($userId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function prepare_dashboard() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"prepare_dashboard","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6"
    //                                 }}');
    $this->apiAction = 'prepare_dashboard';
      $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $this->apiResponse = $this->User->prepareDashboard($userId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function view_profile() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"view_profile","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"12","profile_user_id":"15"
    //                                 }}');
    $this->apiAction = 'view_profile';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $profileUserId = $this->ZooterRequest->getRequestParam($result['request_data'], 'profile_user_id');
      $this->apiResponse = $this->User->getUserProfile($userId,$profileUserId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function prepare_profile_activities() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"prepare_profile_activities","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"12","profile_user_id":"15"
    //                                 }}');
    $this->apiAction = 'prepare_profile_activities';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $profileUserId = $this->ZooterRequest->getRequestParam($result['request_data'], 'profile_user_id');
      $this->apiResponse = $this->User->prepareUserProfile($userId,$profileUserId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function prepare_profile_about() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"prepare_profile_about","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"3","profile_user_id" : "15"
    //                                 }}');
    $this->apiAction = 'prepare_profile_about';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $profileUserId = $this->ZooterRequest->getRequestParam($result['request_data'], 'profile_user_id');
      $this->apiResponse = $this->User->prepareUserProfileAbout($userId,$profileUserId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function prepare_profile_career() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"prepare_profile_career","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"3","profile_user_id" : "3"
    //                                 }}');
    $this->apiAction = 'prepare_profile_career';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $profileUserId = $this->ZooterRequest->getRequestParam($result['request_data'], 'profile_user_id');
      $this->apiResponse = $this->User->prepareUserProfileCareer($userId,$profileUserId);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function prepare_profile_social() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"prepare_profile_social","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"15","profile_user_id" : "12"
    //                                 }}');
    $this->apiAction = 'prepare_profile_social';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $profileUserId = $this->ZooterRequest->getRequestParam($result['request_data'], 'profile_user_id');
      $this->apiResponse = $this->User->prepareUserProfileFriendsAndGroups($userId,$profileUserId);
    } else {
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function search_box() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"search_box","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","search_input":"king","num_of_records":"  "
    //                                 }}');
    $this->apiAction = 'search_box';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $searchInput = $this->ZooterRequest->getRequestParam($result['request_data'], 'search_input');
      $numOfRecords = $this->ZooterRequest->getRequestParam($result['request_data'], 'num_of_records');
      $this->apiResponse = $this->User->searchBox($userId,$searchInput,$numOfRecords);
    } else {
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function search_social() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"search_social","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","search_input":"nir","num_of_records":"  "
    //                                 }}');
    $this->apiAction = 'search_social';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $searchInput = $this->ZooterRequest->getRequestParam($result['request_data'], 'search_input');
      $numOfRecords = $this->ZooterRequest->getRequestParam($result['request_data'], 'num_of_records');
      $this->apiResponse = $this->User->searchSocial($userId,$searchInput,$numOfRecords);
    } else {
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function filter_social() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"filter_social","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","filter":"nir","num_of_records":"  "
    //                                 }}');
    $this->apiAction = 'filter_social';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $filter = $this->ZooterRequest->getRequestParam($result['request_data'], 'filter');
      $numOfRecords = $this->ZooterRequest->getRequestParam($result['request_data'], 'num_of_records');
      $this->apiResponse = $this->User->filterFriendsGroupsFanclubs($userId,$filter,$numOfRecords);
    } else {
        $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function search_friends() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"search_friends","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","input":"nir","num_of_records":"10"
    //                                 }}');
    $this->apiAction = 'search_friends';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $input = $this->ZooterRequest->getRequestParam($result['request_data'], 'input');
      $numOfRecords = $this->ZooterRequest->getRequestParam($result['request_data'], 'num_of_records');
      $this->apiResponse = $this->User->searchUserFriends($userId,$input,$numOfRecords);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function update_last_seen_notification_time() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"update_last_seen_notification_time","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","date":"2015-01-27"
    //                                 }}');
    $this->apiAction = 'update_last_seen_notification_time';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $date = $this->ZooterRequest->getRequestParam($result['request_data'], 'date');
      $this->apiResponse = $this->User->updateLastSeenNotifcationTime($userId,$date);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function update_last_seen_request_time() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"update_last_seen_request_time","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","date":"2015-01-27"
    //                                 }}');
    $this->apiAction = 'update_last_seen_request_time';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $date = $this->ZooterRequest->getRequestParam($result['request_data'], 'date');
      $this->apiResponse = $this->User->updateLastSeenRequestTime($userId,$date);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }

  public function update_last_seen_message_time() {
    $data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
    // $this->request->data = array('{"api":"user","action":"update_last_seen_message_time","appGuid":"aonecdad-345hldd-nuhoacfl",
    //                                 "data":{
    //                                   "user_id":"6","date":"2015-01-27"
    //                                 }}');
    $this->apiAction = 'update_last_seen_message_time';
    $result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
    if ($result['validation_result']) {
      $userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
      $date = $this->ZooterRequest->getRequestParam($result['request_data'], 'date');
      $this->apiResponse = $this->User->updateLastSeenMessageTime($userId,$date);
    } else {
      $this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
    }
  }


  ////////////////////////////////////////////////////////////////////////////////////////
    /////////////// Declare All Admin Panel Functions Below //////////////////////////
  ///////////////////////////////////////////////////////////////////////////////////////

  public function login(){
    // $this->layout = 'auth';
    if ($this->request->is('post') && AuthComponent::user('id') == null) {
      if ($this->Auth->login()) {
        return $this->redirect(array('action' => 'index', 'admin' => true));
      }
      $this->Session->setFlash(__('Invalid username or password, try again'));
    } else if (AuthComponent::user('id')) {
      $this->redirect(array('action' => 'index', 'admin' => true));
    }
  }

  public function logout() {
    $this->Auth->logout();
    return $this->redirect('/');
  }

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$types = $this->User->Type->find('list');
		$this->set(compact('types'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$types = $this->User->Type->find('list');
		$this->set(compact('types'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
