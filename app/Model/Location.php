<?php
App::uses('AppModel', 'Model');
/**
 * Location Model
 *
 * @property Profile $Profile
 */
class Location extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
			),
		),
		'unique_identifier' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'Unique Identifier already exists'
			)
		),
		'latitude' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
			),
			'between' => array(
        'rule'    => array('between', -90, 90),
        'message' => 'Not valid latitude'
      ),
      'float' => array(
      	'rule' => array('decimal'),
      	'message' => 'Latitude should be floating point number'
    	)
		),
		'longitude' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
			),
			'between' => array(
        'rule'    => array('between', -180, 180),
        'message' => 'Not valid longitude'
      ),
      'float' => array(
      	'rule' => array('decimal'),
      	'message' => 'Longitude should be floating point number'
    	)
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'City' => array(
			'className' => 'City',
			'foreignKey' => 'city_id',
			'counterCache' => true,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Profile' => array(
			'className' => 'Profile',
			'foreignKey' => 'location_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'WallPost' => array(
			'className' => 'WallPost',
			'foreignKey' => 'location_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'location_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Series' => array(
			'className' => 'Series',
			'foreignKey' => 'location_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'location_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Place' => array(
			'className' => 'Place',
			'foreignKey' => 'location_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PlacesEssential' => array(
			'className' => 'PlacesEssential',
			'foreignKey' => 'location_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)

	);

	public function saveLocation($locationName, $latitude, $longitude, $uniqueIdentifier) {
		$locationName =trim($locationName);
		$latitude = trim($latitude);
		$longitude = trim($longitude);
		$uniqueIdentifier = trim($uniqueIdentifier);
		if (empty($locationName) || empty($latitude) || empty($longitude) || empty($uniqueIdentifier)) {
			return array('status' => 100, 'message' => 'saveLocation : Invalid Input Arguments');
		}
		if ( ( ! is_float((float)$latitude)) && ( ! is_float((float)$longitude))) {
			return array('status' => 100, 'message' => 'saveLocation : Invalid Input , Latitude and Longitude should be float');
		}
		$existingLocation = $this->find('first',array(
			'conditions'=> array(
				'Location.unique_identifier' => $uniqueIdentifier
			),
			'contain' => array(
				'City' => array(
					'fields' => array('id','name')
				)
			)
		));
		
		$dataSource = $this->getDataSource();
    $dataSource->begin();

		if (empty($existingLocation)) {
			$cityName = $this->getCityNameFromLocation($latitude,$longitude);
			if ($cityName['status'] == 200 && (!empty($cityName['data']))) {
				$cityName = $cityName['data'];
			} else {
				return array('status' => 108, 'message' => 'saveLocation : No City found for givnen latitudes and longitudes');
			}

			$cityId = $this->City->getOrSaveCity($cityName);
			if ($cityId['status'] == 200) {
				$cityId = $cityId['data'];
			} else {
				return array('status' => $cityId['status'], 'message' => $cityId['message']);
			}

			$locationData = array(
	  		'name' => $locationName,
	  		'latitude' => $latitude,
	  		'longitude' => $longitude,
	  		'unique_identifier' => $uniqueIdentifier,
	  		'city_id' => $cityId
			);
			$this->create();
			if ($this->save($locationData)) {
	  		$response = array('status' => 200, 'message' => 'success', 'data' => $this->getLastInsertID());
			} else {
				$response = array('status' => 107, 'message' => 'saveLocation : Location could not be saved');
			}
		} else {
			$response = array('status' => 200, 'message' => 'success', 'data' => $existingLocation['Location']['id']);
		}

		if ($response['status'] == 200) {
      $dataSource->commit();
    } else {
    	$dataSource->rollback();
    }
		return $response;
	}

	public function getNearbyLocation($latitude, $longitude, $distance){
		$latitude = trim($latitude);
		$longitude = trim($longitude);
		$distance = trim($distance);
		if (empty($latitude) || empty($longitude) || empty($distance)) {
			return array('status' => 100 , 'message' => 'getNearbyLocation : Invalid Input Argumetns');
		}
		$longitude_1 = $longitude - $distance / abs(cos(deg2rad($latitude)) * 111);
		$longitude_2 = $longitude + $distance / abs(cos(deg2rad($latitude)) * 111);
		$latitude_1  = $latitude - ($distance / 111); 
		$latitude_2  = $latitude + ($distance / 111);
		$nearbyLocations = $this->query("SELECT *, 6378 * 2 * ASIN(SQRT(
			POWER(SIN(($latitude - abs(Location.latitude)) * pi() / 180 / 2), 2) + 
			COS($latitude * pi() / 180 ) * COS(abs(Location.latitude) * pi() / 180) * POWER(SIN(($longitude - Location.longitude) * pi() / 180 / 2), 2) 
			)) AS distance
			FROM locations AS Location
			WHERE Location.longitude between $longitude_1 and $longitude_2
			and Location.latitude between $latitude_1 and $latitude_2
			having distance <= $distance
			ORDER BY distance;");
		$locations = array();
		foreach ($nearbyLocations as $key => $location) {
			$locations[$key]['id'] = $location['Location']['id'];
			$locations[$key]['name'] = $location['Location']['name'];
			$locations[$key]['distance'] = round($location[0]['distance'],4);
		}
		$response = array('status' => 200, 'data' => $locations);
		return $response;
	}

	public function getCityNameFromLocation($latitude,$longitude) {
		$latitude = trim($latitude);
		$longitude = trim($longitude);
		$cityName = null;
		if (empty($latitude) || empty($longitude)) {
			return array('status' => 100, 'message' => 'getCityNameFromLocation : Invalid Input Arguments');
		}
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude;
		$results = @json_decode(file_get_contents($url),true);
		//$results = json_decode($this->curl_get_contents($url),true);
		if (empty($results) || empty($results['results']) || empty($results['status'])) {
			return array('status' => 104, 'data' => $cityName,'message' => 'getCityNameFromLocation : Network Error');
		}
		if ($results['status'] != "OK") {
			return array('status' => 105, 'data' => $cityName, 'message' => 'getCityNameFromLocation : No results set from Geocoder');
		}
		$results = $results['results'][0]['address_components'];
		foreach ($results as $result) {
			if (in_array('administrative_area_level_2', $result['types'])) {
				$cityName = $result['long_name'];
				break;
			}
		}
		return array('status' => 200, 'data' => $cityName, 'message' => 'success');
	}

	public function getDistanceBetweenCoordinates($lat1, $lon1, $lat2, $lon2, $unit) {

	  $theta = $lon1 - $lon2;
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  $dist = acos($dist);
	  $dist = rad2deg($dist);
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);

	  if ($unit == "K") { // In Kilometers
	    return ($miles * 1.609344);
	  } else if ($unit == "N") { //In Nautical Miles
      return ($miles * 0.8684);
    } else {
      return $miles; // In Miles
    }
	}

	private function curl_get_contents($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

}
