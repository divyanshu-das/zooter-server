<?php
App::uses('AppModel', 'Model');
App::uses('FollowerStatus', 'Lib/Enum');
/**
 * UserFriend Model
 *
 * @property User $User
 */
class UserFollower extends AppModel {


  //The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
  public $belongsTo = array(
    'UserFrom' => array(
      'className' => 'User',
      'foreignKey' => 'user_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'UserTo' => array(
      'className' => 'User',
      'foreignKey' => 'follower_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );
  public function getFollowers($profileUserId) {
    $followers = $this->find('all', array(
      'conditions' => array(
        'user_id' => $profileUserId,
        'status' => FollowerStatus::FOLLOWING
      ),
      'fields' => array('id'),
      'contain' => array(
        'UserTo' => array(
          'fields' => array('id'),
          'Profile' => array(
            'fields' => array('id', 'user_id', 'first_name', 'middle_name', 'last_name'),
            'ProfileImage' => array(
              'fields' => array('id', 'url', 'caption')
            )
          )
        )
      )
    ));
    $data = array();
    $count = 0;
    if(!empty($followers)) {
      foreach ($followers as $id => $follower) {
        $data[$count]['id'] = $follower['UserTo']['Profile']['user_id'];
        $data[$count]['name'] = $follower['UserTo']['Profile']['first_name'] . ' ' . $follower['UserTo']['Profile']['middle_name'] . ' ' . $follower['UserTo']['Profile']['last_name'];
        $data[$count]['image_url'] = !empty($follower['UserTo']['Profile']['ProfileImage']['url']) ? $follower['UserTo']['Profile']['ProfileImage']['url'] : null;
        $data[$count]['is_following'] = $this->isUserFollowingFollower($profileUserId, $follower['UserTo']['Profile']['user_id']);
        $count++;
      }
    }
    return $data;
  }
  public function isUserFollowingFollower($profileUserId, $followerId) {
    return $this->find('count', array(
      'conditions' => array(
        'user_id' => $followerId,
        'follower_id' => $profileUserId,
        'status' =>FollowerStatus::FOLLOWING
      )
    ));
  }
  public function getFollowing($profileUserId) {
    $followers = $this->find('all', array(
      'conditions' => array(
        'follower_id' => $profileUserId,
        'status' => FollowerStatus::FOLLOWING
      ),
      'fields' => array('id'),
      'contain' => array(
        'UserTo' => array(
          'fields' => array('id'),
          'Profile' => array(
            'fields' => array('id', 'user_id', 'first_name', 'middle_name', 'last_name'),
            'ProfileImage' => array(
              'fields' => array('id', 'url', 'caption')
            )
          )
        )
      )
    ));
    $data = array();
    $count = 0;
    if(!empty($followers)) {
      foreach ($followers as $id => $follower) {
        $data[$count]['id'] = $follower['UserTo']['Profile']['user_id'];
        $data[$count]['name'] = $follower['UserTo']['Profile']['first_name'] . ' ' . $follower['UserTo']['Profile']['middle_name'] . ' ' . $follower['UserTo']['Profile']['last_name'];
        $data[$count]['image_url'] = !empty($follower['UserTo']['Profile']['ProfileImage']['url']) ? $follower['UserTo']['Profile']['ProfileImage']['url'] : null;
        $count++;
      }
    }
    return $data;
  }
}
