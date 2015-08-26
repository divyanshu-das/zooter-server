<?php
App::uses('AppModel', 'Model');
/**
 * SeriesPrivilege Model
 *
 * @property Series $Series
 * @property User $User
 */
class SeriesPrivilege extends AppModel {

public $validate = array(
    'series_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Series id is not numeric',
        'required' => true,
        'allowEmpty' => false,
        'on' => 'create'
      ),
      'seriesExist' => array(
        'rule' => array('seriesExist'),
        'on' => 'create',
      )
    ),
    'user_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'User id is not numeric',
        'required' => true,
        'allowEmpty' => false,
        'on' => 'create'
      ),
      'userExist' => array(
        'rule' => array('userExist'),
        'on' => 'create'
      )
    ),
    'is_admin' => array(
      'boolean' => array(
        'rule' => array('boolean'),
        'required' => true,
        'allowEmpty' => false,
        'message' => 'IsAdmin value is not valid',
        'on' => 'create'
      )
    ),
    'status' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'required' => true,
        'allowEmpty' => false,
        'message' => 'status value is not valid'
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
		)
	);

public function showSeriesPrivilege($id) {
    $series_privilege = $this->findById($id);
    if ( ! empty($series_privilege)) {
      $response = array('status' => 200, 'data' => $series_privilege);
    } else {
      $response = array('status' => 302, 'message' => 'Series Privilege Data Could Not Be Retrieved');
    }
    return $response;
  }

  public function deleteSeriesPrivilege($id) {
    $seriesPrivilege = $this->showSeriesPrivilege($id);
    if ($seriesPrivilege['status'] != 200 ){
      $response = array('status' => 905, 'message' => 'SeriesPrivilege does not exist');
      return $response;
    }
    $this->_updateCache($id);
    $delete = $this->softDelete($id);
    $response = array('status' => 200, 'data'=> $delete , 'message' => 'Deleted');
    return $response;
  }

  public function updateSeriesPrivileges($seriesId , $seriesPrivileges) {
    $series = $this->Series->showSeries($seriesId);
    if ($series['status'] != 200 ) {
      $response = array('status' => 905, 'message' => 'Series does not exist');
      return $response;
    }
    $this->validateId();
    $this->_updateCache(null,$seriesPrivileges,$seriesId);
    if ( ! empty($seriesPrivileges)) {
      if ($this->saveMany($seriesPrivileges)) {
        $response = array('status' => 200 , 'data' =>'Series Player Scores Saved');
      } else {
        $response = array('status' => 316, 'message' => 'Series Player Score Could not be added or updated');
        pr($this->validationErrors);
      }
    } else {      
      $response = array('status' => 317, 'message' => 'No Data To Update or Add Series Player Score');     
    }
    return $response ;
  }

  private function _updateCache($id = null,$seriesPrivileges = null, $seriesId = null) {
    if ( ! empty($id)) {
      $seriesPrivilege = $this->showSeriesPrivilege($id);
      if ( ! empty($seriesPrivilege['data']['SeriesPrivilege']['series_id'])) {
        Cache::delete('show_series_'.$seriesPrivilege['data']['SeriesPrivilege']['series_id']);
        //Cache::delete('show_user_'.$seriesInningScorecard['data']['SeriesInningScorecard']['user_id']);  *** use when show_user_1 cache exists ***
      }
    }
    if ( ! empty($seriesId)) {
      //  $series = $this->Series->showSeries($seriesId);               *** use when show_user_1 cache exists ***
      Cache::delete('show_series_'.$seriesId);
      //  foreach ($series['data']['SeriesPlayer'] as $seriesplayer) {       *** use when show_user_1 cache exists ***
      //  Cache::delete('show_user_'.$seriesplayer['user_id']);              *** use when show_user_1 cache exists ***
      //  }
    }
    //  if(!empty($seriesPrivileges)){
    //  foreach ($seriesPrivileges as $seriesPrivilege) {
    //    Cache::delete('show_user_'.$seriesPrivilege['user_id']);
    //  }
    // }
  }

  public function getSeriesAdminRequest($id) {
    $request_name = array();
    if (!empty($id)) {
      $data = $this->find('first' ,array(
        'conditions' => array('SeriesPrivilege.id' => $id),
        'fields' => array('series_id'),
        'contain' => array(
          'Series' => array(
            'fields' => array('id','start_date_time','name')
          )
        )
      ));
      if (!empty($data)) {
        $request_name['series']['id'] = $data['Series']['id'];
        $request_name['series']['name'] = $data['Series']['name'];
        $request_name['series']['start_date_time'] = $data['Series']['start_date_time'];
      }
    }
    return $request_name;
  }

  public function handleSeriesAdminRequest($requestId,$userId,$status) {
    if (!empty($requestId) && !empty($userId) && !empty($status)) {
      if ($this->isUserEligbleForRequest($requestId,$userId)) {
        $data = array(
          'SeriesPrivilege' => array(
            'id' => $requestId,
            'status' => $status
          )
        );
        if ($this->save($data)) {
          $response =  array('status' => 200 , 'message' => 'success');
        } 
        else {
          $response =  array('status' => 131, 'message' => 'handleSeriesAdminRequest : Series Admin Request Accept Could not be updated');
        } 
      }
      else {
        $response =  array('status' => 130, 'message' => 'handleSeriesAdminRequest : User Not Eligible to Accept Series Admin Request');
      }
    }
    else {
      $response =  array('status' => 129, 'message' => 'handleSeriesAdminRequest : Invalid Input Arguments');
    }
    return $response;
  }

  public function isUserEligbleForRequest($requestId,$userId) {
    return $this->find('count',array(
      'conditions' => array(
        'SeriesPrivilege.id' => $requestId,
        'SeriesPrivilege.user_id' => $userId,
        'SeriesPrivilege.status' => InvitationStatus::INVITED
      )
    ));
  }

}
