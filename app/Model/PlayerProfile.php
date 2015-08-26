<?php
App::uses('AppModel', 'Model');
App::uses('PlayerProfileType', 'Lib/Enum');
/**
 * PlayerProfile Model
 *
 * @property User $User
 */
class PlayerProfile extends AppModel {


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
		)
	);

	public function getPlayerInfo($userId) {
    $playerInfoFromCache = Cache::read('player_info_user_'.$userId);
    if (!empty($playerInfoFromCache)) {
      return $playerInfoFromCache;
    }
    $playerInfo = array();
    $data = $this->find('first', array(
    	'conditions' => array(
        'user_id' => $userId
    	),
    	'fields' => array('batting_arm','bowling_arm','bowling_style')
    ));

    if (!empty($data)) {    
      $playerInfo['batting_arm'] = PlayerProfileType::stringValue($data['PlayerProfile']['batting_arm']);
      $playerInfo['bowling_arm'] = PlayerProfileType::stringValue($data['PlayerProfile']['bowling_arm']);
      $playerInfo['bowling_style'] = PlayerProfileType::stringValue($data['PlayerProfile']['bowling_style']);
    } else {
        $playerInfo['batting_arm'] = null;
        $playerInfo['bowling_arm'] = null;
        $playerInfo['bowling_style'] = null;
    }

    return $playerInfo;
	}

  public function editPlayerProfile($userId,$fields) {
    if (empty($userId) || empty($fields)) {
      return array('status' => 100, 'message' => 'editPlayerProfile : Invalid Input Arguments');
    }
    if (!$this->_userExists($userId)) {
      return array('status' => 332 , 'message' => 'editPlayerProfile : User Does not exist');
    }
    $playerProfile = $this->findByUserId($userId);
    $data = array();
    $data['PlayerProfile']['id'] = $playerProfile['PlayerProfile']['id'];
    $armTypes = [PlayerProfileType::stringValue(PlayerProfileType::LEFT),PlayerProfileType::stringValue(PlayerProfileType::RIGHT)];
    $bowlingStyleTypes = [PlayerProfileType::stringValue(PlayerProfileType::FAST),PlayerProfileType::stringValue(PlayerProfileType::MEDIUM_PACE),PlayerProfileType::stringValue(PlayerProfileType::SLOW),PlayerProfileType::stringValue(PlayerProfileType::OFF_BREAK),PlayerProfileType::stringValue(PlayerProfileType::LEG_BREAK)];
    foreach ($fields as $key => $value) {
      $value = trim($value);
      if (!empty($value)) {
        switch ($key) {
          case 'batting_arm':           
            if (in_array($value, $armTypes)) {
              $data['PlayerProfile']['batting_arm'] = PlayerProfileType::intValue($value);
            } else {
              return array('status' => 100, 'message' => 'editPlayerProfile : Invalid Batting Arm Data');
            }
            break;
          case 'bowling_arm':
            if (in_array($value, $armTypes)) {
              $data['PlayerProfile']['bowling_arm'] = PlayerProfileType::intValue($value);
            } else {
              return array('status' => 100, 'message' => 'editPlayerProfile : Invalid Bowling Arm Data');
            }
            break;
          case 'bowling_style':
            if (in_array($value, $bowlingStyleTypes)) {
              $data['PlayerProfile']['bowling_style'] = PlayerProfileType::intValue($value);
            } else {
              return array('status' => 100, 'message' => 'editPlayerProfile : Invalid Bowling Style Data');
            }
            break;
        }
      }
    }
    
    if ($this->save($data)) {
      return array('status'=> 200 , 'data' => array('player_info' => $this->getPlayerInfo($userId)) , 'message' => 'success');
    } else {
      return array('status' => 105 , 'message' => 'editPlayerProfile : Player info edit was unsuccessfull');
    }

  }


}
