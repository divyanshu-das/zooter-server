<?php
App::uses('AppModel', 'Model');
App::uses('CakeTime', 'Utility');
App::uses('PlaceType', 'Lib/Enum');
App::uses('PlaceEssentialType', 'Lib/Enum');
App::uses('PlaceCostType', 'Lib/Enum');
App::uses('PlaceFacilityAccomodation', 'Lib/Enum');
App::uses('PlaceFacilityTransport', 'Lib/Enum');
App::uses('PlaceFacilityType', 'Lib/Enum');
App::uses('WorkingDaysType', 'Lib/Enum');
App::uses('CuisineType', 'Lib/Bitmask');
App::uses('PitchType', 'Lib/Bitmask');
App::uses('GroundAvailabilityType', 'Lib/Bitmask');

/**
 * Place Model
 *
 * @property User $User
 * @property Location $Location
 */
class Place extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'location_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasOne = array(
		'PlaceFacility' => array(
			'className' => 'PlaceFacility',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	public $hasMany = array(
		'PlaceCoach' => array(
			'className' => 'PlaceCoach',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PlaceRating' => array(
			'className' => 'PlaceRating',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PlaceReview' => array(
			'className' => 'PlaceReview',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PlacesCost' => array(
			'className' => 'PlacesCost',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PlacesEssential' => array(
			'className' => 'PlacesEssential',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PlacesImage' => array(
			'className' => 'PlacesImage',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FavoritePlace' => array(
			'className' => 'FavoritePlace',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PlaceTiming' => array(
			'className' => 'PlaceTiming',
			'foreignKey' => 'place_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
	
	public function viewFullPlace($id) {
		$placeFaciltiyFields = array();
		$placeData = $this->findByIdAndIsVerified($id, true);
		$response = array();
		if (!empty($placeData)) {
			$fields = $this->getFieldsByPlaceType($placeData['Place']['type']);
			array_push($fields['place'], 'place_review_count');
			array_push($fields['place'], 'place_rating_count');
			$place = $this->find('first', array(
				'conditions' => array(
					'Place.id' => $id
				),
				'fields' => $fields['place'],
				'contain' => array(
					'Location' => array(
						'fields' => $fields['location']
					),
					'PlaceFacility' => array(
						'fields' => $fields['facility'],
						'Ground' => array(
							'fields' => $fields['ground']
						)
					),
					'PlacesCost' => array(
						'fields' => $fields['cost']
					),
					'PlacesImage' => array(
						'Image' => array(
							'fields' => $fields['image']
						)
					),
					'PlacesEssential' => array(
						'fields' => $fields['essentials'],
						'Location' => array(
							'fields' => $fields['location']
						)
					),
					'PlaceTiming' => array(
						'fields' => $fields['timing']
					),
					'PlaceReview' => array(
						'fields' => array(
							'id', 'user_id', 'review', 'created'
						),
						'order' => array('PlaceReview.created DESC'),
						'User' => array(
							'fields' => array(
								'id', 'review_count'
							),
							'Profile' => array(
								'fields' => array('id', 'first_name'),
								'ProfileImage' => array(
									'fields' => array('id', 'url')
								)
							)
						),
						'Image' => array(
							'fields' => array('id', 'url')
						)
					),
					'PlaceRating' => array(
						'fields' => array(
							'id', 'rating', 'ROUND(AVG(rating), 1) as average_rating'
						)
					)
				)
			));
			$data = $this->__prepareFullViewPlaceData($place);
			$response = array('status' => 200, 'message' => 'success', 'data' => $data);
		} else {
			$response = array('status' => 404, 'message' => 'Place Not found');	
		}
		return $response;
	}

	private function __prepareFullViewPlaceData($place) {
		$data = array();
		$data['id'] = $place['Place']['id'];
		$data['header'] = array();
		$data['sidebar_info'] = array();
		$data['home'] = array();
		$data['reviews'] = array();
		if (!empty($place['PlacesImage'])) {
			foreach ($place['PlacesImage'] as $imageIndex => $image) {
				$data['home']['pics'][$imageIndex]['id'] = $image['Image']['id'];
				$data['home']['pics'][$imageIndex]['caption'] = $image['Image']['caption'];
				$data['home']['pics'][$imageIndex]['url'] = $image['Image']['url'];
			}
		}
		$data['home']['description'] = $place['Place']['description'];
		$data['home']['facilities'] = $this->prepareFormattedFacilityData($place);

		if (!empty($place['PlaceReview'])) {
			foreach ($place['PlaceReview'] as $reviewIndex => $review) {
				$data['reviews'][$reviewIndex]['id'] = $review['id'];
				$data['reviews'][$reviewIndex]['time'] = CakeTime::timeAgoInWords($review['created']);
				$data['reviews'][$reviewIndex]['text'] = $review['review'];
				$data['reviews'][$reviewIndex]['image'] = !empty($review['Image']['url']) ? $review['Image']['url'] : null;
				$data['reviews'][$reviewIndex]['user']['id'] = $review['User']['id'];
				$data['reviews'][$reviewIndex]['user']['name'] = $review['User']['Profile']['first_name'];
				$data['reviews'][$reviewIndex]['user']['url'] = !empty($review['User']['Profile']['ProfileImage']['url']) ? $review['User']['Profile']['ProfileImage']['url'] : null;
				$data['reviews'][$reviewIndex]['user']['review_count'] = $review['User']['review_count'];
			}
		}
		$data['header']['name'] = $place['Place']['name'];
		$data['header']['image'] = !empty($place['PlacesImage'][0]['Image']['url']) ? $place['PlacesImage'][0]['Image']['url'] : null;
		$data['header']['phone'] = $place['Place']['phone'];
		$data['header']['email'] = $place['Place']['email'];
		$data['header']['rating_count'] = $place['Place']['place_rating_count'];
		$data['header']['review_count'] = $place['Place']['place_review_count'];
		$rating = !empty($place['PlaceRating'][0]['PlaceRating'][0]['average_rating']) ? $place['PlaceRating'][0]['PlaceRating'][0]['average_rating'] : null;
		$data['header']['rating'] = !empty($rating) ? $rating . '/10' : 'Not enough rating';
		$data['header']['location'] = $place['Location'];

		$data['sidebar_info']['basic_details'] = $this->prepareBasicDetails($place);
		$data['sidebar_info']['cost_details'] = array();
		if (!empty($place['PlacesCost'])) {
			foreach ($place['PlacesCost'] as $id => $placeCost) {
				$data['sidebar_info']['cost_details'][$id]['type'] = ucfirst(strtolower(PlaceCostType::stringValue($placeCost['type'])));
				$data['sidebar_info']['cost_details'][$id]['amount'] = $placeCost['amount'];
			}
		}
		if (!empty($place['PlacesEssential'])) {
			foreach ($place['PlacesEssential'] as $essentialIndex => $placesEssential) {
				$data['sidebar_info']['commute'][$essentialIndex]['type'] = strtolower(PlaceEssentialType::stringValue($placesEssential['type']));
				$data['sidebar_info']['commute'][$essentialIndex]['name'] = $placesEssential['Location']['name'];
				$data['sidebar_info']['commute'][$essentialIndex]['distance'] = $placesEssential['distance'] . ' Kms';
			}
		}

		return $data;
	}

	public function prepareFormattedFacilityData($place) {
		$data['facilities'] = array();
		if (!empty($place['PlaceFacility'])) {
			unset($place['PlaceFacility']['id']);
			unset($place['PlaceFacility']['place_id']);
			unset($place['PlaceFacility']['ground_id']);
			if (!empty($place['PlaceFacility']['Ground'])) {
				$place['PlaceFacility']['ground'] = $place['PlaceFacility']['Ground']['name'];
				unset($place['PlaceFacility']['Ground']);
			}
		}
		$place['PlaceFacility'] = array_filter($place['PlaceFacility']);
		$i = 0;
		foreach ($place['PlaceFacility'] as $facilityIndex => $facilitiyValue) {

		}
		foreach ($place['PlaceFacility'] as $facilityIndex => $facilitiyValue) {
			if ($this->isBooleanFacility($facilityIndex)) {
				$data['facilities']['boolean_facilities'][$i]['type'] = $facilityIndex;
				$data['facilities']['boolean_facilities'][$i]['name'] = $this->__getFacilityNameFromType($facilityIndex);
				$data['facilities']['boolean_facilities'][$i]['value'] = $facilitiyValue;
			} else {
				$data['facilities']['other_facilities'][$i]['type'] = $facilityIndex;
				$data['facilities']['other_facilities'][$i]['name'] = $this->__getFacilityNameFromType($facilityIndex);
				$data['facilities']['other_facilities'][$i]['value'] = $facilitiyValue;
			}
			$i++;
		}
		$data['facilities']['boolean_facilities'] = $this->__formatPlaceFacilities($data['facilities']['boolean_facilities']);
		$data['facilities']['other_facilities'] = $this->__formatPlaceFacilities($data['facilities']['other_facilities']);
		return $data['facilities'];
	}

	public function isBooleanFacility($facilityIndex) {
		$booleanFacilities = array('has_cricket_corner', 'has_home_delivery', 'has_dine_in', 'serves_non_veg', 'serves_alcohol', 'has_ac', 'has_smoking_area', 'has_floodlights', 'has_toilets', 'has_medical_facilities', 'has_gym', 'has_food', 'has_karyoke', 'has_wifi', 'accept_credit_card', 'has_individual_classes');
		return (in_array($facilityIndex, $booleanFacilities));
	}

	public function prepareBasicDetails($place) {
		$weekdayTiming = array();
		$weekendTiming = array();
		$workingDays = array();
		$count = 0;
		foreach ($place['PlaceTiming'] as $id => $placeTiming) {
			$workingDays[] = $placeTiming['day_of_week'];
		}
		foreach ($place['PlaceTiming'] as $id => $placeTiming) {
			if (in_array($placeTiming['day_of_week'], array(WorkingDaysType::MONDAY, WorkingDaysType::TUESDAY, WorkingDaysType::WEDNESDAY, WorkingDaysType::THURSDAY, WorkingDaysType::FRIDAY))) {
				$weekdayTiming[$count] = date('H:i A',strtotime($placeTiming['time_open']));
				$weekdayTiming[$count+1] = $this->__getEndTime($placeTiming['time_open'], $placeTiming['working_time']);
				$count = 0;
				break;
			}
		}
		foreach ($place['PlaceTiming'] as $id => $placeTiming) {
			if (in_array($placeTiming['day_of_week'], array(WorkingDaysType::SATURDAY, WorkingDaysType::SUNDAY))) {
				$weekendTiming[$count] = date('H:i A',strtotime($placeTiming['time_open']));
				$weekendTiming[$count+1] = $this->__getEndTime($placeTiming['time_open'], $placeTiming['working_time']);
				$count = 0;
				break;
			}
		}
		$weekdayTimeJoin[] = !empty($weekdayTiming) ? 'Weekday : ' . join('-', $weekdayTiming) : null;
		$weekdayTimeJoin[] = !empty($weekendTiming) ? 'Weekend : ' . join('-', $weekendTiming) : null;
		$weekdayTimeJoin = array_filter($weekdayTimeJoin);
		$timingString = join(', ', $weekdayTimeJoin);
		$ageGroupString = '';
		if (!empty($place['Place']['min_age']) && !empty($place['Place']['max_age'])) {
			$ageGroupString = $place['Place']['min_age'] . ' - ' . $place['Place']['max_age'] . ' ' . 'Years';
		} else if (!empty($place['Place']['min_age']) && empty($place['Place']['max_age'])) {
			$ageGroupString = '>=' . $place['Place']['min_age'] . ' ' . 'Years';
		} else if (empty($place['Place']['min_age']) && !empty($place['Place']['max_age'])) {
			$ageGroupString = '<=' . $place['Place']['max_age'] . ' ' . 'Years';
		}

		$data = array(
			'name' => $place['Place']['name'],
			'location' => $place['Location']['name'],
			'phone' => $place['Place']['phone'],
			'email' => !empty($place['Place']['email']) ? $place['Place']['email'] : null,
			'age_group' => $ageGroupString,
			'timing' => $timingString,
			'working_days' => $workingDays
		);
		return $data;
	}

	public function viewPlace($id) {
		$placeFaciltiyFields = array();
		$placeData = $this->findByIdAndIsVerified($id, true);
		$response = array();
		if (!empty($placeData)) {
			$fields = $this->getFieldsByPlaceType($placeData['Place']['type']);
			$place = $this->find('first', array(
				'conditions' => array(
					'Place.id' => $id
				),
				'fields' => $fields['place'],
				'contain' => array(
					'Location' => array(
						'fields' => $fields['location']
					),
					'PlaceFacility' => array(
						'fields' => $fields['facility'],
						'Ground' => array(
							'fields' => $fields['ground']
						)
					),
					'PlacesCost' => array(
						'fields' => $fields['cost']
					),
					'PlacesImage' => array(
						'Image' => array(
							'fields' => $fields['image']
						)
					),
					'PlacesEssential' => array(
						'fields' => $fields['essentials'],
						'Location' => array(
							'fields' => $fields['location']
						)
					),
					'PlaceTiming' => array(
						'fields' => $fields['timing']
					)
				)
			));
			$data = $this->__prepareViewPlaceData($place);
			$response = array('status' => 200, 'message' => 'success', 'data' => $data);
		} else {
			$response = array('status' => 404, 'message' => 'Place Not found');	
		}
		return $response;
	}

	public function getFieldsByPlaceType($type) {
		$fields = array();
		$fields['location'] = array('id', 'name', 'unique_identifier', 'latitude', 'longitude');
		$fields['image'] = array('id', 'caption', 'url');
		$fields['essentials'] = array('id', 'location_id', 'type', 'distance');
		$fields['timing'] = array('id', 'day_of_week', 'time_open', 'working_time');
		$fields['cost'] = array('id', 'type', 'amount');
		$fields['ground'] = array();
		if ($type == PlaceType::ACADEMY) {
			$fields['place'] = array('id', 'name', 'phone', 'alt_phone', 'email', 'min_age', 'max_age', 'description', 'type');
			$fields['facility'] = array('id', 'has_individual_classes', 'has_medical_facilities', 'has_gym', 'has_food', 'bowling_machine_count', 'coach_student_ratio', 'nets', 'transport', 'accomodation', 'special_offer');
			$fields['ground'] = array('id', 'name');
		} else if ($type == PlaceType::CLUB) {
			$fields['place'] = array('id', 'name', 'phone', 'alt_phone', 'email', 'min_age', 'max_age', 'description', 'type');
			$fields['facility'] = array('id', 'has_gym', 'has_food', 'bowling_machine_count', 'coach_count', 'titles_won', 'special_offer');
		} else if ($type == PlaceType::GROUND) {
			$fields['place'] = array('id', 'name', 'contact_person', 'phone', 'email', 'alt_phone', 'description', 'type');
			$fields['facility'] = array('id', 'has_gym', 'has_food', 'bowling_machine_count', 'nets', 'has_floodlights', 'has_toilets', 'special_offer', 'available_for', 'pitch_type');
		} else if ($type == PlaceType::SCHOOL) {
			$fields['place'] = array('id', 'name', 'phone', 'alt_phone', 'email', 'min_age', 'max_age', 'description', 'type');
			$fields['facility'] = array('id', 'has_individual_classes', 'has_medical_facilities', 'has_gym', 'has_food', 'bowling_machine_count', 'coach_student_ratio', 'nets', 'titles_won', 'transport', 'accomodation', 'special_offer');
			$fields['ground'] = array('id', 'name');
		} else if ($type == PlaceType::SHOP) {
			$fields['place'] = array('id', 'name', 'phone', 'alt_phone', 'email', 'min_age', 'max_age', 'description', 'type');
			$fields['facility'] = array('id', 'accept_credit_card', 'has_cricket_corner', 'special_offer');
		} else if ($type == PlaceType::SPORTS_BAR) {
			$fields['place'] = array('id', 'name', 'phone', 'alt_phone', 'email', 'min_age', 'max_age', 'description', 'type');
			$fields['facility'] = array('id', 'cuisine', 'not_to_miss', 'has_home_delivery', 'has_dine_in', 'serves_non_veg', 'serves_alcohol', 'has_ac', 'has_karyoke', 'has_smoking_area', 'accept_credit_card', 'special_offer');
		}
		return $fields;
	}

	private function __prepareViewPlaceData($place) {
		$data = array();
		$data['id'] = $place['Place']['id'];
		$data['details'] = array();
		$data['facilities'] = array();
		$data['pics'] = array();
		$data['commute'] = array();
		$weekdayTiming = array();
		$weekendTiming = array();
		$workingDays = array();
		$count = 0;
		foreach ($place['PlaceTiming'] as $id => $placeTiming) {
			$workingDays[] = $placeTiming['day_of_week'];
		}
		foreach ($place['PlaceTiming'] as $id => $placeTiming) {
			if (in_array($placeTiming['day_of_week'], array(WorkingDaysType::MONDAY, WorkingDaysType::TUESDAY, WorkingDaysType::WEDNESDAY, WorkingDaysType::THURSDAY, WorkingDaysType::FRIDAY))) {
				$weekdayTiming[$count] = date('H:i A',strtotime($placeTiming['time_open']));
				$weekdayTiming[$count+1] = $this->__getEndTime($placeTiming['time_open'], $placeTiming['working_time']);
				$count = 0;
				break;
			}
		}
		foreach ($place['PlaceTiming'] as $id => $placeTiming) {
			if (in_array($placeTiming['day_of_week'], array(WorkingDaysType::SATURDAY, WorkingDaysType::SUNDAY))) {
				$weekendTiming[$count] = date('H:i A',strtotime($placeTiming['time_open']));
				$weekendTiming[$count+1] = $this->__getEndTime($placeTiming['time_open'], $placeTiming['working_time']);
				$count = 0;
				break;
			}
		}
		$weekdayTimeJoin[] = !empty($weekdayTiming) ? 'Weekday : ' . join('-', $weekdayTiming) : null;
		$weekdayTimeJoin[] = !empty($weekendTiming) ? 'Weekend : ' . join('-', $weekendTiming) : null;
		$weekdayTimeJoin = array_filter($weekdayTimeJoin);
		$timingString = join(', ', $weekdayTimeJoin);
		$ageGroupString = '';
		if (!empty($place['Place']['min_age']) && !empty($place['Place']['max_age'])) {
			$ageGroupString = $place['Place']['min_age'] . ' - ' . $place['Place']['max_age'] . ' ' . 'Years';
		} else if (!empty($place['Place']['min_age']) && empty($place['Place']['max_age'])) {
			$ageGroupString = '>=' . $place['Place']['min_age'] . ' ' . 'Years';
		} else if (empty($place['Place']['min_age']) && !empty($place['Place']['max_age'])) {
			$ageGroupString = '<=' . $place['Place']['max_age'] . ' ' . 'Years';
		}

		$data['details']['basic_details'] = array(
			'name' => $place['Place']['name'],
			'location' => $place['Location']['name'],
			'phone' => $place['Place']['phone'],
			'description' => $place['Place']['description'],
			'alt_phone' => $place['Place']['alt_phone'],
			'email' => !empty($place['Place']['email']) ? $place['Place']['email'] : null,
			'age_group' => $ageGroupString,
			'timing' => $timingString,
			'working_days' => $workingDays
		);
		$data['details']['cost_details'] = array();
		if (!empty($place['PlacesCost'])) {
			foreach ($place['PlacesCost'] as $id => $placeCost) {
				$data['details']['cost_details'][$id]['type'] = ucfirst(strtolower(PlaceCostType::stringValue($placeCost['type'])));
				$data['details']['cost_details'][$id]['amount'] = $placeCost['amount'];
			}
		}
		if (!empty($place['PlaceFacility'])) {
			unset($place['PlaceFacility']['id']);
			unset($place['PlaceFacility']['place_id']);
			unset($place['PlaceFacility']['ground_id']);
			if (!empty($place['PlaceFacility']['Ground'])) {
				$place['PlaceFacility']['ground'] = $place['PlaceFacility']['Ground']['name'];
				unset($place['PlaceFacility']['Ground']);
			}
		}
		$place['PlaceFacility'] = array_filter($place['PlaceFacility']);
		$i = 0;
		foreach ($place['PlaceFacility'] as $facilityIndex => $facilitiyValue) {
			$data['facilities'][$i]['type'] = $facilityIndex;
			$data['facilities'][$i]['name'] = $this->__getFacilityNameFromType($facilityIndex);
			$data['facilities'][$i]['value'] = $facilitiyValue;
			$i++;
		}
		$data['facilities'] = $this->__formatPlaceFacilities($data['facilities']);
		if (!empty($place['PlacesImage'])) {
			foreach ($place['PlacesImage'] as $imageIndex => $image) {
				$data['pics'][$imageIndex]['id'] = $image['Image']['id'];
				$data['pics'][$imageIndex]['caption'] = $image['Image']['caption'];
				$data['pics'][$imageIndex]['url'] = $image['Image']['url'];
			}
		}
		if (!empty($place['PlacesEssential'])) {
			foreach ($place['PlacesEssential'] as $essentialIndex => $placesEssential) {
				$data['commute'][$essentialIndex]['type'] = strtolower(PlaceEssentialType::stringValue($placesEssential['type']));
				$data['commute'][$essentialIndex]['name'] = $placesEssential['Location']['name'];
				$data['commute'][$essentialIndex]['distance'] = $placesEssential['distance'] . ' Kms';
			}
		}
		return $data;
	}

	private function __getFacilityNameFromType($type) {
		$name = '';
		switch ($type) {
			case 'bowling_machine_count':
				$name = 'Bowling Machine';
				break;
			case 'coach_student_ratio':
				$name = 'Coach Student Ratio';
				break;
			case 'accomodation':
				$name = 'Accomodation';
				break;
			case 'transport':
				$name = 'Transport';
				break;
			case 'nets':
				$name = 'Nets';
				break;
			case 'cuisine':
				$name = 'Cuisine';
				break;
			case 'has_individual_classes':
				$name = 'Individual Classes';
				break;
			case 'has_medical_facilities':
				$name = 'Medical Facilities';
				break;
			case 'has_gym':
				$name = 'Gym';
				break;
			case 'has_food':
				$name = 'Food';
				break;
			case 'has_karyoke':
				$name = 'Karyoke';
				break;
			case 'has_wifi':
				$name = 'Wi-Fi';
				break;
			case 'accept_credit_card':
				$name = 'Credit Card';
				break;
			case 'ground':
				$name = 'Ground';
				break;
			case 'coach_count':
				$name = 'Coach Count';
				break;
			case 'titles_won':
				$name = 'Titles Won';
				break;
			case 'has_cricket_corner';
				$name = 'Cricket Corner';
				break;
			case 'sports_type':
				$name = 'Sports Type';
				break;
			case 'available_for':
				$name = 'Available For';
				break;
			case 'pitch_type':
				$name = 'Pitch Type';
				break;
			case 'not_to_miss':
				$name = 'Not To Miss';
				break;
			case 'has_home_delivery':
				$name = 'Home Delivery';
				break;
			case 'has_dine_in':
				$name = 'Dine In';
				break;
			case 'serves_non_veg':
				$name = 'Serves Non Veg';
				break;
			case 'serves_alcohol':
				$name = 'Serves Alcohol';
				break;
			case 'has_ac':
				$name = 'A/c';
				break;
			case 'has_smoking_area':
				$name = 'Smoking Area';
				break;
			case 'has_floodlights':
				$name = 'Floodlights';
				break;
			case 'has_toilets':
				$name = 'Toilets';
				break;
			case 'special_offer':
				$name = 'Special Offer';
				break;
			default:
				$name = '';
				break;
		}
		return $name;
	}

	private function __formatPlaceFacilities($facilities) {
		$count = 0;
		foreach ($facilities as $id => $facility) {
			if ($facility['type'] == 'accomodation') {
				$facilities[$id]['value'] = PlaceFacilityAccomodation::stringValue($facility['value']);
			} elseif ($facility['type'] == 'transport') {
				$facilities[$id]['value'] = PlaceFacilityTransport::stringValue($facility['value']);
			} elseif ($facility['type'] == 'cuisine') {
				$facilities[$id]['value'] = CuisineType::decodeBitmaskOptionString($facility['value']);
			} elseif ($facility['type'] == 'available_for') {
				$facilities[$id]['value'] = GroundAvailabilityType::decodeBitmaskOptionString($facility['value']);
			} else if ($facility['type'] == 'pitch_type') {
				$facilities[$id]['value'] = PitchType::decodeBitmaskOptionString($facility['value']);
			}
		}
		foreach ($facilities as $facilityIndex => $facility) {
			if ( !in_array($facility['type'], array('accomodation', 'transport', 'nets', 'ground', 'bowling_machine_count', 'coach_student_ratio', 'special_offer', 'cuisine', 'available_for', 'pitch_type', 'coach_count', 'titles_won')) ) {
				if ($facilities[$facilityIndex]['value'] == true) {
					$facilities[$facilityIndex]['value'] = 'Yes';
				} elseif ($facilities[$facilityIndex]['value'] == false) {
					$facilities[$facilityIndex]['value'] = 'No';
				}
			}
		}
		$facilities = array_values($facilities);
		return $facilities;
	}
	public function editPlace($id, $locationData, $name, $userId, $desc, $type, $contact_person, $phone, $alt_phone, $email, $min_age, $max_age, $commute, $facilities, $costData, $timeData, $images, $newGroundInfo) {
		$saveLocationResponse = $this->Location->saveLocation($locationData['name'], $locationData['latitude'], $locationData['longitude'], $locationData['unique_identifier']);
		if ($saveLocationResponse['status'] == 200) {
			$datasource = $this->getDataSource();
			$datasource->begin();
			$locationId = $saveLocationResponse['data'];
			$facilityData = $this->__prepareFacilityData($facilities, $newGroundInfo);
			$costData = $this->__prepareCostData($costData);
			$essentialsData = $this->__prepareEssentialsData($commute, $locationData);
			$placeTimingData = $this->__preparePlaceTimings($timeData);
			$imageData = $this->__prepareImageData($images);
			$is_verified = $this->User->isZooterAuthorisedDataCollector($userId);
			$data = array(
				'Place' => array(	
					'id' => $id,
					'location_id' => $locationId,
					'name' => $name,
					'user_id' => $userId,
					'description' => $desc,
					'type' => PlaceType::intValue(ucfirst($type)),
					'contact_person' => $contact_person,
					'phone' => $phone,
					'alt_phone' => $alt_phone,
					'email' => $email,
					'min_age' => $min_age,
					'max_age' => $max_age,
					'is_verified' => $is_verified
				),
				'PlaceFacility' => $facilityData,
				'PlacesCost' => $costData,
				'PlacesEssential' => $essentialsData,
				'PlaceTiming' => $placeTimingData,
				'PlacesImage' => $imageData
			);
			$currentDataCleaned = $this->__cleanCurrentData($id);
			if ($currentDataCleaned) {
				$this->create();
				if ($this->saveAssociated($data, array('deep' => true))) {
					$datasource->commit();
					$response = $this->__preparePlaceResponse($this->getLastInsertID());
				} else {
					$datasource->rollback();
					$response = array('status' => 304, 'message' => 'Could not edit Place');	
				}
			} else {
				$datasource->rollback();
				$response = array('status' => 305, 'message' => 'Could not cleanup data');	
			}
		} else {
			$response = array('status' => 303, 'message' => 'Could not identify location. Please check your inputs.');
		}
		return $response;
	}

	private function __cleanCurrentData($id) {
		$this->PlaceFacility->deleteAll(array('PlaceFacility.place_id' => $id));
		$this->PlacesCost->deleteAll(array('PlacesCost.place_id' => $id));
		$this->PlacesEssential->deleteAll(array('PlacesEssential.place_id' => $id));
		$this->PlaceTiming->deleteAll(array('PlaceTiming.place_id' => $id));
		$this->PlacesImage->deleteAll(array('PlacesImage.place_id' => $id));
		return true;
	}

	public function createPlace($locationData, $name, $userId, $desc, $type, $contact_person, $phone, $alt_phone, $email, $min_age, $max_age, $commute, $facilities, $costData, $timeData, $images, $newGroundInfo) {
		$saveLocationResponse = $this->Location->saveLocation($locationData['name'], $locationData['latitude'], $locationData['longitude'], $locationData['unique_identifier']);
		if ($saveLocationResponse['status'] == 200) {
			$datasource = $this->getDataSource();
			$datasource->begin();
			$locationId = $saveLocationResponse['data'];
			$facilityData = $this->__prepareFacilityData($facilities, $newGroundInfo);
			$costData = $this->__prepareCostData($costData);
			$essentialsData = $this->__prepareEssentialsData($commute, $locationData);
			$placeTimingData = $this->__preparePlaceTimings($timeData);
			$imageData = $this->__prepareImageData($images);
			$is_verified = $this->User->isZooterAuthorisedDataCollector($userId);
			$data = array(
				'Place' => array(	
					'location_id' => $locationId,
					'name' => $name,
					'user_id' => $userId,
					'description' => $desc,
					'type' => PlaceType::intValue(ucfirst($type)),
					'contact_person' => $contact_person,
					'phone' => $phone,
					'alt_phone' => $alt_phone,
					'email' => $email,
					'min_age' => $min_age,
					'max_age' => $max_age,
					'is_verified' => $is_verified
				),
				'PlaceFacility' => $facilityData,
				'PlacesCost' => $costData,
				'PlacesEssential' => $essentialsData,
				'PlaceTiming' => $placeTimingData,
				'PlacesImage' => $imageData
			);
			$this->create();
			if ($this->saveAssociated($data, array('deep' => true))) {
				$datasource->commit();
				$response = $this->__preparePlaceResponse($this->getLastInsertID());
				$user = $this->User->find('first', array(
					'conditions' => array(
						'User.id' => $userId,
					),
					'contain' => array('Profile')
				));
				$mergeVars = array('name' => $user['Profile']['first_name'], 'place_type' => ucfirst($type), 'place_name' => $name);
        $fromEmail = array('team@zooter.in' => 'Team Zooter');
        $toEmail = $user['User']['email'];
        $subject = 'Thank you for adding a ' . ucfirst($type);
        $template = 'place-added';
        $this->TransactionalEmail = ClassRegistry::init('TransactionalEmail');
        $this->TransactionalEmail->queueTransactionEmail($fromEmail, $toEmail, $subject, $mergeVars, '', $template);
			} else {
				$datasource->rollback();
				$response = array('status' => 304, 'message' => 'Could not save Place');	
			}
		} else {
			$response = array('status' => 303, 'message' => 'Could not identify location. Please check your inputs.');
		}
		return $response;
	}
	private function __prepareFacilityData($facilities, $newGroundInfo) {
		$data = array(
			'bowling_machine_count' => $facilities['bowling_machine_count'],
			'coach_student_ratio' => $facilities['coach_student_ratio'],
			'transport' => $facilities['transport'],
			'accomodation' => $facilities['accomodation'],
			'nets' => $facilities['nets'],
			'coach_count' => $facilities['coach_count'],
			'titles_won' => $facilities['titles_won'],
			'not_to_miss' => $facilities['not_to_miss'],
			'special_offer' => $facilities['special_offer'],
			'has_individual_classes' => false,
			'has_medical_facilities' => false,
			'has_gym' => false,
			'has_food' => false,
			'has_wifi' => false,
			'has_karyoke' => false,
			'accept_credit_card' => false,
			'ground_id' => $facilities['ground_id'],
			'cuisine' => null,
			'available_for' => null,
			'pitch_type' => null,
		);
		if (!empty($newGroundInfo)) {
			$saveLocationResponse = $this->Location->saveLocation($newGroundInfo['ground_location']['name'], $newGroundInfo['ground_location']['latitude'], $newGroundInfo['ground_location']['longitude'], $newGroundInfo['ground_location']['unique_identifier']);
			if ($saveLocationResponse['status'] == 200) {
				$groundData = array(
					'name' => $newGroundInfo['ground_name'],
					'location_id' => $saveLocationResponse['data'],
					'contact_name' => $newGroundInfo['contact_name'],
					'contact_number' => $newGroundInfo['contact_number']
				);
				$data['ground_id'] = $this->PlaceFacility->Ground->saveGround($groundData);
			}
		}
		if (!empty($facilities['other_facilities'])) {
			foreach ($facilities['other_facilities'] as $id => $facility) {
				if ($facility == PlaceFacilityType::INDIVIDUAL_CLASSES) {
					$data['has_individual_classes'] = true;
				} else if ($facility == PlaceFacilityType::MEDICAL_FACILITIES) {
					$data['has_medical_facilities'] = true;
				} else if ($facility == PlaceFacilityType::GYM) {
					$data['has_gym'] = true;
				} else if ($facility == PlaceFacilityType::FOOD) {
					$data['has_food'] = true;
				} else if ($facility == PlaceFacilityType::KARYOKE) {
					$data['has_karyoke'] = true;
				} else if ($facility == PlaceFacilityType::WIFI) {
					$data['has_wifi'] = true;
				} else if ($facility == PlaceFacilityType::CREDIT_CARD) {
					$data['accept_credit_card'] = true;
				} else if ($facility == PlaceFacilityType::CRICKET_CORNER) {
					$data['has_cricket_corner'] = true;
				} else if ($facility == PlaceFacilityType::HOME_DELIVERY) {
					$data['has_home_delivery'] = true;
				} else if ($facility == PlaceFacilityType::DINE_IN) {
					$data['has_dine_in'] = true;
				} else if ($facility == PlaceFacilityType::NON_VEG) {
					$data['serves_non_veg'] = true;
				} else if ($facility == PlaceFacilityType::ALCOHOL) {
					$data['serves_alcohol'] = true;
				} else if ($facility == PlaceFacilityType::AC) {
					$data['has_ac'] = true;
				} else if ($facility == PlaceFacilityType::SMOKING_AREA) {
					$data['has_smoking_area'] = true;
				} else if ($facility == PlaceFacilityType::FLOODLIGHTS) {
					$data['has_floodlights'] = true;
				} else if ($facility == PlaceFacilityType::TOILETS) {
					$data['has_toilets'] = true;
				}
			}
		}
		if (!empty($facilities['cuisine'])) {
			$data['cuisine'] = CuisineType::encodeBitmask($facilities['cuisine']);
		}
		if (!empty($facilities['available_for'])) {
			$data['available_for'] = GroundAvailabilityType::encodeBitmask($facilities['available_for']);
		}
		if (!empty($facilities['pitch_type'])) {
			$data['pitch_type'] = PitchType::encodeBitmask($facilities['pitch_type']);
		}
		return $data;
	}

	private function __prepareCostData($costData) {
		$data = array();
		$count = 0;
		$costData = array_filter($costData);
		foreach ($costData as $key => $value) {
			$data[$count]['type'] = PlaceCostType::intValue(ucfirst($key));
			$data[$count]['amount'] = $value;
			$count++;
		}
		return $data;
	}


	private function __prepareEssentialsData($essentials, $placeLocation) {
		$data = array();
		$count = 0;
		foreach ($essentials as $index => $essential) {
			if (!empty($essential)) {
				$saveLocationResponse = $this->Location->saveLocation($essential['name'], $essential['latitude'], $essential['longitude'], $essential['unique_identifier']);
				if ($saveLocationResponse['status'] == 200) {
					$data[$count]['location_id'] = $saveLocationResponse['data'];
					$distance = $this->Location->getDistanceBetweenCoordinates($placeLocation['latitude'], $placeLocation['longitude'], $essential['latitude'], $essential['longitude'], 'K');
					$data[$count]['distance'] = number_format((float)$distance, 2, '.', '');
					$data[$count]['type'] = $this->__getEssentialTypeByIndex($index);
				}
				$saveLocationResponse = null;
				$count++;
			}
		}
		return $data;
	}

	private function __getEssentialTypeByIndex($index) {
		switch ($index) {
			case 'nearest_bus_stop':
				$type = PlacesEssentialType::BUS_STOP;
				break;
			case 'nearest_train_station':
				$type = PlaceEssentialType::RAILWAY_STATION;
				break;
			case 'nearest_airport':
				$type = PlaceEssentialType::AIRPORT;
				break;
			case 'nearest_hospital':
				$type = PlaceEssentialType::HOSPITAL;
				break;
			case 'nearest_atm':
				$type = PlaceEssentialType::ATM;
				break;
		}
		return $type;
	}
	

	private function __preparePlaceTimings($timeData) {
		$data = array();
		$timeData = array_filter($timeData);
		if (!empty($timeData)) {
			$workingDays = $timeData['working_days'];
			$count = 0;
			foreach ($workingDays as $workingDay) {
				$dayOfWeek = WorkingDaysType::intValue($workingDay);
				$data[$count]['day_of_week'] = $dayOfWeek;
				if ( ($dayOfWeek >=1) && ($dayOfWeek <=5) ) { //If Weekday
					$data[$count]['time_open'] = date('H:i', strtotime($timeData['weekday_start_time']));
					$data[$count]['working_time'] = $this->__getTimeDifferenceInMinutes($timeData['weekday_start_time'], $timeData['weekday_end_time']);
				} else if ( ($dayOfWeek >=6) && ($dayOfWeek <=7) ) {
					if (!empty($timeData['weekend_start_time']) && !empty($timeData['weekend_end_time'])) {
					$data[$count]['time_open'] = date('H:i', strtotime($timeData['weekend_start_time']));
					$data[$count]['working_time'] = $this->__getTimeDifferenceInMinutes($timeData['weekend_start_time'], $timeData['weekend_end_time']);
					} else {
						unset($data[$count]);
					}
				}
				$count++;
			}
		}
		return $data;
	}

	private function __prepareImageData($images) {
		$imageIds = array();
		$data = array();
		$count = 0;
		if (!empty($images)) {
			foreach ($images as $image) {
				$this->PlacesImage->Image->create();
				if ($this->PlacesImage->Image->save($image)) {
					if (!empty($this->PlacesImage->Image->getLastInsertID())) {
						$data[$count]['image_id'] = $this->PlacesImage->Image->getLastInsertID();
					} else {
						$data[$count]['image_id'] = $image['id'];
					}
				}
				$count++;
			}
		}
		return $data;
	}

	private function __preparePlaceResponse($placeId) {
		$place = $this->find('first', array(
			'conditions' => array(
				'Place.id' => $placeId
			),
			'contains' => array(
				'Location' => array(
					'City'
				)
			)
		));
		if (!empty($place)) {
			$place['Place']['type'] = PlaceType::stringValue($place['Place']['type']);
		}
		$response = array('status' => 200, 'message' => 'success', 'data' => $place);
		return $response;
	}

	public function getLocationIds($locationData, $distance) {
		$nearByLocations = $this->Location->getNearbyLocation($locationData['latitude'], $locationData['longitude'], $distance);
		if ($nearByLocations['status'] == 200) {
			$locations = $nearByLocations['data'];
			$locationsIds = array();
			foreach ($locations as $location) {
				$locationsIds[] = $location['id'];
			}
			return $locationsIds;
		} else {
			return false;
		}
	}

	public function prepareSearchResponse($locationData, $placeType, $places, $paginationParams, $userId = null) {
		$data = array();
		$data['filters'] = array(
			'location' => $locationData,
			'place_type' => $placeType
		);
		$data['places'] = array();
		$data['favorite_places'] = array();
		if (!empty($places)) {
			foreach ($places as $place) {
				$coverImageUrl = !empty($place['PlacesImage'][0]['Image']['url']) ? $place['PlacesImage'][0]['Image']['url'] : null;
				$isFavorite = !empty($place['FavoritePlace']) ? true : false;
				$data['places'][] = array(
					'id' => $place['Place']['id'],
					'name' => $place['Place']['name'],
					'cover_image' => $coverImageUrl,
					'is_favorite' => $isFavorite,
					'location' => $place['Location']
				);
			}
			$favoritePlaces = $this->FavoritePlace->findFavoritePlaces($userId);

			foreach ($favoritePlaces as $place) {
				$coverImageUrl = !empty($place['Place']['PlacesImage'][0]['Image']['url']) ? $place['Place']['PlacesImage'][0]['Image']['url'] : null;
				if (!empty($place['FavoritePlace'])) {
					$data['favorite_places'][] = array(
						'id' => $place['Place']['id'],
						'name' => $place['Place']['name'],
						'cover_image' => $coverImageUrl,
						'is_favorite' => true,
						'location' => $place['Place']['Location']
					);	
				}
			}
		}
		$data['pagination'] = array(
			'page' => !empty($paginationParams['Place']['page']) ? $paginationParams['Place']['page'] : 0,
			'per_page' => !empty($paginationParams['Place']['limit']) ? $paginationParams['Place']['limit'] : 0,
			'page_count' => !empty($paginationParams['Place']['pageCount']) ? $paginationParams['Place']['pageCount'] : 0,
			'total_count' => !empty($paginationParams['Place']['count']) ? $paginationParams['Place']['count'] : 0,
			'current' => !empty($paginationParams['Place']['current']) ? $paginationParams['Place']['current'] : 0
		);
		$response = array('status' => 200, 'message' => 'success', 'data' => $data);
		return $response;
	}

	public function addToFavorite($place_id, $user_id) {
		$place = $this->findByIdAndIsVerified($place_id, true);
		if (!empty($place)) {
			$user = $this->User->findById($user_id);
			if (!empty($user)) {
				$favoritePlace = $this->FavoritePlace->findByPlaceIdAndUserId($place_id, $user_id);
				if (empty($favoritePlace)) {
					$data = array(
						'place_id' => $place_id,
						'user_id' => $user_id
					);

					if ($this->FavoritePlace->saveFavorite($data)) {
						$responseData = $this->__prepareFavoritePlaceResponse($place_id);
						$response = array('status' => 200, 'message' => 'success', 'data' => $responseData);
					} else {
						$response = array('status' => 404, 'message' => 'Could not save Favorite');
					}
				} else {
					$response = array('status' => 404, 'message' => 'Already Favorited');		
				}
			} else {
				$response = array('status' => 404, 'message' => 'User Does Not Exist');		
			}
		} else {
			$response = array('status' => 404, 'message' => 'Place Does Not Exist');
		}
		return $response;
	}
	private function __prepareFavoritePlaceResponse($placeId) {
		$place = $this->find('first', array(
			'conditions' => array(
				'Place.id' => $placeId
			),
			'contain' => array(
				'Location',
				'FavoritePlace',
				'PlacesImage' => array(
					'limit' => 1,
					'Image'
				)
			),
		));
		$coverImageUrl = !empty($place['PlacesImage'][0]['Image']['url']) ? $place['PlacesImage'][0]['Image']['url'] : null;
		$isFavorite = !empty($place['FavoritePlace']) ? true : false;
		$data = array(
			'id' => $place['Place']['id'],
			'name' => $place['Place']['name'],
			'cover_image' => $coverImageUrl,
			'is_favorite' => $isFavorite,
			'location' => $place['Location']
		);
		return $data;
	}
	public function removeFromFavorite($place_id, $user_id) {
		$place = $this->findByIdAndIsVerified($place_id, true);
		if (!empty($place)) {
			$user = $this->User->findById($user_id);
			if (!empty($user)) {
				$favoritePlace = $this->FavoritePlace->findByPlaceIdAndUserId($place_id, $user_id);
				if (!empty($favoritePlace)) {
					if ($this->FavoritePlace->deleteFavorite($favoritePlace)) {
						$responseData = $this->__prepareFavoritePlaceResponse($place_id);
						$response = array('status' => 200, 'message' => 'success', 'data' => $responseData);
					} else {
						$response = array('status' => 404, 'message' => 'Could not save Favorite');
					}
				} else {
					$response = array('status' => 404, 'message' => 'Could Not Find Fvorite Data');		
				}
			} else {
				$response = array('status' => 404, 'message' => 'User Does Not Exist');		
			}
		} else {
			$response = array('status' => 404, 'message' => 'Place Does Not Exist');
		}
		return $response;
	}

	public function getPlaceDetails($placeId, $userId) {
		$place = $this->findById($placeId);
		if (!empty($place)) {
			if ($place['Place']['user_id'] == $userId) {
				$fields = $this->getFieldsByPlaceType($place['Place']['type']);
				$place = $this->find('first', array(
					'conditions' => array(
						'Place.id' => $placeId
					),
					'fields' => $fields['place'],
					'contain' => array(
						'Location' => array(
							'fields' => $fields['location']
						),
						'PlaceFacility' => array(
							'fields' => $fields['facility'],
							'Ground' => array(
								'fields' => $fields['ground']
							)
						),
						'PlacesCost' => array(
							'fields' => $fields['cost']
						),
						'PlacesImage' => array(
							'Image' => array(
								'fields' => $fields['image']
							)
						),
						'PlacesEssential' => array(
							'fields' => $fields['essentials'],
							'Location' => array(
								'fields' => $fields['location']
							)
						),
						'PlaceTiming' => array(
							'fields' => $fields['timing']
						)
					)
				));
				$place['Place']['type'] = strtolower(PlaceType::stringValue($place['Place']['type']));
				$placeCosts = array();
				foreach ($place['PlacesCost'] as $id => $placeCost) {
					$typeKey = join('_', explode(" ", strtolower(PlaceCostType::stringValue($placeCost['type']))));
					$placeCosts[$typeKey] = $placeCost['amount'];
				}
				unset($place['PlacesCost']);
				$place['cost_details'] = $placeCosts;
				$workingDays = array();
				$weekdayTiming = array(
					'start_time' => '',
					'end_time' => ''
				);
				$weekendTiming = array(
					'start_time' => '',
					'end_time' => ''
				);
				foreach ($place['PlaceTiming'] as $id => $placeTiming) {

				}
				foreach ($place['PlaceTiming'] as $id => $placeTiming) {
					$workingDays[] = substr(WorkingDaysType::stringValue($placeTiming['day_of_week']), 0, 3);
				}
				$count = 0;
				foreach ($place['PlaceTiming'] as $id => $placeTiming) {
					if (in_array($placeTiming['day_of_week'], array(WorkingDaysType::MONDAY, WorkingDaysType::TUESDAY, WorkingDaysType::WEDNESDAY, WorkingDaysType::THURSDAY, WorkingDaysType::FRIDAY))) {
						$weekdayTiming['start_time'] = date('H:i A',strtotime($placeTiming['time_open']));
						$weekdayTiming['end_time'] = $this->__getEndTime($placeTiming['time_open'], $placeTiming['working_time']);
						$count = 0;
						break;
					}
				}
				foreach ($place['PlaceTiming'] as $id => $placeTiming) {
					if (in_array($placeTiming['day_of_week'], array(WorkingDaysType::SATURDAY, WorkingDaysType::SUNDAY))) {
						$weekendTiming['start_time'] = date('H:i A',strtotime($placeTiming['time_open']));
						$weekendTiming['end_time'] = $this->__getEndTime($placeTiming['time_open'], $placeTiming['working_time']);
						$count = 0;
						break;
					}
				}
				$place['timings']['working_days'] = $workingDays;
				$place['timings']['weekday_start_time'] = $weekdayTiming['start_time'];
				$place['timings']['weekday_end_time'] = $weekdayTiming['end_time'];
				$place['timings']['weekend_start_time'] = $weekendTiming['start_time'];
				$place['timings']['weekend_end_time'] = $weekendTiming['end_time'];
				unset($place['PlaceTiming']);
				$booleanFacilities = array();
				if (!empty($place['PlaceFacility'])) {
					unset($place['PlaceFacility']['id']);
					foreach ($place['PlaceFacility'] as $key => $value) {
						if ((in_array($this->__getFacilityNameFromType($key), PlaceFacilityType::options())) && !empty($value) ) {
							$booleanFacilities[] = (string)PlaceFacilityType::intValue($this->__getFacilityNameFromType($key));
						}
						if ( in_array($this->__getFacilityNameFromType($key), PlaceFacilityType::options()) ) {
							unset($place['PlaceFacility'][$key]);
						}
					}
				}
				$place['facilities']['checked_facilities'] = $booleanFacilities;
				$place['facilities']['other_facilities'] = $place['PlaceFacility'];
				if (!empty($place['facilities']['other_facilities']['cuisine'])) {
					$place['facilities']['other_facilities']['cuisine'] = CuisineType::decodeBitmask($place['facilities']['other_facilities']['cuisine']);
				}
				if (!empty($place['facilities']['other_facilities']['available_for'])) {
					$place['facilities']['other_facilities']['available_for'] = GroundAvailabilityType::decodeBitmask($place['facilities']['other_facilities']['available_for']);
				}
				if (!empty($place['facilities']['other_facilities']['pitch_type'])) {
					$place['facilities']['other_facilities']['pitch_type'] = PitchType::decodeBitmask($place['facilities']['other_facilities']['pitch_type']);
				}
				unset($place['PlaceFacility']);
				$images = array();
				if (!empty($place['PlacesImage'])) {
					foreach ($place['PlacesImage'] as $id => $image) {
						$images[$id]['id'] = $image['Image']['id'];
						$images[$id]['name'] = null;
						$images[$id]['size'] = null;
						$images[$id]['type'] = null;
						$images[$id]['caption'] = $image['Image']['caption'];
						$images[$id]['url'] = $image['Image']['url'];
						$images[$id]['progress'] = 100;
					}
				}
				unset($place['PlacesImage']);
				$place['images'] = $images;
				$response = array('status' => 200, 'message' => 'success', 'data' => $place);
			} else {
				$response = array('status' => 404, 'message' => 'Not Authorized to do this action');
			}
		} else {
			$response = array('status' => 404, 'message' => 'Place Does Not Exist');
		}
		return $response;
	}

	public function getPlacesList($userId) {
		$user = $this->User->findById($userId);
		if (!empty($user)) {
			$places = $this->find('all', array(
				'conditions' => array(
					'Place.user_id' => $userId,
				),
				'fields' => array('id', 'name', 'type')
			));
			$data = array();
			if (!empty($places)) {
				foreach ($places as $id => $place) {
					$data[$id]['id'] = $place['Place']['id'];
					$data[$id]['name'] = $place['Place']['name'];
					$data[$id]['type'] = PlaceType::stringValue($place['Place']['type']);
				}
			}
			$response = array('status' => 200, 'message' => 'success', 'data' => $data);
		} else {
			$response = array('status' => 404, 'message' => 'User Does Not Exist');	
		}
		return $response;
	}
}
