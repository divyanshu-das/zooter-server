<?php
App::uses('AppModel', 'Model');
/**
 * SeriesAward Model
 *
 * @property Series $Series
 * @property User $User
 * @property AwardType $AwardType
 */
class SeriesAward extends AppModel {

	public $validate = array(
		'series_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Series id is not numeric',
				'on' => 'update',
			),
			'seriesExist' => array(
				'rule' => array('seriesExist'),
				'on' => 'update',
			)
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'User id is not numeric',
				'allowEmpty' => true
			),
			'userExist' => array(
				'rule' => array('userExist'),
				'allowEmpty'=> true
			)
		),
		'award_type_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Award type Id is not numeric'
			)
		),
		'value' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'value is not numeric'
			)
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Series' => array(
			'className' => 'Series',
			'foreignKey' => 'series_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AwardType' => array(
			'className' => 'AwardType',
			'foreignKey' => 'award_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function showSeriesAward($id) {
		$series_award = $this->findById($id);
		if ( ! empty($series_award)) {
			$response = array('status' => 200, 'data' => $series_award);
		} else {
			$response = array('status' => 302, 'message' => 'Series Award Data Could Not Be Retrieved');
		}
		return $response;
	}

	public function deleteSeriesAward($id){
		$seriesAward = $this->showSeriesAward($id);
		if ( $seriesAward['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Series Award does not exist');
			return $response;
		}
		$this->_updateCache($id,null);
		$delete = $this->softDelete($id);
		$response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
		return $response;
	}


	public function updateSeriesAwards($seriesId, $series_awards) {
		$series = $this->Series->showSeries($seriesId);
		if ($series['status'] != 200 ) {
			$response = array('status' => 905, 'message' => 'Series does not exist');
			return $response;
		}
		$this->validateId();
		$this->_updateCache(null,$seriesId);
		$award_names = array();
	 	foreach ($series_awards as $series_award) {
	 		$award_names[] = $series_award['award_name'];
	 	}
		$existing_award = $this->AwardType->find('list',array(
			'conditions' => array('AwardType.award_name' => $award_names),
			'fields' => array('AwardType.award_name','AwardType.id')
		));	
		$awards = [];
			foreach ($series_awards as $series_award) {
				if ( ! array_key_exists('id',$series_award) ){
					$series_award['id'] = "";
				}
				if (array_key_exists($series_award['award_name'], $existing_award )) {
					if ( isset($series_award['deleted']) && $series_award['deleted']== true) {
						$awards[] = array(
							'id' => $series_award['id'],
							'series_id' => $seriesId, 
							'user_id'=> $series_award['user_id'], 
							'value'=> $series_award['value'], 
							'award_type_id' => $existing_award[$series_award['award_name']], 
							'deleted'=> true, 
							'deleted_date'=> date('Y-m-d H:i:s') 
						);
					} else{
						$awards[] = array(
							'id' => $series_award['id'], 
							'series_id' => $seriesId, 
							'user_id'=> $series_award['user_id'], 
							'value'=> $series_award['value'], 
							'award_type_id' => $existing_award[$series_award['award_name']]
						);		
					}
				} else {
					if ( isset($series_award['deleted']) && $series_award['deleted']== true) {
						$awards[] = array(
							'id' => $series_award['id'], 
							'series_id' => $seriesId, 
							'user_id'=> $series_award['user_id'],
							'value'=> $series_award['value'], 
							'AwardType' => array('award_name' => $series_award['award_name']), 
							'deleted'=> true, 
							'deleted_date'=> date('Y-m-d H:i:s')
						);
					} else {
						$awards[] = array(
							'id' => $series_award['id'], 
							'series_id' => $seriesId, 
							'user_id'=> $series_award['user_id'],
							'value'=> $series_award['value'], 
							'AwardType' => array('award_name' => $series_award['award_name'] )
						);						
					}
				}
			}
		if ( ! empty($awards)) {
			if ($this->saveMany($awards, array('deep' => true))) {
				$response = array('status' => 200 , 'message' => 'Series Updated','data' => '');
			} else {
				$response = array('status' => 308, 'message' => 'Updating Series Awards Unsuccessfull' , 'data' => $this->validationErrors);
			}
		} else {			
			$response = array('status' => 309, 'message' => 'No Data To Modify Series Awards');			
		}
		return $response ;
	}

	private function _updateCache($id = null,$series_id = null) {
		if ( ! empty($id)) {
			$seriesAward = $this->showSeriesAward($id);
			if ( ! empty($seriesAward['data']['SeriesAward']['series_id'])) {
				Cache::delete('show_series_' . $seriesAward['data']['SeriesAward']['series_id']);
			}
		}
		if ( ! empty($series_id)) {
			Cache::delete('show_series_' . $series_id);
		}
  }

	public function updateSeriesAward($series_id, $series_awards) {
		$award_names = array();
 		foreach ($series_awards as $series_award) {
 			$award_names[] = $series_award['award_name'];
 		}
		$existing_award = $this->AwardType->find('list',array(
		'conditions' => array('AwardType.award_name' => $award_names),
		 'fields' => array('AwardType.award_name','AwardType.id')
		));	
		$awards = [];
		$data= array();
		foreach($series_awards as $series_award){
			if (array_key_exists($series_award['award_name'], $existing_award )){
				$series_award['award_type_id'] = $existing_award[$series_award['award_name']];
				$awards[] = $series_award;
			} else {
				$series_award['AwardType'] = array('award_name' => $series_award['award_name'] ) ;
				$awards[] = $series_award;
			}
			$data =array(
	 		'Series' => array(
	 			'id' => $series_id, 			
				),
	 		'SeriesAward' => $awards
			);
		}
			if ( ! empty($awards)) {
				if ($this->Series->saveAssociated($data, array('deep' => true))) {
					$response = array('status' => 200 , 'data' => '');
				} else {
					$response = array('status' => 308, 'message' => 'Updating Series Awards Unsuccessfull');
				}
			}	else {			
				$response = array('status' => 309, 'message' => 'No Data To Update or Add Series Awards');			
			}
		return $response ;
	}
}
