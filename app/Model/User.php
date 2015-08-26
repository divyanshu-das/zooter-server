<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
App::uses('MatchType', 'Lib/Enum');
App::uses('MatchResultType', 'Lib/Enum');
App::uses('InvitationStatus', 'Lib/Enum');
App::uses('WallPostType', 'Lib/Enum');
App::uses('MatchTossType','Lib/Enum');
App::uses('Match', 'Model');
App::uses('SearchType', 'Lib/Enum');
App::uses('TeamSearchType', 'Lib/Enum');
App::uses('MatchSearchType', 'Lib/Enum');
App::uses('PlayerStatisticsType', 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
App::uses('PlayerProfileType', 'Lib/Enum');
App::uses('PlayerRole', 'Lib/Enum');
App::uses('FollowerStatus', 'Lib/Enum');
App::uses('GenderType', 'Lib/Enum');
/**
 * User Model
 *
 * @property Type $Type
 * @property Profile $Profile
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'email';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_verified' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'access_level' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Type' => array(
			'className' => 'Type',
			'foreignKey' => 'type_id',
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
		'ApiKey' => array(
			'className' => 'ApiKey',
			'foreignKey' => 'user_id',
			'dependent' => false
		),
    'WallPostSent' => array(
      'className' => 'WallPost',
      'foreignKey' => 'user_id'
    ),
    'WallPostComment' => array(
      'className' => 'WallPostComment',
      'foreignKey' => 'user_id'
    ),
    'WallPostLike' => array(
      'className' => 'WallPostLike',
      'foreignKey' => 'user_id'
    ),
    'FriendFrom' => array(
      'className' => 'UserFriend',
      'foreignKey' => 'user_id',
      'dependent' => false
    ),
    'FriendTo' => array(
      'className' => 'UserFriend',
      'foreignKey' => 'friend_id',
      'dependent' => false
    ),
    "Follower" => array(
      'className' => 'UserFollower',
      'foreignKey' => 'user_id',
      'dependent' => false
    ),
    'Following' => array(
      'className' => 'UserFollower',
      'foreignKey' => 'follower_id',
      'dependent' => false
    ),
    'GroupMessage' => array(
      'className' => 'GroupMessage',
      'foreignKey' => 'user_id',
      'dependent' => false
    ),
    'Group' => array(
      'className' => 'Group',
      'foreignKey' => 'user_id'
    ),
    'GroupMessageComment' => array(
      'className' => 'GroupMessageComment',
      'foreignKey' => 'user_id',
      'dependent' => false,
    ),
    'GroupMessageLike' => array(
      'className' => 'GroupMessageLike',
      'foreignKey' => 'user_id',
      'dependent' => false,
    ),
    'Match' => array(
      'className' => 'Match',
      'foreignKey' => 'owner_id',
    ),
    'MatchAward' => array(
      'className' => 'MatchAward',
      'foreignKey' => 'user_id',
    ),
    'MatchComment' => array(
      'className' => 'MatchComment',
      'foreignKey' => 'comment_by',
    ),
    'MatchPlayer' => array(
      'className' => 'MatchPlayer',
      'foreignKey' => 'user_id',
    ),
    'MatchPlayerScorecard' => array(
      'className' => 'MatchPlayerScorecard',
      'foreignKey' => 'user_id',
    ),
    'MatchPrivilege' => array(
      'className' => 'MatchPrivilege',
      'foreignKey' => 'user_id',
    ),
    'MatchStaff' => array(
      'className' => 'MatchStaff',
      'foreignKey' => 'user_id',
    ),
    'MatchFollower' => array(
      'className' => 'MatchFollower',
      'foreignKey' => 'user_id',
    ),
    'MatchRecommendation' => array(
      'className' => 'MatchRecommendation',
      'foreignKey' => 'user_id',
    ),
    'MatchBatsmanScore' => array(
      'className' => 'MatchBatsmanScore',
      'foreignKey' => 'user_id',
    ),
    'MatchBowlerScore' => array(
      'className' => 'MatchBowlerScore',
      'foreignKey' => 'user_id',
    ),
    'SeriesOwner' => array(
      'className' => 'Series',
      'foreignKey' => 'owner_id',
    ),
    'SeriesAward' => array(
      'className' => 'SeriesAward',
      'foreignKey' => 'user_id',
    ),
    'SeriesPrivilege' => array(
      'className' => 'SeriesPrivilege',
      'foreignKey' => 'user_id',
    ),
    'Team' => array(
      'className' => 'Team',
      'foreignKey' => 'owner_id',
    ),
    'TeamPlayer' => array(
      'className' => 'TeamPlayer',
      'foreignKey' => 'user_id',
    ),
    'TeamPrivilege' => array(
      'className' => 'TeamPrivilege',
      'foreignKey' => 'user_id',
    ),
    'TeamStaff' => array(
      'className' => 'TeamStaff',
      'foreignKey' => 'user_id',
    ),
    'UserToBeReminded' => array(
      'className' => 'Reminder',
      'foreignKey' => 'user_id',
    ),
    'UserCreatedReminder' => array(
      'className' => 'Reminder',
      'foreignKey' => 'created_by',
    ),
    'UserModifiedReminder' => array(
      'className' => 'Reminder',
      'foreignKey' => 'modified_by',
    ),
    'UserCreatedRequest' => array(
      'className' => 'UserRequest',
      'foreignKey' => 'created_by',
    ),
    'UserModifiedRequest' => array(
      'className' => 'UserRequest',
      'foreignKey' => 'modified_by',
    ),
    'UserGroup' => array(
      'className' => 'UserGroup',
      'foriegnKey' => 'user_id'
    ),
    'Notification' => array(
      'className' => 'Notification',
      'foriegnkey' => 'user_id'
    ),
    'PlayerStatistic' => array(
      'className' => 'PlayerStatistic',
      'foreignKey' => 'user_id',
    ),
    'Album' => array(
      'className' => 'Album',
      'foriegnkey' => 'user_id'
    ),
    'Video' => array(
      'className' => 'Video',
      'foriegnkey' => 'user_id'
    ),
    'Image' => array(
      'className' => 'Image',
      'foriegnkey' => 'user_id'
    ),
    'UserFavorite' => array(
      'className' => 'UserFavorite',
      'foriegnkey' => 'user_id'
    ),
    'TeamPlayerHistory' => array(
      'className' => 'TeamPlayerHistory',
      'foriegnkey' => 'user_id'
    ),
    'FanClub' => array(
      'className' => 'FanClub',
      'foriegnkey' => 'user_id'
    ),
    'FanClubMember' => array(
      'className' => 'FanClubMember',
      'foriegnkey' => 'user_id'
    ),
    'RecommendedBy' => array(
      'className' => 'UserFriend',
      'foreignKey' => 'user_id'
    ),
    'RecommendedTo' => array(
      'className' => 'UserFriend',
      'foreignKey' => 'friend_id'
    ),
    'RoomMember' => array(
      'className' => 'RoomMember',
      'foreignKey' => 'user_id'
    ),
    'BowlerBowling' => array(
      'className' => 'MatchBallScore',
      'foreignKey' => 'bowler_id'
    ),
    'FielderForBall' => array(
      'className' => 'MatchBallScore',
      'foreignKey' => 'out_other_by_id'
    ),
    'StrikerBatsman' => array(
      'className' => 'MatchBallScore',
      'foreignKey' => 'striker_id'
    ),
    'NonStrikerBatsman' => array(
      'className' => 'MatchBallScore',
      'foreignKey' => 'non_striker_id'
    ),
    'BatsmanDismissed' => array(
      'className' => 'MatchBallScore',
      'foreignKey' => 'out_batsman_id'
    ),
    'Batsman' => array(
      'className' => 'MatchBatsmanScore',
      'foreignKey' => 'player_id'
    ),
    'BowlerBowling' => array(
      'className' => 'MatchBatsmanScore',
      'foreignKey' => 'out_by_id'
    ),
    'FielderFielding' => array(
      'className' => 'MatchBatsmanScore',
      'foreignKey' => 'out_other_by_id'
    ),
    'StrikeBowler' => array(
      'className' => 'MatchBowlerScore',
      'foreignKey' => 'user_id'
    ),
    'UmpireOne' => array(
      'className' => 'Match',
      'foreignKey' => 'first_umpire_id'
    ),
    'UmpireTwo' => array(
      'className' => 'Match',
      'foreignKey' => 'second_umpire_id'
    ),
    'UmpireThird' => array(
      'className' => 'Match',
      'foreignKey' => 'third_umpire_id'
    ),
    'UmpireReserve' => array(
      'className' => 'Match',
      'foreignKey' => 'reserve_umpire_id'
    ),
    'MatchReferee' => array(
      'className' => 'Match',
      'foreignKey' => 'referee_id'
    ),
    'ZooterBucket' => array(
      'className' => 'ZooterBucket',
      'foreignKey' => 'user_id'
    ),
    'Place' => array(
      'className' => 'Place',
      'foreignKey' => 'user_id'
    ),
    'PlaceCoach' => array(
      'className' => 'PlaceCoach',
      'foreignKey' => 'user_id'
    ),
    'PlaceReview' => array(
      'className' => 'PlaceReview',
      'foreignKey' => 'user_id'
    ),
    'PlaceRating' => array(
      'className' => 'PlaceRating',
      'foreignKey' => 'user_id'
    ),
    'FavoritePlace' => array(
      'className' => 'FavoritePlace',
      'foreignKey' => 'user_id'
    )
	);

  public $hasOne = array(
    'Profile' => array(
      'className' => 'Profile',
      'foreignKey' => 'user_id',
      'dependent' => false
    ),
    'PlayerProfile' => array(
      'className' => 'PlayerProfile',
      'foreignKey' => 'user_id',
    ),
  );

	public function beforeSave($options = array()) {
    if (isset($this->data[$this->alias]['password'])) {
      $passwordHasher = new SimplePasswordHasher();
      $this->data[$this->alias]['password'] = $passwordHasher->hash(
        $this->data[$this->alias]['password']
      );
    }
    return true;
	}

  public function loginUser($email, $username, $password) {
    $userExists = $this->__doesUserExist($email, $username);
    if ($userExists) {
      $user = $this->__getUser($email, $username);
      if (!empty($user)) {
        if ($this->__doesUserAuthenticate($password, $user['User']['password'])) {
          $userResponse = $this->__prepareUserResponse($user['User']['id']);
          $response = array('status' => 200, 'data' => $userResponse);
        } else {
          $response = array('status' => 906, 'message' => 'Password does not match');
        }
      } else {
        $response = array('status' => 905, 'message' => 'Username and email do not match');
      }
    } else {
      $response = array('status' => 904, 'message' => 'User does not exist');
    }
    return $response;
  }

  public function authenticateUser($email, $username, $password) {
    $userExists = $this->__doesUserExist($email, $username);
    if ($userExists) {
      $user = $this->__getUser($email, $username);
      if (!empty($user)) {
        if ($this->__doesUserAuthenticate($password, $user['User']['password'])) {
          $userResponse = $this->__prepareUserResponse($user['User']['id']);
          $response = array('status' => 200, 'data' => $userResponse);
        } else {
          $response = array('status' => 906, 'message' => 'Password does not match');
        }
      } else {
        $response = array('status' => 905, 'message' => 'Username and email do not match');
      }
    } else {
      $response = array('status' => 904, 'message' => 'User does not exist');
    }
    return $response;
  }


	public function registerUser($email, $username, $password, $phone = null, $typeId, $name = null,
                               $gender = null, $birthDate = null,$location_name, 
                               $location_latitude, $location_longitude, $location_unique_identifier) {
		$userExists = $this->__doesUserExist($email, $username);
		if (!$userExists) {
      $userNameExists = $this->__doesUsernameExist($username);
      if (!$userNameExists) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        // $locationId = $this->Profile->Location->saveLocation(
        //   $location_name,
        //   $location_latitude, 
        //   $location_longitude, 
        //   $location_unique_identifier
        // );
        // if ($locationId['status'] == 200) {
          // $locationId = $locationId['data'];
    			$apiKey = $this->ApiKey->generateApiKey();
          $data = $this->__createUserProfileData($email, $username, $password, $phone, $typeId, $name, $gender, $birthDate, $apiKey);
          $this->create();
    			if ($this->saveAssociated($data)) {
            $userId = $this->getLastInsertID();
            $userResponse = $this->__prepareUserResponse($userId);
            $mergeVars = array('name' => $name);
            $fromEmail = array('team@zooter.in' => 'Team Zooter');
            $toEmail = $email;
            $subject = 'Welcome to Zooter';
            $template = 'welcome-email';
            $this->TransactionalEmail = ClassRegistry::init('TransactionalEmail');
            $this->TransactionalEmail->queueTransactionEmail($fromEmail, $toEmail, $subject, $mergeVars, '', $template);
            $response = array('status' => 200, 'data' => $userResponse);
          } else {
            $response = array('status' => 903, 'message' => 'User not saved. Validation Errors');
          }
        // } else $response = array('status' => $locationId['status'], 'message' => $locationId['message']);

        if ($response['status'] == 200) {
          $dataSource->commit();
        } else $dataSource->rollback();

      } else {
        $response = array('status' => 902, 'message' => 'Username already exists');
      }
    } else {
      $response = array('status' => 901, 'message' => 'User already Registered');
    }
    return $response;
	}

  public function doSocialLogin($email, $facebookId, $facebookAccessToken, $firstName, $lastName, $typeId) {
    if ( ! $this->__doesUserExist($email)) {
      $apiKey = $this->ApiKey->generateApiKey();
      $data = $this->__createSocialProfileData($email, $apiKey, $facebookId, $facebookAccessToken, $firstName, $lastName, $typeId);
      $this->create();
      if ($this->saveAssociated($data)) {
        $userId = $this->getLastInsertID();
        $userResponse = $this->__prepareUserResponse($userId);
        $response = array('status' => 200, 'data' => $userResponse);
      } else {
        $response = array('status' => 903, 'message' => 'User not saved. Validation Errors');
      }
    } else {
      $user = $this->findByEmail($email);
      $userResponse = $this->__prepareUserResponse($user['User']['id']);
      $response = array('status' => 200, 'data' => $userResponse);
    }
    return $response;
  }
  public function doTwitterLogin($email, $twitterOauthKey, $twitterOauthSecret, $firstName, $lastName, $typeId) {
    if ( ! $this->__doesUserExist($email)) {
      $apiKey = $this->ApiKey->generateApiKey();
      $data = $this->__createTwitterProfileData($email, $apiKey, $twitterOauthKey, $twitterOauthSecret, $firstName, $lastName, $typeId);
      $this->create();
      if ($this->saveAssociated($data)) {
        $userId = $this->getLastInsertID();
        $userResponse = $this->__prepareUserResponse($userId);
        $response = array('status' => 200, 'data' => $userResponse);
      } else {
        $response = array('status' => 903, 'message' => 'User not saved. Validation Errors');
      }
    } else {
      $user = $this->findByEmail($email);
      $userResponse = $this->__prepareUserResponse($user['User']['id']);
      $response = array('status' => 200, 'data' => $userResponse);
    }
    return $response;
  }

  public function twitterUserExists($twitterOauthKey, $twitterOauthSecret) {
    $profile = $this->Profile->findByTwitterOauthKeyAndTwitterOauthSecret($twitterOauthKey, $twitterOauthSecret);
    if (!empty($profile)) {
      $userResponse = $this->__prepareUserResponse($profile['Profile']['user_id']);
      $response = array('status' => 200, 'data' => $userResponse);
    } else {
      $response = array('status' => 904, 'message' => 'User does not exist');
    }
    return $response;
  }

  public function ZooterVerification($twitterHandle) {
    App::uses('VerifiedUser', 'Model');
    $VerifiedUser = new VerifiedUser();
    $verifiedTwitterUser = $VerifiedUser->findByTwitterHandle($twitterHandle);
    if (!empty($verifiedTwitterUser)) {
      $response = array('status' => 200, 'message' => 'Twitter Handle Verification');
    } else {
      $response = array('status' => 904, 'message' => 'User does not exist');
    }
    return $response;
  }

  public function forgotPassword($email) {
    $userExists = $this->__doesUserExist($email);
    if ($userExists) {
      $password_reset = $this->__generateRandomString($length = 20);
      $user = $this->findByEmail($email);
      $this->id = $user['User']['id'];
      if ($this->saveField('password_reset', $password_reset)) {
        $responseData = array(
          'user_id' => $user['User']['id'],
          'password_reset_key' => $password_reset
        );
        $resetUrl = Configure::read('site_url') . 'users/reset_password/' . $responseData['user_id'] . '/' . $responseData['password_reset_key'];
        $mergeVars = array('forgot_password_link' => $resetUrl);
        $fromEmail = array('server@zooter.in' => 'Zooter Server');
        $toEmail = $email;
        $subject = 'Zooter - Forgot Password';
        $template = 'forgot-password';
        $this->TransactionalEmail = ClassRegistry::init('TransactionalEmail');
        $this->TransactionalEmail->queueTransactionEmail($fromEmail, $toEmail, $subject, $mergeVars, '', $template);
        $response = array('status' => 200, 'data' => $responseData);
      } else {
        $response = array('status' => 908, 'message' => 'Password reset key not generated');
      }
    } else {
      $response = array('status' => 907, 'message' => 'Email does not exist');
    }
    return $response;
  }
  public function isCorrectUser($userId, $passwordReset) {
    $user = $this->findById($userId);
    if ($user['User']['password_reset'] == $passwordReset) {
      $response = array('status' => 200, 'message' => 'Password Reset Key Verified');
    } else {
      $response = array('status' => 908, 'message' => 'User Id and Password reset key not matched');
    }
    return $response;
  }
  public function resetPassword($userId, $password) {
    $usersExists = $this->find('count', array('conditions' => array('User.id' => $userId)));
    if ($usersExists) {
      $data = array(
        'id' => $userId,
        'password' => $password,
        'password_reset' => null
      );
      if ($this->save($data)) { 
        $response = array('status' => 200, 'message' => 'Password Successfully Changed.');
      }
    } else {
      $response = array('status' => 904, 'message' => 'User does not exist');
    }
    return $response;
  }
  public function getNewsFeed($userId) {
    $newsFeeds = $this->WallPostSent->find('all', array(
      'conditions' => array(
        'WallPostSent.user_id' => $userId
      ),
      'fields' => array(
        'WallPostSent.id as postId, WallPostSent.post as zoot, WallPostSent.image as imageUrl, WallPostSent.user_id, WallPostSent.posted_on_id, WallPostSent.location_id, WallPostSent.wall_post_like_count'
      ),
      'contain' => array(
        'WallPostComment' => array('User' => 'Profile'),
        'WallPostLike'
      ),
      'order' => array('WallPostSent.created DESC')
    ));
    $profile = $this->Profile->findByUserId($userId);
    $name = $profile['Profile']['first_name'];
    foreach ($newsFeeds as $id => $newsFeed) {
      $newsFeeds[$id]['WallPostSent']['posted_by'] = $name;
    }
    $apiKey = $this->ApiKey->getLatestApiKeyForUser($userId);
    $response = array(
      'user_id' => $userId,
      'apiKey' => $apiKey,
      'news_feed' => $newsFeeds
    );
    $response = array('status' => 200, 'data' => $response);
    return $response;
  }
  public function autocomplete($userId, $searchWord) {
    $users = $this->Profile->find('all', array(
      'conditions' => array(
        'Profile.first_name LIKE' => '%' . $searchWord . '%',
        'NOT' => array(
          'Profile.user_id' => $userId
        )
      ),
      'contain' => array('Location')
    ));
    $response = array(
      'suggestions' => $users
    );
    $response = array('status' => 200, 'data' => $response);
    return $response;
  }

  private function __generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }

  private function __getUser($email, $username) {
    $user = array();
    if (empty($email)) {
      $user = $this->findByUsername($username);
    } else if (empty($username)) {
      $user = $this->findByEmail($email);
    } else {
      $user = $this->findByEmail($email);
      if ($user['User']['username'] != $username) {
        $user = array();
      }
    }
    return $user;
  }

  private function __doesUserAuthenticate($enteredPassword, $actualPassword) {
    $passwordHasher = new SimplePasswordHasher();
    $hashedPassword = $passwordHasher->hash($enteredPassword);
    return ($hashedPassword == $actualPassword);
  }

  private function __doesUserExist($email, $username = null) {
    if ($email) {
      return $this->find('count', array(
        'conditions' => array(
          'User.email' => $email,
        )
      ));
    } else if ($username) {
      return $this->find('count', array(
        'conditions' => array(
          'User.email' => $email,
        )
      ));
    } else if ($email && $username) {
      return $this->find('count', array(
        'conditions' => array(
          'User.email' => $email,
          'User.username' => $username,
        )
      ));
    }
    return false;
  }
  private function __doesUsernameExist($username) {
    if(!empty($username)) {
    return $this->find('count', array(
      'conditions' => array(
        'User.username' => $username
      )
    ));
   }
   return false;
  }

  private function __createUserProfileData($email, $username, $password, $phone = null, $typeId, $name = null, $gender = null, $birthDate = null, $apiKey = null, $locationId = null) {
    $data = array(
      'User' => array(
        'email' => $email,
        'username' => $username,
        'password' => $password,
        'phone' => $phone,
        'type_id' => $typeId
      ),
      'Profile' => array(
        'first_name' => $name,
        'gender' => $gender,
        'date_of_birth' => $birthDate,
        'location_id' => $locationId
      ),
      'ApiKey' => array(
        array(
          'api_key' => $apiKey
        )
      )
    );
    return $data;
  }
  public function __createSocialProfileData($email, $apiKey, $facebookId, $facebookAccessToken, $firstName, $lastName, $typeId) {
    $data = array(
      'User' => array(
        'email' => $email,
        'password' => $facebookId,
        'type_id' => $typeId
      ),
      'Profile' => array(
        'facebook_id' => $facebookId,
        'facebook_access_token' => $facebookAccessToken,
        'first_name' => $firstName,
        'last_name' => $lastName,
      ),
      'ApiKey' => array(
        array(
          'api_key' => $apiKey
        )
      )
    );
    return $data;
  }
  public function __createTwitterProfileData($email, $apiKey, $twitterOauthKey, $twitterOauthSecret, $firstName, $lastName, $typeId) {
    $data = array(
      'User' => array(
        'email' => $email,
        'password' => $twitterOauthKey,
        'type_id' => $typeId
      ),
      'Profile' => array(
        'twitter_oauth_key' => $twitterOauthKey,
        'twitter_oauth_secret' => $twitterOauthSecret,
        'first_name' => $firstName,
        'last_name' => $lastName,
      ),
      'ApiKey' => array(
        array(
          'api_key' => $apiKey
        )
      )
    );
    return $data;
  }

  private function __prepareUserResponse($userId) {
    $response = array();
    $user = $this->find('first', array(
      'conditions' => array(
        'User.id' => $userId
      ),
      'contain' => array(
        'Profile' => array(
          'fields' => array('id','first_name','middle_name','last_name','image_id','gender','date_of_birth'),
          'ProfileImage' => array(
            'fields' => array('id','url')
          )
        ),
        'Type' => array(
          'fields' => array('id','type')
        )
      )
    ));
    if (!empty($user)) {
      $apiKey = $this->ApiKey->getLatestApiKeyForUser($userId);
      if (!empty($apiKey)) {
        $name = $this->__prepareUserName($user['Profile']['first_name'],
                                              $user['Profile']['middle_name'],
                                              $user['Profile']['last_name']);
        if (!empty($user['Profile']['ProfileImage'])) {
          $image = $user['Profile']['ProfileImage']['url'];
        } else $image = null;
        $response = array(
          'id' => $userId,
          'email' => $user['User']['email'],
          'apiKey' => $apiKey,
          'name' => $name,
          'image_url' => $image,
          'gender' => $user['Profile']['gender'],
          'birthDate' => $user['Profile']['date_of_birth'],
          'type' => $user['Type']['type'],
        );
      }
    }
    return $response;
  }

  public function userExists($userId) {    
    return $this->find('count', array(
      'conditions' => array(
        'id' => $userId,
      )
    ));
  }

  public function prepareDashboard($userId){
    if (empty($userId)) {
      return array('status' => 100, 'message' => 'prepareDashboard : Invalid Input Arguments');
    }
    if (!$this->userExists($userId)) {
      return array('status' => 912, 'message' => 'prepareDashboard : User Does Not Exist');
    }
    $isTimeline = false;
    $newsFeed = $this->__getNewsFeed($userId,$isTimeline);
    $matchScheduler = $this->__getMatchScheduler($userId);
    $nearbyMatches = $this->__getNearByMatches($userId);
    $myTeams = $this->__getUserTeams($userId);
    $numOfSocialRecords = Limit::NUM_OF_USER_SOCIAL_RECORDS_IN_DASHBOARD;
    $friendsInfo = $this->__getFriendsInfo($userId,$numOfSocialRecords);
    $groupsInfo = $this->__getGroupsInfo($userId,$numOfSocialRecords);
    $fanclubsInfo = $this->__getFanclubsInfo($userId,$numOfSocialRecords);
    $userInfo = array();

    $dashboard = array(
      'newsfeed' => $newsFeed,
      'followed_matches' => $matchScheduler['following'],
      'recommended_matches' => $matchScheduler['recommended'],
      'nearby_matches' => $nearbyMatches,
      'my_teams' => $myTeams,
      'friends' => $friendsInfo,
      'groups' => $groupsInfo,
      'fanclubs' => $fanclubsInfo,
      'user' => $userInfo,
      'news' => array()
    ); 
    return array('status' => 200, 'data' => $dashboard);
  }

  public function getUserProfile($userId, $profileUserId) {
    if(!empty($userId)) {
      if($this->userExists($userId) && $this->userExists($profileUserId)) {
        $userDetails = $this->getUserDetails($profileUserId);
        $followers = $this->Follower->getFollowers($profileUserId);
        $following = $this->Follower->getFollowing($profileUserId);
        $reviews = $this->PlaceReview->getUserReviews($profileUserId);
        $favorites = $this->FavoritePlace->getFavoritePlaces($profileUserId);
        $photos = $this->Image->getPhotosByUser($profileUserId);
        $user = array(
          'id' => $userDetails['User']['id'],
          'name' => $userDetails['Profile']['first_name'] . ' ' . $userDetails['Profile']['middle_name'] . ' ' . $userDetails['Profile']['last_name'],
          'profile_image_url' => !empty($userDetails['Profile']['ProfileImage']['url']) ? $userDetails['Profile']['ProfileImage']['url'] : null,
          'follower_count' => count($followers),
          'following_count' => count($following),
          'reviews_count' => count($reviews)
        );
        $userDetails['Profile']['gender'] = (int)$userDetails['Profile']['gender'];
        $home = array(
          'description' => $userDetails['Profile']['description'],
          'basic_info' => array(
            'name' => $userDetails['Profile']['first_name'] . ' ' . $userDetails['Profile']['middle_name'] . ' ' . $userDetails['Profile']['last_name'],
            'gender' => !empty($userDetails['Profile']['gender']) ? GenderType::stringValue($userDetails['Profile']['gender']) : null,
            'birthday' => !empty($userDetails['Profile']['date_of_birth']) ? date('F j, Y', strtotime($userDetails['Profile']['date_of_birth'])) : null
          ),
          'contact_info' => array(
            'phone' => ($userId == $profileUserId) ? $userDetails['User']['phone'] : null,
            'email' => ($userId == $profileUserId) ? $userDetails['User']['email'] : null
          )
        );
        $profile = array(
          'user' => $user,
          'home' => $home,
          'forum' => array(
            'favorites' => $favorites,
            'reviews' => $reviews
          ),
          'connections' => array(
            'following' => $following,
            'followers' => $followers
          ),
          'photos' => $photos
        );
        $response = array('status' => 200, 'data' => $profile);
      } else {
          $response = array('status' => 912, 'message' => 'getUserProfile : User Does Not Exist');
      }
    } else {
        $response = array('status' => 100, 'message' => 'getUserProfile : Invalid Input Arguments');
    }
    return $response;
  }

  public function getUserDetails($profileUserId) {
    $user = $this->find('first', array(
      'conditions' => array(
        'User.id' => $profileUserId
      ),
      'fields' => array('id', 'phone', 'email'),
      'contain' => array(
        'Profile' => array(
          'fields' => array(
            'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth', 'description'
          ),
          'ProfileImage' => array(
            'fields' => array('id','url')
          ),
        )
      )
    ));
    return $user;
  }

  public function prepareUserProfile($userId,$profileUserId) {
    if(!empty($userId)) {
      if($this->userExists($userId) && $this->userExists($profileUserId)) {
        $userInfo = $this->getUserInfo($profileUserId);
        $friendshipStatus = $this->FriendFrom->getFriendShipStatus($userId,$profileUserId);
        $careerHighlights = $this->__getCareerHighlights($profileUserId);
        $isTimeline = true;
        $timeline = $this->__getNewsFeed($profileUserId,$isTimeline);
        $recentActivities = $this->__getRecentActivities($profileUserId);
        $recentMatches = $this->__getRecentMatches($profileUserId);
        $recentPhotos = $this->__getRecnetPhotos($profileUserId);

        $userProfile = array(
          'user' => $userInfo,
          'friendship_status' => $friendshipStatus,
          'career_highlights' => $careerHighlights,
          'timeline' => $timeline,
          'recent_activities' => $recentActivities,
          'recent_matches' => $recentMatches,
          'recent_photos' => $recentPhotos,
        ); 
        $response = array('status' => 200, 'data' => $userProfile);
      } else {
          $response = array('status' => 912, 'message' => 'prepareUserProfileHome : User Does Not Exist');
      }
    } else {
        $response = array('status' => 100, 'message' => 'prepareUserProfileHome : Invalid Input Arguments');
    }
    return $response;
  }

  public function getUserInfo($userId) {
    $user = $this->Profile->find('first',array(
      'conditions' => array(
        'Profile.user_id' => $userId 
      ),
      'fields' => array('id','first_name','middle_name','last_name','image_id','cover_image_id'),
      'contain' => array(
        'ProfileImage' => array(
          'fields' => array('id','url')
        ),
        'CoverImage' => array(
          'fields' => array('id','url')
        )
      )     
    ));
    $responseData['id'] = $userId;
    $responseData['name'] = $this->__prepareUserName($user['Profile']['first_name'],
                                                      $user['Profile']['middle_name'],
                                                      $user['Profile']['last_name']);
    $responseData['image_url'] = $user['ProfileImage']['url'];
    $responseData['cover_image_url'] = $user['CoverImage']['url'];
    return $responseData;
  }

  private function __getCareerHighlights($userId) {
    return $this->PlayerStatistic->getUserCareerHighlights($userId,PlayerStatisticsType::CAREER_HIGHLIGHTS);
  }

  private function __getTimeline($userId) {
    return $this->WallPostSent->getUserTimeline($userId);
  }

  private function __getRecentActivities($userId) {
    $activities = Cache::read('recent_activities_user_'.$userId);
    if (empty($activities)) {
      return array();
    }
    return $activities;
  }

  private function __getRecentMatches($userId) {
    $matches = Cache::read('recent_matches_user_'.$userId);
    if (empty($matches)) {
      return array();
    }
    return $matches;
  }

  private function __getRecnetPhotos($userId) {
    $photoes = Cache::read('recent_photos_user_'.$userId);
    if (empty($photoes)) {
      return array();
    }
    return $photoes;
  }

  public function prepareUserProfileAbout($userId,$profileUserId) {
    if(!empty($userId)) {
      if($this->userExists($userId) && $this->userExists($profileUserId)) {
        $numOfRecords = Limit::NUM_OF_USER_PROFILE_ABOUT_RECORDS;
        $userInfo = $this->getUserInfo($profileUserId);
        $friendshipStatus = $this->FriendFrom->getFriendShipStatus($userId,$profileUserId);
        $careerHighlights = $this->__getCareerHighlights($profileUserId);
        $personalInfo = $this->__getProfilePersonalInfo($profileUserId,$userId);
        $photos = $this->__getProfileAlbums($profileUserId,$numOfRecords);
        $videos = $this->__getProfileVideos($profileUserId,$numOfRecords);
        $timelinePhotoes = $this->__getTimelinePhotoes($profileUserId,$numOfRecords);
        //$favorites = $this->__getProfileFavorites($userId);
        $favorites = array('sports' => array(), 'movies' => array() ,'music' => array());

        $userProfileAbout = array(
          'user' => $userInfo,
          'friendship_status' => $friendshipStatus,
          'career_highlights' => $careerHighlights,
          'personal_info' => $personalInfo,
          'albums' => $photos,
          'timeline_photos' => $timelinePhotoes,
          'videos' => $videos,
          'favorites' => $favorites,
        ); 
        $response = array('status' => 200, 'data' => $userProfileAbout);
      } else {
          $response = array('status' => 912, 'message' => 'prepareUserProfileAbout : User Does Not Exists');
      }
    } else {
        $response = array('status' => 100, 'message' => 'prepareUserProfileAbout : Invalid Input Arguments');
    }
    return $response;
  }

  private function __getProfilePersonalInfo($profileUserId,$userId) {
    $personalInfoFromCache = Cache::read('personal_info_user_'.$profileUserId);
    if (!empty($personalInfoFromCache)) {
      return $personalInfoFromCache;
    }

    $personalInfo = array();
    $data = $this->find('first',array(
      'conditions' => array(
        'User.id' => $profileUserId
      ),
      'fields' => array('email','phone'),
      'contain' => array(
        'Profile' => array(
         'fields' => array('first_name','middle_name','last_name','date_of_birth','gender','location_id',
                            'facebook_id','twitter_handle','linkedin_handle','description'),
         'Location' => array(
           'fields' => array('id','name','city_id'),
           'City' => array(
             'fields' => array('id','name')
           )
        )
       )
      )
    ));
    
    $personalInfo['id'] = $profileUserId;
    if (!empty($profileUserId) && !empty($userId) && $profileUserId == $userId) {
      $personalInfo['email'] = $data['User']['email'];
      $personalInfo['mobile'] = $data['User']['phone'];
      $personalInfo['date_of_birth'] = $data['Profile']['date_of_birth'];
    }
    $personalInfo['name'] = $this->__prepareUserName($data['Profile']['first_name'],
                                                    $data['Profile']['middle_name'],
                                                    $data['Profile']['last_name']);
    $personalInfo['gender'] = $data['Profile']['gender'];
    $personalInfo['facebook_handle'] = $data['Profile']['facebook_id'];
    $personalInfo['twitter_handle'] = $data['Profile']['twitter_handle'];
    $personalInfo['linkedin_handle'] = $data['Profile']['linkedin_handle'];
    $personalInfo['description'] = $data['Profile']['description'];
    if (!empty($data['Profile']['Location'])) {
      $personalInfo['location']['id'] = $data['Profile']['Location']['id'];
      $personalInfo['location']['name'] = $data['Profile']['Location']['name'];
      if (!empty($data['Profile']['Location']['City'])) {
        $personalInfo['location']['city'] = $data['Profile']['Location']['City']['name'];
      } else $personalInfo['location']['city'] = null;
    } else {
        $personalInfo['location']['id'] = null;
        $personalInfo['location']['name'] = null;
        $personalInfo['location']['city'] = null;
    }

    return $personalInfo;
  }

  private function __getProfileAlbums($userId,$numOfRecords) {
    return $this->Album->getUserProfileAlbums($userId,$numOfRecords);
  }

  private function __getProfileVideos($userId,$numOfRecords) {
    return $this->Video->getUserProfileVideos($userId,$numOfRecords);
  }

  private function __getTimelinePhotoes($userId,$numOfRecords) {
    return $this->WallPostSent->getTimelinePhotoes($userId,$numOfRecords);
  }

  private function __getProfileFavorites($userId) {
    return $this->UserFavorite->getUserProfileFavorites($userId);
  }

  public function prepareUserProfileCareer($userId,$profileUserId) {
    if(!empty($userId)) {
      if($this->userExists($userId) && $this->userExists($profileUserId)) {
        $numOfRecords = Limit::NUM_OF_USER_PROFILE_CAREER_RECORDS;
        $userInfo = $this->getUserInfo($profileUserId);
        $friendshipStatus = $this->FriendFrom->getFriendShipStatus($userId,$profileUserId);
        $careerHighlights = $this->__getCareerHighlights($profileUserId);
        $graphData = $this->__getCareerGraphData($profileUserId);
        $playerInfo = $this->__getPlayerInfo($profileUserId);
        $myPlayedMatches = $this->__getMyPlayedMatches($profileUserId,$numOfRecords);
        $mybestMatches = $this->__getMyBestMatches($profileUserId,$numOfRecords);
        $myTeamsHistory = $this->__getTeamsHistory($profileUserId,$numOfRecords);
        $myAcademyHistory = array();//$this->__getAcademyHistory($profileUserId);
        $mySkillSet = array();//$this->__getSkillSet($profileUserId);

        $userProfileCareer = array(
          'user' => $userInfo,
          'friendship_status' => $friendshipStatus,
          'career_highlights' => $careerHighlights,
          'player_info' => $playerInfo,
          'performance_graph' => $graphData,
          'my_played_matches' => $myPlayedMatches,
          'my_best_matches' => $mybestMatches,
          'my_teams_history' => $myTeamsHistory,
          'my_academy_history' => $myAcademyHistory,
          'my_skill_set' => $mySkillSet
        ); 
        $response = array('status' => 200, 'data' => $userProfileCareer);
      } else {
          $response = array('status' => 912, 'message' => 'prepareUserProfileCareer : User Does Not Exists');
      }
    } else {
        $response = array('status' => 100, 'message' => 'prepareUserProfileCareer : Invalid Input Arguments');
    }
    return $response;
  }

  private function __getPlayerInfo($userId) {
    return $this->PlayerProfile->getPlayerInfo($userId);
  }

  private function __getCareerGraphData($userId) {
    $year = date('Y');
    $is_batting = true;
    $graphData = $this->MatchPlayerScorecard->getCareerPerformanceGraph($userId,$year,$is_batting);
    if ($graphData['status'] == 200) {
      return array('is_batting' => $is_batting, 'year' => $year, 'records' => $graphData['data']['records']);
    } else {
      return array('is_batting' => $is_batting, 'year' => $year, 'records' => array());
    }
  }

  private function __getMyPlayedMatches($userId,$numOfRecords) {
    $is_best = false;
    $myMatches = $this->MatchPlayerScorecard->getUserMyMatches($userId,$is_best,$numOfRecords);
    if ($myMatches['status'] == 200) {
      return $myMatches['data'];
    } else return array();
  }

  private function __getTeamsHistory($userId,$numOfRecords) {
    return $this->TeamPlayerHistory->getUserTeamsHistory($userId,$numOfRecords);
  }

  private function __getMyBestMatches($userId,$numOfRecords) {
    $is_best = true;
    $myBestMatches = $this->MatchPlayerScorecard->getUserMyMatches($userId,$is_best,$numOfRecords);
    if ($myBestMatches['status'] == 200) {
      return $myBestMatches['data'];
    } else return array();
  }

  public function prepareUserProfileFriendsAndGroups($userId,$profileUserId) {
    if(!empty($userId)) {
      if($this->userExists($userId) && $this->userExists($profileUserId)) {
        $numOfRecords = Limit::NUM_OF_USER_PROFILE_SOCIAL_RECORDS;
        $userInfo = $this->getUserInfo($profileUserId);
        $friendshipStatus = $this->FriendFrom->getFriendShipStatus($userId,$profileUserId);
        $careerHighlights = $this->__getCareerHighlights($profileUserId);
        $friendsInfo = $this->__getFriendsInfo($profileUserId, $numOfRecords);
        $groupsInfo = $this->__getGroupsInfo($profileUserId,$numOfRecords);
        $fanclubsInfo = $this->__getFanclubsInfo($profileUserId,$numOfRecords);

        $userProfileFriendsAndGroups = array(
          'user' => $userInfo,
          'friendship_status' => $friendshipStatus,
          'career_highlights' => $careerHighlights,
          'friends_info' => $friendsInfo,
          'groups_info' => $groupsInfo,
          'fanclubs_info' => $fanclubsInfo,
        ); 
        $response = array('status' => 200, 'data' => $userProfileFriendsAndGroups);
      } else {
          $response = array('status' => 912, 'message' => 'prepareUserProfileFriendsAndGroups : User Does Not Exists');
      }
    } else {
        $response = array('status' => 100, 'message' => 'prepareUserProfileFriendsAndGroups : Invalid Input Arguments');
    }
    return $response;
  }

  private function __getNearByMatches($userId) {
    $numOfRecords = Limit::NUM_OF_USER_NEARBY_MATCHES;
    $result = array();
    $data = $this->Match->getUserNearByMatches($userId,$numOfRecords);
    if ($data['status'] == 200) {
      $result = $data['data'];
    } 
    return $result;
  }

  private function __getUserTeams($userId) {
    $numOfRecords = Limit::NUM_OF_USER_OWNED_TEAMS;
    $result = array();
    $data =  $this->Team->getTeamsOwnedByUser($userId,$numOfRecords);
    if ($data['status'] == 200) {
      $result = $data['data'];
    } 
    return $result;
  }

  private function __getFriendsInfo($userId,$numOfRecords) {
    $result = array();
    $data =  $this->FriendFrom->getFriendslist($userId,$numOfRecords,null);
    if ($data['status'] == 200) {
      $result = $data['data'];
    } 
    return $result;
  }

  private function __getGroupsInfo($userId,$numOfRecords) {
    $result = array();
    $data =  $this->UserGroup->getUserGroups($userId,$numOfRecords,null);
    if ($data['status'] == 200) {
      $result = $data['data'];
    } 
    return $result;
  }

  private function __getFanclubsInfo($userId,$numOfRecords) {
    $result = array();
    $data = $this->FanClubMember->getUserFanClubs($userId,$numOfRecords,null);
    if ($data['status'] == 200) {
      $result = $data['data'];
    } 
    return $result;
  }

  public function searchBox($userId,$searchInput,$numOfRecords) {
    $searchInput = trim($searchInput);
    $numOfRecords = trim($numOfRecords);
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_RECORDS_IN_SEARCH_BOX;
    }
    if (empty($userId) || empty($searchInput) ) {
      return array('status' => 100 , 'message' => 'searchBox : Invalid Input Arguments For Search');
    }
    if (!$this->userExists($userId)) {
      return array('status' => 332 , 'message' => 'searchBox : User Does not exist');
    } 

    $searchData = array();
    $matchData = $this->Match->searchMatches($userId,$searchInput,MatchSearchType::NAME,$numOfRecords);
    if ($matchData['status'] != 100) {
      $searchData['matches'] = $matchData['data'];
    } else return array('status' => $matchData['status'], 'message' => $matchData['message']);

    $teamData = $this->Team->searchTeams($userId,$searchInput,TeamSearchType::NAME,$numOfRecords);
    if ($teamData['status'] != 100) {
      $searchData['teams'] = $teamData['data'];
    } else return array('status' => $teamData['status'], 'message' => $teamData['message']);

    $userData = $this->searchUser($searchInput,$numOfRecords);
    if ($userData['status'] != 100) {
      $searchData['people'] = $userData['data'];
    } else return array('status' => $userData['status'], 'message' => $userData['message']);

    $groupData = $this->Group->searchGroup($searchInput,$numOfRecords);
    if ($groupData['status'] != 100) {
      $searchData['groups'] = $groupData['data'];
    } else return array('status' => $groupData['status'], 'message' => $groupData['message']);

    $fanclubData = $this->FanClub->searchFanClub($searchInput,$numOfRecords);
    if ($fanclubData['status'] != 100) {
      $searchData['fanclubs'] = $fanclubData['data'];
    } else return array('status' => $fanclubData['status'], 'message' => $fanclubData['message']);

    $friendsData = $this->searchUserFriends($userId,$searchInput,$numOfRecords);
    if ($friendsData['status'] != 100) {
      $searchData['friends'] = $friendsData['data'];
    } else return array('status' => $friendsData['status'], 'message' => $friendsData['message']);
    
    return array('status' => 200, 'data' => $searchData);
  }

  public function searchSocial($userId,$searchInput,$numOfRecords) {
    $searchInput = trim($searchInput);
    $numOfRecords = trim($numOfRecords);
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_USER_PROFILE_SOCIAL_RECORDS;
    }
    if (empty($userId) || empty($searchInput) ) {
      return array('status' => 100 , 'message' => 'searchSocial : Invalid Input Arguments For Search');
    }
    if (!$this->userExists($userId)) {
      return array('status' => 332 , 'message' => 'searchSocial : User Does not exist');
    } 
    $searchData = array();

    $friendsData = $this->searchUserFriends($userId,$searchInput,$numOfRecords);
    if ($friendsData['status'] != 100) {
      $searchData['friends'] = $friendsData['data'];
    } else return array('status' => $friendsData['status'], 'message' => $friendsData['message']);

    $groupData = $this->Group->searchGroup($searchInput,$numOfRecords);
    if ($groupData['status'] != 100) {
      $searchData['groups'] = $groupData['data'];
    } else return array('status' => $groupData['status'], 'message' => $groupData['message']);

    $fanclubData = $this->FanClub->searchFanClub($searchInput,$numOfRecords);
    if ($fanclubData['status'] != 100) {
      $searchData['fanclubs'] = $fanclubData['data'];
    } else return array('status' => $fanclubData['status'], 'message' => $fanclubData['message']);
    
    return array('status' => 200, 'data' => $searchData);

  }

  public function searchUserFriends($userId,$input,$numOfRecords) {
    $input= trim($input);
    if (empty($input)) {
      return array('status' => 100, 'message' => 'searchUserFriends : Invalid Input Arguments');
    }
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_RECORDS_IN_SEARCH_FRIENDS;
    }
    $friendsSearchData = array();
    $friendsId = $this->FriendFrom->getUserFriendsIdList($userId);
    if (empty($friendsId)) {
      return array('status' => 340, 'data' => $friendsSearchData, 'message' => 'searchUserFriends : No Friends exist For User');
    }
    $friendsData = $this->find('all',array(
      'conditions' => array(
        'User.id' => $friendsId,
        'OR' => array(
          'email LIKE' => "%$input%",
          'username LIKE' => "%$input%",
          'Profile.first_name LIKE' => "%$input%",
          'Profile.middle_name LIKE' => "%$input%",
          'Profile.last_name LIKE' => "%$input%"
        )        
      ),
      'fields' => array('id','email','username'),
      'contain' => array(
        'Profile' => array(
          'fields' => array('first_name','middle_name','last_name','image_id','location_id'),
          'Location' => array(
            'fields' => array('id','name','city_id'),
            'City' => array(
              'fields' => array('id','name')
            )
          ),
          'ProfileImage' => array(
            'fields' => array('id','url')
          )
        ),
        'PlayerProfile' => array(
          'fields' => array('batting_arm','bowling_arm','bowling_style','playing_roles')
        )
      ),
      'limit' => $numOfRecords
    ));
    foreach ($friendsData as $index => $data) {
      $name = $this->__prepareUserName($data['Profile']['first_name'],
                                       $data['Profile']['middle_name'],
                                       $data['Profile']['last_name']);
      if (!empty($data['Profile']['ProfileImage'])) {
        $image = $data['Profile']['ProfileImage']['url'];
      } else $image = null;

      $friendsSearchData[$index]['id'] = $data['User']['id'];
      $friendsSearchData[$index]['email'] = $data['User']['email'];
      $friendsSearchData[$index]['username'] = $data['User']['username'];
      $friendsSearchData[$index]['name'] = $name;
      $friendsSearchData[$index]['first_name'] = $data['Profile']['first_name'];
      $friendsSearchData[$index]['middle_name'] = $data['Profile']['middle_name'];
      $friendsSearchData[$index]['last_name'] = $data['Profile']['last_name'];
      $friendsSearchData[$index]['image'] = $image;
      if (!empty($data['Profile']['Location'])) {
        $friendsSearchData[$index]['location']['id'] = $data['Profile']['Location']['id'];
        $friendsSearchData[$index]['location']['name'] = $data['Profile']['Location']['name'];
      } else {
        $friendsSearchData[$index]['location']['id'] = null;
        $friendsSearchData[$index]['location']['name'] = null;
      }
      if (!empty($data['Profile']['Location']['City'])) {
        $friendsSearchData[$index]['location']['city'] = $data['Profile']['Location']['City']['name'];
      } else {
        $friendsSearchData[$index]['location']['city'] = null;
      }

      if (!empty($data['PlayerProfile'])) {
        $friendsSearchData[$index]['batting_style'] = PlayerProfileType::stringValue($data['PlayerProfile']['batting_arm']).' Hand Bat';
        $friendsSearchData[$index]['bowling_style'] = PlayerProfileType::stringValue($data['PlayerProfile']['bowling_arm']).' Arm '.PlayerProfileType::stringValue($data['PlayerProfile']['bowling_style']);
        $friendsSearchData[$index]['role'] = PlayerRole::stringValue($data['PlayerProfile']['playing_roles']);
      } else {
        $friendsSearchData[$index]['batting_style'] = null;
        $friendsSearchData[$index]['bowling_style'] = null;
        $friendsSearchData[$index]['role'] = null;
      }
    }
    return array('status' => 200 , 'data' => $friendsSearchData);
  }

  public function searchUser($input,$numOfRecords) {
    $input = trim($input);
    if (empty($input)) {
      return array('status' => 100, 'message' => 'searchUser : Invalid Input Arguments');
    }
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_RECORDS_IN_SEARCH_USERS;
    }
    $userData = array();
    $users = $this->find('all',array(
      'conditions' => array(
        'OR' => array(
          'User.username LIKE' => "%$input%",
          'User.email LIKE' => "%$input%",
          'Profile.first_name LIKE' => "%$input%",
          'Profile.middle_name LIKE' => "%$input%",
          'Profile.last_name LIKE' => "%$input%",
        )       
      ),
      'fields' => array('id','email','username'),
      'contain' => array(
        'Profile' => array(
          'fields' => array('first_name','middle_name','last_name','image_id','location_id'),
          'ProfileImage' => array(
            'fields' => array('id','url')
          ),
          'Location' => array(
            'fields' => array('id','name','city_id'),
            'City' => array(
              'fields' => array('id','name')
            )
          ),
        ),
        'PlayerProfile' => array(
          'fields' => array('batting_arm','bowling_arm','bowling_style','playing_roles')
        )
      ),
      'limit' => $numOfRecords
    ));
    foreach ($users as $index => $user) {
      if (!empty($user['Profile']['ProfileImage'])) {
        $image = $user['Profile']['ProfileImage']['url'];
      } else $image = null;

      $name = $this->__prepareUserName($user['Profile']['first_name'],
                                       $user['Profile']['middle_name'],
                                         $user['Profile']['last_name']);

      $userData[$index]['id'] = $user['User']['id'];
      $userData[$index]['email'] = $user['User']['email'];
      $userData[$index]['username'] = $user['User']['username'];
      $userData[$index]['name'] = $name;
      $userData[$index]['first_name'] = $user['Profile']['first_name'];
      $userData[$index]['middle_name'] = $user['Profile']['middle_name'];
      $userData[$index]['last_name'] = $user['Profile']['last_name'];
      $userData[$index]['image'] = $image;
      if (!empty($user['Profile']['Location'])) {
        $userData[$index]['location']['id'] = $user['Profile']['Location']['id'];
        $userData[$index]['location']['name'] = $user['Profile']['Location']['name'];
      } else {
        $userData[$index]['location']['id'] = null;
        $userData[$index]['location']['name'] = null;
      }
      
      if (!empty($user['Profile']['Location']['City'])) {
        $userData[$index]['location']['city'] = $user['Profile']['Location']['City']['name'];
      } else {
        $userData[$index]['location']['city'] = null;
      }
      if (!empty($user['PlayerProfile'])) {
        $userData[$index]['batting_style'] = PlayerProfileType::stringValue($user['PlayerProfile']['batting_arm']).' Hand Bat';
        $userData[$index]['bowling_style'] = PlayerProfileType::stringValue($user['PlayerProfile']['bowling_arm']).' Arm '.PlayerProfileType::stringValue($user['PlayerProfile']['bowling_style']);
        $userData[$index]['role'] = PlayerRole::stringValue($user['PlayerProfile']['playing_roles']);
      } else {
        $userData[$index]['batting_style'] = null;
        $userData[$index]['bowling_style'] = null;
        $userData[$index]['role'] = null;
      }
    }
    return array('status' => 200 , 'data' => $userData);
  }

  public function filterFriendsGroupsFanclubs($userId,$filterLike,$numOfRecords) {
    $filterLike = trim($filterLike);
    $numOfRecords = trim($numOfRecords);
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_USER_PROFILE_SOCIAL_RECORDS;
    }
    if (empty($userId) || empty($filterLike) ) {
      return array('status' => 100 , 'message' => 'filterFriendsGroupsFanclubs : Invalid Input Arguments For filter');
    }
    if (!$this->userExists($userId)) {
      return array('status' => 332 , 'message' => 'filterFriendsGroupsFanclubs : User Does not exist');
    } 
    $filteredData = array();

    $data =  $this->FriendFrom->getFriendslist($userId,$numOfRecords,$filterLike);
    if ($data['status'] == 200) {
      $filteredData['friends'] = $data['data']['friends'];
    } else {
      $filteredData['friends'] = array();
    }
   
    $data =  $this->UserGroup->getUserGroups($userId,$numOfRecords,$filterLike);
    if ($data['status'] == 200) {
      $filteredData['groups'] = $data['data']['groups'];
    } else {
      $filteredData['groups'] = array();
    }
    
    $data = $this->FanClubMember->getUserFanClubs($userId,$numOfRecords,$filterLike);
    if ($data['status'] == 200) {
      $filteredData['fanclubs'] = $data['data']['fanclubs'];
    } else {
      $filteredData['fanclubs'] = array();
    }
   return array('status' => 200 , 'data' => $filteredData);
  }

  public function getFriendsInfo($userId, $numOfRecords) {
    $friendsFromCache = Cache::read('friends_user_'.$userId);
    if (!empty($friendsFromCache)) {
      return $friendsFromCache;
    }
    if (empty($numOfRecords)) {
      $numOfRecords = NUM_OF_USER_FRIENDS;
    }

    $friendsData = array();
    $friendsIdsList = $this->FriendFrom->getUserFriendsIdList($userId);
    if (!empty($friendsIdsList)) {
      $friends = $this->find('all',array(
        'conditions' => array(
          'User.id' => $friendsIdsList
        ),
        'fields' => array('id'),
        'contain' => array(
          'Profile' => array(
            'fields' => array('first_name','middle_name','last_name','image_id'),
            'ProfileImage' => array(
              'fields' => array('url')
            )
          )
        ),
        'limit' => $numOfRecords
      ));
      foreach ($friends as $key => $friend) {
        $friendsData[$key]['id'] = $friend['User']['id'];
        $friendsData[$key]['name'] = $this->__prepareUserName($friend['Profile']['first_name'],
                                                               $friend['Profile']['middle_name'],
                                                                $friend['Profile']['last_name']);
        if (!empty($friend['Profile']['ProfileImage'])) {
          $friendsData[$key]['image'] = $friend['Profile']['ProfileImage']['url'];
        }
        else {
          $friendsData[$key]['image'] = NULL;
        }
      }
    }
    return $friendsData;   
  }

  public function updateLastSeenNotifcationTime($userId,$date) {
    $date = trim($date);
    if (empty($userId) || empty($date)) {
      return array('status' => 100, 'message' => 'updateLastSeenNotifcationTime : Invalid Input Arguments');
    }
    $data = array(
      'id' => $userId,
      'last_seen_notification_time' => $date 
    );
    if ($this->save($data)) {
      $responseData = array('id' => $userId, 'last_seen_notification_time' => $date);
      return array('status' => 200, 'data' => $responseData, 'message' => 'success');
    } else return array('status' => 101, 'message' => 'updateLastSeenNotifcationTime : Data Could not be saved');
  }

  public function updateLastSeenRequestTime($userId,$date) {
    $date = trim($date);
    if (empty($userId) || empty($date)) {
      return array('status' => 100, 'message' => 'updateLastSeenRequestTime : Invalid Input Arguments');
    }
    $data = array(
      'id' => $userId,
      'last_seen_request_time' => $date 
    );
    if ($this->save($data)) {
      $responseData = array('id' => $userId, 'last_seen_request_time' => $date);
      return array('status' => 200, 'data' => $responseData, 'message' => 'success');
    } else return array('status' => 101, 'message' => 'updateLastSeenRequestTime : Data Could not be saved');
  }

  public function updateLastSeenMessageTime($userId,$date) {
    $date = trim($date);
    if (empty($userId) || empty($date)) {
      return array('status' => 100, 'message' => 'updateLastSeenMessageTime : Invalid Input Arguments');
    }
    $data = array(
      'id' => $userId,
      'last_seen_message_time' => $date 
    );
    if ($this->save($data)) {
      $responseData = array('id' => $userId, 'last_seen_message_time' => $date);
      return array('status' => 200, 'data' => $responseData, 'message' => 'success');
    } else return array('status' => 101, 'message' => 'updateLastSeenMessageTime : Data Could not be saved');
  }

  public function getUserNearByLocations($userId) {
    $userId = trim($userId);
    if (empty($userId)) {
      return array('status' => 100, 'message' => 'getUserNearByLocations : Invalid Input Arguments');
    }
    if(!$this->userExists($userId)) {
      return array('status' => 912, 'message' => 'getUserNearByLocations : User does not exist');
    }
    $location = $this->getUserLocation($userId);
    if ($location['status'] == 200) {
      if (!empty($location['data']['latitude']) && !empty($location['data']['longitude'])) {
        $location = $location['data'];
      } else return array('status' => 443,'message' => 'getUserNearByLocations : Invalid Latitude or Longiude for User Location');     
    } else return array('status' => $location['status'],'message' => $location['message']);
    $locations = $this->Match->Location->getNearbyLocation($location['latitude'],
                                                          $location['longitude'],
                                                          Limit::USER_NEAR_BY_LOCATION_DISTANCE);
    if ($locations['status'] == 200) {
      $locations = $locations['data'];
      return array('status' => 200, 'data' => $locations);
    } else return array('status' => 200, 'message' => $locations['message']);
    
  }

  public function getUserLocation($userId) {
    $userId = trim($userId);
    if (empty($userId)) {
      return array('status' => 100, 'message' => 'getUserLocation : Invalid Input Arguments');
    }
    if(!$this->userExists($userId)) {
      return array('status' => 912, 'message' => 'getUserLocation : User does not exist');
    }
    $response = array();
    $location = array();
    $data = $this->find('first',array(
      'conditions' => array(
        'User.id' => $userId
      ),
      'fields' => array('id'),
      'contain' => array(
        'Profile' => array(
          'fields' => array('id','location_id'),
          'Location' => array(
            'fields' => array('id','name','latitude','longitude')
          )
        )
      )
    ));
    if (!empty($data['Profile']['Location'])) {
      $location['id'] = $data['Profile']['Location']['id'];
      $location['name'] = $data['Profile']['Location']['name'];
      $location['latitude'] = $data['Profile']['Location']['latitude'];
      $location['longitude'] = $data['Profile']['Location']['longitude'];
      $cityName = $this->Profile->Location->getCityNameFromLocation($location['latitude'],$location['longitude']);
      if ($cityName['status'] != 100) {
        $location['city_name'] = $cityName['data'];
      } else $location['city_name'] = null;
      $response = array('status' => 200 , 'data' => $location);
    } else $response = array('status' => 334 , 'data' => 'getUserLocation : user Location does not exist');

    return $response;
  }

  private function __getNewsFeed($userId,$isTimeline) {
    $numOfStatusWalls = Limit::NUM_OF_STATUS_WALLS;
    $numOfTotalWalls = Limit::TOTAL_NUM_OF_WALLS;
    return $this->WallPostSent->getUserNewsFeed($userId,$numOfStatusWalls,$numOfTotalWalls,$isTimeline);
  }

  private function __getMatchScheduler($userId) {
    $matchSchedulerData = array();
    $numOfRecords = Limit::NUM_OF_MATCH_SCHEDULER_RECORDS;
    $followedMatches = $this->Match->MatchFollower->fetchMatchesFollowedByUser($userId,$numOfRecords);
    if ($followedMatches['status'] == 200) {
      $matchSchedulerData['following'] = $followedMatches['data'];
    } else $matchSchedulerData['following'] = null;
    $recommendedMatches = $this->MatchRecommendation->getRecommendedMatches($userId,$numOfRecords); 
    if ($recommendedMatches['status'] == 200) {
      $matchSchedulerData['recommended'] = $recommendedMatches['data'];
    } else $matchSchedulerData['recommended'] = null;
    
    return $matchSchedulerData;
  }

  public function prepareNotificationArea($userId) {
    if (empty($userId)) {
      return array('status' => 100, 'message' => 'prepareNotificationArea : Invalid Input Arguments');
    }
    if (!$this->userExists($userId)) {
      return array('status' => 112, 'message' => 'prepareNotificationArea : User Does not exist');
    }
    $user = $this->findById($userId); 
    $notificationArea = array();

    $lastSeenMessageTime = $user['User']['last_seen_message_time'];
    $lastSeenReminderTime = $user['User']['last_seen_reminder_time'];
    $lastSeenRequestTime = $user['User']['last_seen_request_time'];
    $lastSeenNotificationTime = $user['User']['last_seen_notification_time'];

    $requests = $this->UserCreatedRequest->fetchRequests($userId,$lastSeenRequestTime);
    $notificationArea['requests'] = $requests;

    $reminders = $this->UserToBeReminded->fetchReminders($userId);
    $notificationArea['reminders'] = $reminders;

    $notifications = $this->Notification->fetchNotifications($userId ,$lastSeenNotificationTime);
    $notificationArea['notifications'] = $notifications;

    $notificationArea['messages'] = array();

    return array('status' => 200, 'data' => $notificationArea);
  }

  private function __getFanClubsFollowedByUser($userId) {
    $userFanClubs = $this->FanClubFollower->find('list',array(
      'conditions' => array('user_id' => $userId),
      'fields' => array('fan_club_id')
    ));
    return $userFanClubs;
  }

  private function _getUserTeams($userId) {
    $userteams = $this->TeamFollower->find('list',array(
      'conditions' => array('user_id' => $userId),
      'fields' => array('team_id')
    ));
    return $userteams;
  }

  private function __getMatchesFollowedByUser($userId) {
    $userMaches = $this->MatchFollower->find('list',array(
      'conditions' => array('user_id' => $userId),
      'fields' => array('match_id')
    ));
    return $userMaches;
  }


  private function __getLikedByData($wallPost) {
    $likeList = array();
    foreach ($wallPost['WallPostLike'] as $wallPostLike) {
      $likeData = array(
        'id' => $wallPostLike['id'],
        'user' => array('id' => $wallPostLike['User']['id'], 'name' => $wallPostLike['User']['Profile']['first_name'])
      );
      array_push($likeList,$likeData);
    }
    return $likeList;
  }

  private function __getCommentedByData($wallPost) {
    $commentList = array();
    foreach ($wallPost['WallPostComment'] as $wallPostComment) {
      $commentData = array(
        'id' => $wallPostComment['id'],
        'user' => array('id' => $wallPostComment['User']['id'], 'name' => $wallPostComment['User']['Profile']['first_name']),
        'comment_text' => $wallPostComment['comment']
      );
      array_push($commentList,$commentData);
    }
    return $commentList;
  }

  public function __prepareUserName($firstName, $middleName, $lastName){
    $name = NULL;
    if (!empty($firstName)) {
      $name = $firstName." ";
    }
    if (!empty($middleName)) {
      $name = $name.$middleName." ";
    }
    if (!empty($lastName)) {
      $name = $name.$lastName." ";
    }
    return $name;
  }

  public function isZooterAuthorisedDataCollector($userId) {
    $user = $this->find('first', array(
      'conditions' => array(
        'User.id' => $userId
      ),
      'contain' => array(
        'Type'
      )
    ));
    if ($user['Type']['type'] == 'Data Collector') {
      return true;
    }
    return false;
  }

  public function getUserDetailsToEdit($userId, $profileId) {
    $userExists = $this->find('count', array('conditions' => array('User.id' => $userId)));
    if ($userExists) {
      $user = $this->find('first', array(
        'conditions' => array(
          'User.id' => $userId
        ),
        'fields' => array('id', 'phone'),
        'contain' => array(
          'Profile' => array(
            'fields' => array('id', 'first_name', 'gender', 'date_of_birth', 'description'),
            'ProfileImage' => array(
              'fields' => array('id', 'url', 'caption')
            )
          )
        )
      ));
      $data = array(
        'id' => $user['User']['id'],
        'phone' => !empty($user['User']['phone']) ? $user['User']['phone'] : null,
        'name' => $user['Profile']['first_name'],
        'gender' => !empty($user['Profile']['gender']) ? $user['Profile']['gender'] : null,
        'date_of_birth' => !empty($user['Profile']['date_of_birth']) ? date('d F, Y', strtotime($user['Profile']['date_of_birth'])) : null,
        'description' => !empty($user['Profile']['description']) ? $user['Profile']['description'] : null,
        'profile_image_url' => !empty($user['Profile']['ProfileImage']['url']) ? $user['Profile']['ProfileImage']['url'] : null
      );
      $response = array('status' => 200, 'message' => 'success', 'data' => $data);
    } else {
      $response = array('status' => 404, 'message' => 'User Does Not Exist');    
    }
    return $response;
  }

  public function editProfile($id, $name, $gender, $dateOfBirth, $phone, $description, $image) {
    $user = $this->find('first', array(
      'conditions' => array(
        'User.id' => $id
      ),
      'fields' => array('id'),
      'contain' => array(
        'Profile' => array(
          'fields' => array('id'),
          'ProfileImage' => array(
            'fields' => array('id', 'url')
          )
        )
      )
    ));
    if (!empty($user)) {
      if (!empty($user['Profile']['ProfileImage'])) {
        $imageData = array(
          'id' => $user['Profile']['ProfileImage']['id'],
          'url' => !empty($image) ? $image : $user['Profile']['ProfileImage']['url']
        );
      } else if (empty($user['Profile']['ProfileImage']) && !empty($image)) {
        $imageData = array(
          'url' => $image
        );
      } else {
        $imageData = array();
      }
      $data = array(
        'User' => array(
          'id' => $id,
          'phone' => $phone,
        ),
        'Profile' => array(
          'id' => $user['Profile']['id'],
          'first_name' => $name,
          'gender' => trim($gender),
          'date_of_birth' => date('Y-m-d', strtotime($dateOfBirth)),
          'phone' => $phone,
          'description' => $description,
          'ProfileImage' => $imageData
        )
      );
      if ($this->saveAssociated($data, array('deep' => true))) {
        $response = array('status' => 200, 'message' => 'success', 'data' => $data);
      }
    } else {
      $response = array('status' => 404, 'message' => 'User Does Not Exist');
    }
    return $response;
  }
}
