<?php
App::uses('AppController', 'Controller');
App::uses('PlaceType', 'Lib/Enum');
App::uses('PlaceTypePlural', 'Lib/Enum');
/**
 * Places Controller
 *
 * @property Place $Place
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PlacesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public $api = 'place';
  public $apiAction;

	public $apiEndPoints = array('create', 'search', 'view', 'view_place', 'add_to_favorite', 'remove_from_favorite', 'get_place_details', 'edit', 'list_places', 'add_review', 'add_rating');

	/**
	 * beforeFilter callback
	 *
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('search','create', 'view', 'view_place', 'add_to_favorite', 'remove_from_favorite', 'get_place_details', 'edit', 'list_places', 'add_review', 'add_rating');

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


	public function create() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "place","action": "create","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"user_id":25, "name" : "Karnataka state association", "description": "Worlds best academy", "user_id" : null, "location":{"name" : "Goa, India", "latitude" : "15.2993265", "longitude" : "74.12399600000003", "unique_identifier":"ChIJQbc2YxC6vzsRkkDzYv-H-Oo"}, "type" : "Academy", "phone" : "9960474942", "alt_phone" : "959199098", "email" : "office@goacricket.org", "min_age" :"13", "max_age" : "15"}}');
		$this->apiAction = 'create';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$locationData = $this->ZooterRequest->getRequestParam($result['request_data'], 'location');
			$name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
			$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
			$desc = $this->ZooterRequest->getRequestParam($result['request_data'], 'description');
			$type = $this->ZooterRequest->getRequestParam($result['request_data'], 'type');
			$contact_person = $this->ZooterRequest->getRequestParam($result['request_data'], 'contact_person');
			$phone = $this->ZooterRequest->getRequestParam($result['request_data'], 'phone');
			$alt_phone = $this->ZooterRequest->getRequestParam($result['request_data'], 'alt_phone');
			$email = $this->ZooterRequest->getRequestParam($result['request_data'], 'email');
			$min_age = $this->ZooterRequest->getRequestParam($result['request_data'], 'min_age');
			$max_age = $this->ZooterRequest->getRequestParam($result['request_data'], 'max_age');

			$images = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'images'), true);

			$nearest_bus_stop = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_bus_stop'), true);
			$nearest_train_station = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_train_station'), true);
			$nearest_airport = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_airport'), true);
			$nearest_hospital = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_hospital'), true);
			$nearest_atm = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_atm'), true);

			$ground_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'ground_id');
			$new_ground_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'new_ground_name');
			$new_ground_location = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'new_ground_location'), true);

			$facilities = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'facilities'), true);
			$bowling_machine = $this->ZooterRequest->getRequestParam($result['request_data'], 'bowling_machine');
			$coach_student_ratio = $this->ZooterRequest->getRequestParam($result['request_data'], 'coach_student_ratio');
			$nets = $this->ZooterRequest->getRequestParam($result['request_data'], 'nets');
			$not_to_miss = $this->ZooterRequest->getRequestParam($result['request_data'], 'not_to_miss');
			$special_offer = $this->ZooterRequest->getRequestParam($result['request_data'], 'special_offer');
			$accomodation = $this->ZooterRequest->getRequestParam($result['request_data'], 'accomodation');
			$transport = $this->ZooterRequest->getRequestParam($result['request_data'], 'transport');
			$coach_count = $this->ZooterRequest->getRequestParam($result['request_data'], 'coach_count');
			$titles_won = $this->ZooterRequest->getRequestParam($result['request_data'], 'titles_won');
			$cuisine = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'cuisine'), true);
			$available_for = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'available_for'), true);
			$pitch_type = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'pitch_type'), true);

			$yearly_charge = $this->ZooterRequest->getRequestParam($result['request_data'], 'yearly_charge');
			$monthly_charge = $this->ZooterRequest->getRequestParam($result['request_data'], 'monthly_charge');
			$cost_for_two = $this->ZooterRequest->getRequestParam($result['request_data'], 'cost_for_two');
			$cost_per_day = $this->ZooterRequest->getRequestParam($result['request_data'], 'cost_per_day');

			$working_days = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'working_days'), true);
			$weekday_start_time = $this->ZooterRequest->getRequestParam($result['request_data'], 'weekday_start_time');
			$weekday_end_time = $this->ZooterRequest->getRequestParam($result['request_data'], 'weekday_end_time');
			$weekend_start_time = $this->ZooterRequest->getRequestParam($result['request_data'], 'weekend_start_time');
			$weekend_end_time = $this->ZooterRequest->getRequestParam($result['request_data'], 'weekend_end_time');

			$facilitiesData = array(
				'bowling_machine_count' => $bowling_machine,
				'coach_student_ratio' => $coach_student_ratio,
				'accomodation' => $accomodation,
				'transport' => $transport,
				'nets' => $nets,
				'cuisine' => $cuisine,
				'available_for' => $available_for,
				'pitch_type' => $pitch_type,
				'not_to_miss' => $not_to_miss,
				'special_offer' => $special_offer,
				'coach_count' => $coach_count,
				'titles_won' => $titles_won,
				'ground_id' => $ground_id,
				'other_facilities' => $facilities
			);
			$locationData = json_decode($locationData, true);
			$commute = array(
				'nearest_bus_stop' => $nearest_bus_stop,
				'nearest_train_station' => $nearest_train_station,
				'nearest_airport' => $nearest_airport,
				'nearest_hospital' => $nearest_hospital,
				'nearest_atm' => $nearest_atm
			);
			$costData = array(
				'monthly' => $monthly_charge,
				'yearly' => $yearly_charge,
				'cost for two' => $cost_for_two,
				'cost per day' => $cost_per_day
			);

			$timeData = array(
				'working_days' => $working_days,
				'weekday_start_time' => $weekday_start_time,
				'weekday_end_time' => $weekday_end_time,
				'weekend_start_time' => $weekend_start_time,
				'weekend_end_time' => $weekend_end_time
			);
			$newGroundInfo = array();
			if ($type != 'ground') {
				$newGroundInfo = array(
					'ground_name' => $new_ground_name,
					'ground_location' => $new_ground_location,
					'contact_name' => null,
					'contact_number' => null
				);
			} else if ($type == 'ground') {
				$newGroundInfo = array(
					'ground_name' => $name,
					'ground_location' => $locationData,
					'contact_name' => $contact_person,
					'contact_number' => $phone
				);
			}

		  $this->apiResponse = $this->Place->createPlace($locationData, $name, $userId, $desc, $type, $contact_person, $phone, $alt_phone, $email, $min_age, $max_age, $commute, $facilitiesData, $costData, $timeData, $images, $newGroundInfo);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function edit() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "place","action": "edit","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"id" : 28, "user_id":25, "name" : "Karnataka state association", "description": "Worlds best academy", "user_id" : null, "location":{"name" : "Goa, India", "latitude" : "15.2993265", "longitude" : "74.12399600000003", "unique_identifier":"ChIJQbc2YxC6vzsRkkDzYv-H-Oo"}, "type" : "Academy", "phone" : "9960474942", "alt_phone" : "959199098", "email" : "office@goacricket.org", "min_age" :"13", "max_age" : "15"}}');
		$this->apiAction = 'edit';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$locationData = $this->ZooterRequest->getRequestParam($result['request_data'], 'location');
			$id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
			$name = $this->ZooterRequest->getRequestParam($result['request_data'], 'name');
			$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
			$desc = $this->ZooterRequest->getRequestParam($result['request_data'], 'description');
			$type = $this->ZooterRequest->getRequestParam($result['request_data'], 'type');
			$contact_person = $this->ZooterRequest->getRequestParam($result['request_data'], 'contact_person');
			$phone = $this->ZooterRequest->getRequestParam($result['request_data'], 'phone');
			$alt_phone = $this->ZooterRequest->getRequestParam($result['request_data'], 'alt_phone');
			$email = $this->ZooterRequest->getRequestParam($result['request_data'], 'email');
			$min_age = $this->ZooterRequest->getRequestParam($result['request_data'], 'min_age');
			$max_age = $this->ZooterRequest->getRequestParam($result['request_data'], 'max_age');

			$images = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'images'), true);

			$nearest_bus_stop = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_bus_stop'), true);
			$nearest_train_station = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_train_station'), true);
			$nearest_airport = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_airport'), true);
			$nearest_hospital = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_hospital'), true);
			$nearest_atm = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'nearest_atm'), true);

			$ground_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'ground_id');
			$new_ground_name = $this->ZooterRequest->getRequestParam($result['request_data'], 'new_ground_name');
			$new_ground_location = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'new_ground_location'), true);

			$facilities = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'facilities'), true);
			$bowling_machine = $this->ZooterRequest->getRequestParam($result['request_data'], 'bowling_machine');
			$coach_student_ratio = $this->ZooterRequest->getRequestParam($result['request_data'], 'coach_student_ratio');
			$nets = $this->ZooterRequest->getRequestParam($result['request_data'], 'nets');
			$not_to_miss = $this->ZooterRequest->getRequestParam($result['request_data'], 'not_to_miss');
			$special_offer = $this->ZooterRequest->getRequestParam($result['request_data'], 'special_offer');
			$accomodation = $this->ZooterRequest->getRequestParam($result['request_data'], 'accomodation');
			$transport = $this->ZooterRequest->getRequestParam($result['request_data'], 'transport');
			$coach_count = $this->ZooterRequest->getRequestParam($result['request_data'], 'coach_count');
			$titles_won = $this->ZooterRequest->getRequestParam($result['request_data'], 'titles_won');
			$cuisine = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'cuisine'), true);
			$available_for = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'available_for'), true);
			$pitch_type = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'pitch_type'), true);

			$yearly_charge = $this->ZooterRequest->getRequestParam($result['request_data'], 'yearly_charge');
			$monthly_charge = $this->ZooterRequest->getRequestParam($result['request_data'], 'monthly_charge');
			$cost_for_two = $this->ZooterRequest->getRequestParam($result['request_data'], 'cost_for_two');
			$cost_per_day = $this->ZooterRequest->getRequestParam($result['request_data'], 'cost_per_day');

			$working_days = json_decode($this->ZooterRequest->getRequestParam($result['request_data'], 'working_days'), true);
			$weekday_start_time = $this->ZooterRequest->getRequestParam($result['request_data'], 'weekday_start_time');
			$weekday_end_time = $this->ZooterRequest->getRequestParam($result['request_data'], 'weekday_end_time');
			$weekend_start_time = $this->ZooterRequest->getRequestParam($result['request_data'], 'weekend_start_time');
			$weekend_end_time = $this->ZooterRequest->getRequestParam($result['request_data'], 'weekend_end_time');

			$facilitiesData = array(
				'bowling_machine_count' => $bowling_machine,
				'coach_student_ratio' => $coach_student_ratio,
				'accomodation' => $accomodation,
				'transport' => $transport,
				'nets' => $nets,
				'cuisine' => $cuisine,
				'available_for' => $available_for,
				'pitch_type' => $pitch_type,
				'not_to_miss' => $not_to_miss,
				'special_offer' => $special_offer,
				'coach_count' => $coach_count,
				'titles_won' => $titles_won,
				'ground_id' => $ground_id,
				'other_facilities' => $facilities
			);
			$locationData = json_decode($locationData, true);
			$commute = array(
				'nearest_bus_stop' => $nearest_bus_stop,
				'nearest_train_station' => $nearest_train_station,
				'nearest_airport' => $nearest_airport,
				'nearest_hospital' => $nearest_hospital,
				'nearest_atm' => $nearest_atm
			);
			$costData = array(
				'monthly' => $monthly_charge,
				'yearly' => $yearly_charge,
				'cost for two' => $cost_for_two,
				'cost per day' => $cost_per_day
			);

			$timeData = array(
				'working_days' => $working_days,
				'weekday_start_time' => $weekday_start_time,
				'weekday_end_time' => $weekday_end_time,
				'weekend_start_time' => $weekend_start_time,
				'weekend_end_time' => $weekend_end_time
			);
			$newGroundInfo = array();
			if ($type != 'ground') {
				$newGroundInfo = array(
					'ground_name' => $new_ground_name,
					'ground_location' => $new_ground_location,
					'contact_name' => null,
					'contact_number' => null
				);
			} else if ($type == 'ground') {
				$newGroundInfo = array(
					'ground_name' => $name,
					'ground_location' => $locationData,
					'contact_name' => $contact_person,
					'contact_number' => $phone
				);
			}

		  $this->apiResponse = $this->Place->editPlace($id, $locationData, $name, $userId, $desc, $type, $contact_person, $phone, $alt_phone, $email, $min_age, $max_age, $commute, $facilitiesData, $costData, $timeData, $images, $newGroundInfo);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function search() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "place","action": "search","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"user_id": 25, "location":{"name":"Goa, India", "latitude":"15.2993265", "longitude":"74.12399600000003", "unique_identifier":"ChIJQbc2YxC6vzsRkkDzYv-H-Oo"}, "place_type":"All", "limit" : 20, "page" : 1, "distance" : 50}}');
		$this->apiAction = 'search';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$locationData = $this->ZooterRequest->getRequestParam($result['request_data'], 'location');
			$placeType = $this->ZooterRequest->getRequestParam($result['request_data'], 'place_type');
			$distance = $this->ZooterRequest->getRequestParam($result['request_data'], 'distance');
			$limit = $this->ZooterRequest->getRequestParam($result['request_data'], 'limit');
			$page = $this->ZooterRequest->getRequestParam($result['request_data'], 'page');
			$userId = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');

			$locationsIds = $this->Place->getLocationIds($locationData, $distance);
			$places = array();
			$paginationParams = array();
			if (!empty($locationsIds)) {
				if ($placeType == 'All') {
					$conditions = array(
						'Place.location_id' => $locationsIds,
						'Place.is_verified' => true
					);
				} else {
					$conditions = array(
						'Place.location_id' => $locationsIds,
						'Place.type' => PlaceTypePlural::intValue($placeType),
						'Place.is_verified' => true
					);
				}
				$this->paginate = array(
					'conditions' => $conditions,
					'contain' => array(
						'Location',
						'FavoritePlace',
						'PlacesImage' => array(
							'limit' => 1,
							'Image'
						)
					),
					'limit' => $limit,
					'page' => $page
				);
				$places = $this->paginate('Place');
				$paginationParams = $this->request->params['paging'];
			}
		  $this->apiResponse = $this->Place->prepareSearchResponse($locationData, $placeType, $places, $paginationParams, $userId);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function view() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "place","action": "view","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"id" : 3}}');
		$this->apiAction = 'view';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
		  $this->apiResponse = $this->Place->viewPlace($id);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function view_place() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "place","action": "view_place","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"id" : 3}}');
		$this->apiAction = 'view';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$id = $this->ZooterRequest->getRequestParam($result['request_data'], 'id');
		  $this->apiResponse = $this->Place->viewFullPlace($id);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function get_place_details() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "place","action": "get_place_details","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"user_id" : 3, "place_id" : 4}}');
		$this->apiAction = 'get_place_details';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$place_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'place_id');
			$user_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
		  $this->apiResponse = $this->Place->getPlaceDetails($place_id, $user_id);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function add_to_favorite() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "place","action": "add_to_favorite","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"place_id" : 3, "user_id" : 7}}');
		$this->apiAction = 'add_to_favorite';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$place_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'place_id');
			$user_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
		  $this->apiResponse = $this->Place->addToFavorite($place_id, $user_id);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function remove_from_favorite() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "place","action": "remove_from_favorite","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"place_id" : 3, "user_id" : 7}}');
		$this->apiAction = 'remove_from_favorite';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$place_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'place_id');
			$user_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
		  $this->apiResponse = $this->Place->removeFromFavorite($place_id, $user_id);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function list_places() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api": "place","action": "list_places","appGuid": "aonecdad-345hldd-nuhoacfl","data":{"user_id" : 7}}');
		$this->apiAction = 'list_places';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$user_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
		  $this->apiResponse = $this->Place->getPlacesList($user_id);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function add_review() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api":"place","action":"add_review","appGuid":"aonecdad-345hldd-nuhoacfl","data":{"place_id": "1", "rating":"6","review":"My first Review for this place....\nIts an awesome Place.\nI am so happy that i chose this place to learn my first cricket lessons.","image":"{\"name\":\"IMG_20150422_111128.jpg\",\"size\":2863475,\"type\":\"image/jpeg\",\"caption\":\"\",\"url\":\"https://zooterupload.s3.amazonaws.com/143904999255c62907e4099.jpg\",\"progress\":100}","user_id":"25"}}');
		$this->apiAction = 'add_review';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$place_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'place_id');
			$user_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
			$review = $this->ZooterRequest->getRequestParam($result['request_data'], 'review');
			$rating = $this->ZooterRequest->getRequestParam($result['request_data'], 'rating');
			$image = $this->ZooterRequest->getRequestParam($result['request_data'], 'image');
			if (!empty($image)) {
				$image = json_decode($image, true);
			}
		  $this->apiResponse = $this->Place->PlaceReview->addReview($place_id, $user_id, $rating, $review, $image);
		} else {
			$this->apiResponse = $this->ZooterResponse->getErrorResponse($result);
		}
	}

	public function add_rating() {
		$data = file_get_contents('php://input');
    $this->request->data = json_decode($data, false);
    $this->request->data = array(json_encode($this->request->data));
		// $this->request->data = array('{"api":"place","action":"add_rating","appGuid":"aonecdad-345hldd-nuhoacfl","data":{"rating":"9","place_id":"1","user_id":"25"}}');
		$this->apiAction = 'add_rating';
		$result = $this->ZooterRequest->validateRequestData($this->request, $this->api, $this->apiAction);
		if ($result['validation_result']) {
			$place_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'place_id');
			$user_id = $this->ZooterRequest->getRequestParam($result['request_data'], 'user_id');
			$rating = $this->ZooterRequest->getRequestParam($result['request_data'], 'rating');
		  $this->apiResponse = $this->Place->PlaceRating->addRating($place_id, $user_id, $rating);
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
		$this->Place->recursive = 0;
		$this->set('places', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Place->exists($id)) {
			throw new NotFoundException(__('Invalid place'));
		}
		$options = array(
			'conditions' => array(
				'Place.' . $this->Place->primaryKey => $id
			),
			'contain' => array(
				'User',
				'Location'
			)
		);
		$this->set('place', $this->Place->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Place->create();
			if ($this->Place->save($this->request->data)) {
				$this->Session->setFlash(__('The place has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->Place->User->find('list');
		$locations = $this->Place->Location->find('list');
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
		if (!$this->Place->exists($id)) {
			throw new NotFoundException(__('Invalid place'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Place->save($this->request->data)) {
				$this->Session->setFlash(__('The place has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The place could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Place.' . $this->Place->primaryKey => $id));
			$this->request->data = $this->Place->find('first', $options);
		}
		$users = $this->Place->User->find('list');
		$locations = $this->Place->Location->find('list');
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
		$this->Place->id = $id;
		if (!$this->Place->exists()) {
			throw new NotFoundException(__('Invalid place'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Place->delete()) {
			$this->Session->setFlash(__('The place has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The place could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
