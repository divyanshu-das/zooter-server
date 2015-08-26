<?php
App::uses('AppModel', 'Model');
App::uses('Match', 'Model');
App::uses('WallPostType', 'Lib/Enum');
App::uses('MatchTeamStatus', 'Lib/Enum');
App::uses('MatchScorecardInning', 'Lib/Enum');
App::uses('NotificationType', 'Lib/Enum');
App::uses('MatchTossType', 'Lib/Enum');
App::uses('Limit', 'Lib/Enum');
App::uses('WallPostCategory', 'Lib/Enum');
/**
 * WallPost Model
 *
 * @property User $User
 * @property Location $Location
 */
class WallPost extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PostedBy' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PostedOn' => array(
			'className' => 'User',
			'foreignKey' => 'posted_on_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
    'Image' => array(
      'className' => 'Image',
      'foreignKey' => 'image_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'Video' => array(
      'className' => 'Video',
      'foreignKey' => 'video_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'Room' => array(
      'className' => 'Room',
      'foreignKey' => 'room_id',
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
 		'WallPostComment' => array(
  		'className' => 'WallPostComment',
  		'foreignKey' => 'wall_post_id'
  	),
  	'WallPostLike' => array(
  		'className' => 'WallPostLike',
  		'foreignKey' => 'wall_post_id'
  	),
	);

  //App::import('Model','Match');
  //public $match = array();
  //public $var1= new Match();

	public function saveZoot($category, $type, $userId, $zoot = null, $location = null,$imageUrl = null,
                              $video = null, $posted_on_id = null, $hyperLink = null) {
    $category = trim($category);
    $type = trim($type);
    $zoot = trim($zoot);
    $imageUrl = trim($imageUrl);
    $hyperLink = trim($hyperLink);
    $userId = trim($userId);
    $videoData = array();

    if (empty($category) || empty($type) || empty($userId) || (empty($zoot) && empty($imageUrl) && empty($video))) {
      return array('status' => 100, 'message' => 'WallPost saveZoot : Invalid Input Arguments');
    }
    if (!$this->__wallPostCategoryExists($category)) {
      return array('status' => 100, 'saveZoot : Invalid Value for Wall Post Category');
    }
    if (!$this->__wallPostTypeExists($type)) {
      return array('status' => 100, 'saveZoot : Invalid Value for Wall Post Type');
    }
    if (!$this->__isvalidPostedOnForCategory($category,$posted_on_id)) {
      return array('status' => 100, 'saveZoot : Invalid Value for Posted_on_id for Wall Post Category');
    }
    if (!$this->_userExists($userId)) {
      return array('status' => 812 , 'message' => 'saveZoot : User does not exist');
    }
    if (!empty($video)) {
      if (!empty($video['name']) &&  !empty($video['url']) && !empty($video['size'])) {
        $videoData = array(
          'name' => trim($video['name']), 
          'url' => trim($video['url']),
          'size' => trim($video['size']),
        );
        if (!empty($video['caption'])) {
          $videoData['caption'] = trim($video['caption']);
        }
        if (!empty($video['mime_type'])) {
          $videoData['mime_type'] = trim($video['mime_type']);
        }
        if (!empty($video['video_date_time'])) {
          $videoData['video_date_time'] = trim($video['video_date_time']);
        }
      } else {
        return array('status' => 100, 'message' => 'WallPost saveZoot : Invalid Input Arguments for Video');
      }
    }
    if (!$this->PostedBy->userExists($userId)) {
      return array('status' => 812 , 'message' => 'WallPost saveZoot : User does not exist');
    }

		$locationId = null;
    if (!empty($location)) {
      if (!empty($location['latitude']) &&  !empty($location['longitude']) 
            && !empty($location['unique_identifier']) && !empty($location['place'])) {
        $locationData = $this->PostedBy->Profile->Location->saveLocation($location['place'],
          $location['latitude'],$location['longitude'],$location['unique_identifier']);
        if ($locationData['status'] == 200) {
          $locationId = $locationData['data'];
        } else  {
          return array('status' => $locationData['status'], 'message' => $locationData['message']);
        }
      } else {
        return array('status' => 100, 'message' => 'WallPost saveZoot : Invalid Input Arguments for Location');
      }
    }
    
    $type = WallPostType::intValue($type);
    $category = WallPostCategory::intValue($category);
    $isShared = false;
    $data = array(
      'category' => $category,
      'type' => $type,
  		'user_id' => $userId,
  		'posted_on_id' => $posted_on_id,
      'is_shared' => $isShared,
  		'post' => $zoot,
  		'hyperlink' => $hyperLink,
  		'location_id' => $locationId,
      'wall_post_like_count' => 0,
      'wall_post_comment_count' => 0,
      'Room' => array(
        'name' => "wall post",
        'RoomMember' => array(
          array(
            'user_id' => $userId
          )         
        )
      )
  	);
    if (!empty($imageUrl)) {
      $data['Image'] = array('url' => $imageUrl);
    }
    if (!empty($video)) {
      $data['Video'] = $videoData;
    }
  	if ($this->saveAssociated($data,array('deep' => true))) {
  		$wallPostData = $this->__prepareZootResponse($userId,$this->getLastInsertID());
  		$response = array('status' => 200, 'data' => $wallPostData);
  	} else {
  		  $response = array('status' => 801, 'message' => 'Wall Post saveZoot : Zoot not saved');
  	}
		return $response;
	}

  public function share($userId, $wallPostId, $sharedText) {
    $userId = trim($userId);
    $wallPostId = trim($wallPostId);
    $sharedText = trim($sharedText);

    if (empty($userId) || empty($wallPostId)) {
      return array('status' => 100, 'message' => 'Wall Post share : Invalid Input Arguments');
    }

    if (!$this->_userExists($userId)) {
      return array('status' => 812 , 'message' => 'Wall Post share : User does not exist');
    }

    if (!$this->wallPostExists($wallPostId)) {
      return array('status' => 813 , 'message' => 'Wall Post share : wall post to share does not exist');
    }

    $data = array(
     'type' => WallPostType::STATUS_UPDATE,
     'user_id' => $userId,
     'is_shared' => true,
     'shared_id' => $wallPostId,
     'shared_text' => $sharedText,
     'wall_post_like_count' => 0,
     'wall_post_comment_count' => 0,
     'Room' => array(
       'name' => "wall post",
       'RoomMember' => array(
         array(
           'user_id' => $userId
         )         
       )
      )
    );

    if ($this->saveAssociated($data,array('deep' => true))) {
      $wallPostData = $this->__prepareShareResponse($this->getLastInsertID());
      $response = array('status' => 200, 'data' => $wallPostData);
    } else {
        $response = array('status' => 801, 'message' => 'Wall Post share : share not saved');
    }
    return $response;

  }

  private function __wallPostCategoryExists($category) {
    return in_array($category,WallPostCategory::options());
  }

  private function __wallPostTypeExists($type) {
    return in_array($type,WallPostType::options());
  }

  private function __isvalidPostedOnForCategory($category,$posted_on_id) {
    $response = false;
    switch ($category) {
      case 'User':
        if (!empty($posted_on_id)) {
          $response = $this->_userExists($posted_on_id);
        } else {
          $response = true;
        }
        break;
      case 'Match':
        if (!empty($posted_on_id)) {
          $response = $this->_matchExists($posted_on_id);
        }
        break;
      case 'Team':
        if (!empty($posted_on_id)) {
          $response = $this->_teamExists($posted_on_id);
        }
        break;
      case 'Series':
        if (!empty($posted_on_id)) {
          $response = $this->_seriesExists($posted_on_id);
        }
        break;
    }
    return $response;
  }

  private function __prepareShareResponse($wallPostId) {
    $responseData = array();
    $wallData = $this->find('first',array(
      'conditions' => array(
        'WallPost.id' => $wallPostId 
      ),
      'fields' => array('id','type','user_id','is_shared','room_id','shared_text','shared_id','created')
    ));

    $userData = $this->PostedBy->Profile->find('first',array(
      'conditions' => array(
        'Profile.user_id' => $wallData['WallPost']['user_id'] 
      ),
      'fields' => array('id','first_name','middle_name','last_name','image_id'),
      'ProfileImage' => array(
          'fields' => array('id','url')
      )
    ));

    if(!empty($wallData)) {
      $responseData['type'] = WallPostType::stringValue($wallData['WallPost']['type']);
      $responseData['id'] = $wallData['WallPost']['id'];
      $responseData['posted_by']['id'] = $wallData['WallPost']['user_id'];
      if (!empty($userData['Profile'])) {
        $responseData['posted_by']['name'] = $this->__prepareUserName($userData['Profile']['first_name'],
                                                                  $userData['Profile']['middle_name'],
                                                                  $userData['Profile']['last_name']);
      } else $responseData['posted_by']['name'] = null;

      if (!empty($userData['Profile']['ProfileImage'])) {
        $responseData['posted_by']['image'] = $userData['Profile']['ProfileImage']['url'];
      } else $responseData['posted_by']['image'] = null;
      
      $responseData['is_shared'] = true;
      $responseData['posted_on'] = $wallData['WallPost']['created'];
      $responseData['shared_text'] = $wallData['WallPost']['shared_text'];
      $responseData['total_likes'] = 0;
      $responseData['liked_by'] = array();
      $responseData['total_comments'] = 0;
      $responseData['comments'] = array(); 
      $responseData['room_id'] =  $wallData['WallPost']['room_id'];
      $responseData['original_wall_post_id'] =  $wallData['WallPost']['shared_id']; 

      $originalWallData = $this->find('first',array(
        'conditions' => array(
          'WallPost.id' => $wallData['WallPost']['shared_id']
        ),
        'fields' => array('id','user_id','post','image_id','video_id','hyperlink','location_id','created'),
        'contain' => array(
          'Image' => array(
            'fields' => array('id','url')
          ),
          'Video' => array(
            'fields' => array('id','url')
          ),
          'Location' => array(
            'fields' => array('id','name')
          )
        )
      ));

      $originalWallUserData = $this->PostedBy->Profile->find('first',array(
        'conditions' => array(
          'Profile.user_id' => $wallData['WallPost']['user_id']
        ),
        'fields' => array('id','first_name','middle_name','last_name','image_id'),
        'ProfileImage' => array(
            'fields' => array('id','url')
        )
      ));

      if (!empty($originalWallData)) {
        $responseData['original_post_by']['id'] = $originalWallData['WallPost']['user_id'];
        if (!empty($originalWallUserData['Profile'])) {
          $responseData['original_post_by']['name'] = $this->__prepareUserName($originalWallUserData['Profile']['first_name'],
                                                                    $originalWallUserData['Profile']['middle_name'],
                                                                    $originalWallUserData['Profile']['last_name']);
        } else $responseData['original_post_by']['name'] = null;

        if (!empty($originalWallUserData['Profile']['ProfileImage'])) {
          $responseData['original_post_by']['image'] = $originalWallUserData['Profile']['ProfileImage']['url'];
        } else $responseData['original_post_by']['image'] = null;

        if (!empty($originalWallData['WallPost']['image_id'])) {
          $responseData['has_image'] = true;
          $responseData['image_url'] = $originalWallData['Image']['url'];
        } else {
            $responseData['has_image'] = false;
            $responseData['image_url'] = null;
        }

        if (!empty($originalWallData['WallPost']['video_id'])) {
          $responseData['has_video'] = true;
          $responseData['video_url'] = $originalWallData['Video']['url'];
        } else {
            $responseData['has_video'] = false;
            $responseData['video_url'] = null;
        }

        if (!empty($originalWallData['Location'])) {
          $responseData['location']['id'] = $originalWallData['Location']['id'];
          $responseData['location']['name'] = $originalWallData['Location']['name'];
        } else {
            $responseData['location']['id'] = null;
            $responseData['location']['name'] = null;
        }

        $responseData['text'] = $originalWallData['WallPost']['post'];
        $responseData['hyperlink'] = $originalWallData['WallPost']['hyperlink'];
        $responseData['original_wall_posted_on'] = $originalWallData['WallPost']['created'];
      }
    }
    return $responseData;
  }

	private function __prepareZootResponse($userId,$wallPostId) {
    $zootData = array();
		$wallData = $this->find('first',array(
      'conditions' => array(
        'WallPostSent.id' => $wallPostId 
      ),
      'fields' => array('id','category','type','is_shared','room_id','post','image_id','video_id','hyperlink','location_id',
                        'created'),
      'contain' => array(
        'Image' => array(
          'fields' => array('id','url')
        ),
        'Video' => array(
          'fields' => array('id','url')
        ),
        'Location' => array(
          'fields' => array('id','name','city_id'),
          'City' => array(
            'fields' => array('id','name')
          )
        )
      )
    ));

    $userData = $this->PostedBy->Profile->find('first',array(
      'conditions' => array(
        'Profile.user_id' => $userId 
      ),
      'fields' => array('id','first_name','middle_name','last_name','image_id'),
      'ProfileImage' => array(
          'fields' => array('id','url')
      )
    ));
    
    if(!empty($wallData)) {
      $zootData['category'] = WallPostCategory::stringValue($wallData['WallPostSent']['category']);
      $zootData['type'] = WallPostType::stringValue($wallData['WallPostSent']['type']);
      $zootData['id'] = $wallData['WallPostSent']['id'];
      $zootData['posted_by']['id'] = $userId;
      if (!empty($userData['Profile'])) {
        $zootData['posted_by']['name'] = $this->__prepareUserName($userData['Profile']['first_name'],$userData['Profile']['middle_name'],$userData['Profile']['last_name']);
      } else $zootData['posted_by']['name'] = null;

      $zootData['posted_by']['image'] = !empty($userData['Profile']['ProfileImage']) ? $userData['Profile']['ProfileImage']['url'] : null;
      $zootData['posted_by']['image'] = !empty($wallData['WallPostSent']['is_shared']) ? $wallData['WallPostSent']['is_shared'] : false;
      
      if (!empty($wallData['WallPostSent']['image_id'])) {
        $zootData['has_image'] = true;
        $zootData['image_url'] = $wallData['Image']['url'];
      } else {
        $zootData['has_image'] = false;
        $zootData['image_url'] = null;
      }

      if (!empty($wallData['WallPostSent']['video_id'])) {
        $zootData['has_video'] = true;
        $zootData['video_url'] = $wallData['Video']['url'];
      } else {
        $zootData['has_video'] = false;
        $zootData['video_url'] = null;
      }

      if (!empty($wallData['Location'])) {
        $zootData['location']['id'] = $wallData['Location']['id'];
        $zootData['location']['name'] = $wallData['Location']['name'];
      } else {
        $zootData['location']['id'] = null;
        $zootData['location']['name'] = null;
      }

      $zootData['text'] = $wallData['WallPostSent']['post'];
      $zootData['posted_on'] = $wallData['WallPostSent']['created'];
      $zootData['has_liked'] = false;
      $zootData['hyperlink'] = $wallData['WallPostSent']['hyperlink'];
      $zootData['total_likes'] = 0;
      $zootData['liked_by'] = array();
      $zootData['total_comments'] = 0;
      $zootData['comments'] = array(); 
      $zootData['room_id'] =  $wallData['WallPostSent']['room_id'];   
    } 
    return $zootData;
	}

  public function getUserTimeline($userId) {
    $isTimeline = true;
    $statusUpdateWalls = $this->fetchStatusTypeUpdateWalls($userId,$numOfStatusWalls,$isTimeline);
    $resultTypeUpdateWalls = $this->fetchMatchTypeUpdateWalls($userId,WallPostType::RESULT_UPDATE);
    $mergedWallUpdates = $this->mergeWalls($statusUpdateWalls,$resultTypeUpdateWalls);
    return $mergedWallUpdates;
  }

  public function getUserNewsFeed($userId,$numOfStatusWalls,$numOfTotalWalls,$isTimeline) {
    $statusUpdateWalls = $this->fetchStatusTypeUpdateWalls($userId,$numOfStatusWalls,$isTimeline);
    $remainingWalls = $numOfTotalWalls - count($statusUpdateWalls);
    $wallTypes = [WallPostType::TOSS_UPDATE,WallPostType::SCORE_UPDATE,WallPostType::RESULT_UPDATE];
    $matchTypeUpdateWalls = $this->fetchMatchTypeUpdateWalls(
      $userId,
      $wallTypes,
      $remainingWalls,
      $isTimeline
    );
    $mergedWalls = $this->mergeWalls($statusUpdateWalls,$matchTypeUpdateWalls);
    return $mergedWalls;
  }

	public function fetchStatusTypeUpdateWalls($userId,$numOfStatusWalls,$isTimeline) {
    if ( empty($numOfStatusWalls) || ($numOfStatusWalls == 0) ) {
      return NULL;
    }
    $statusUpdateData = array();
		$options = array(
      'conditions' => array(
       	'type' => WallPostType::STATUS_UPDATE,
      ),
      'contain' => array(
        'WallPostLike' => array(
          'fields' => array('id','user_id as LikedBy','wall_post_id'),
          'User' => array('fields' => array('id'),
            'Profile' => array(
              'fields' => array('id','first_name','middle_name','last_name','image_id'),
              'ProfileImage' => array(
                'fields' => array('id','url')
              )
            )
          )
        ),
        'WallPostComment' => array(
          'fields' => array('id','user_id as commentedBy','comment','modified'),
          'User' => array('fields' => array('id'),
            'Profile' => array(
              'fields' => array('id','first_name','middle_name','last_name','image_id'),
              'ProfileImage' => array(
                'fields' => array('id','url')
              )
            )
          )
        ),
        'PostedBy' => array(
          'fields' => array('id'),
          'Profile' => array(
            'fields' => array('id','first_name','middle_name','last_name','image_id'),
            'ProfileImage' => array(
                'fields' => array('id','url')
              )
          )
        ),
        'Image' => array(
          'fields' => array('id','url')
        ),
        'Video' => array(
          'fields' => array('id','url')
        ),
        'Location' => array(
          'fields' => array('id','name')
        )
      ),
      'fields' => array('id','type','posted_on_id','is_shared','shared_id','shared_text',
                          'room_id','post','image_id','video_id','hyperlink','location_id',
                          'wall_post_like_count','wall_post_comment_count','created','modified',
      ),
      'order' => 'WallPostSent.modified DESC',
      'limit' => $numOfStatusWalls
	  );
    if ($isTimeline) {
      $options['conditions']['OR'] = array(
          'WallPostSent.user_id' => $userId,
          'WallPostSent.posted_on_id' => $userId
        );
    } else {
        $allUserIds = $this->PostedBy->FriendFrom->getUserFriendsIdList($userId);
        $allUserIds[count($allUserIds)] = $userId;
        $options['conditions']['OR'] = array(
          'WallPostSent.user_id' => $allUserIds,
          'WallPostSent.posted_on_id' => $userId
        );
    }
    $data = $this->find('all', $options);

    if(!empty($data)) {
      $statusUpdateData = $this->__prepareDataForStatusUpdate($userId,$data);
    } 
		return $statusUpdateData;
	}

	public function fetchMatchTypeUpdateWalls($userId,$wallTypes,$numOfMatchWalls,$isTimeline) {
    $options = array(
      'contain' => array(
        'WallPostLike' => array(
          'fields' => array('id','user_id as LikedBy','wall_post_id'),
          'User' => array('fields' => array('id'),
            'Profile' => array(
              'fields' => array('id','first_name','middle_name','last_name','image_id'),
              'ProfileImage' => array(
                'fields' => array('id','url')
              )
            )
          )
        ),
        'WallPostComment' => array(
          'fields' => array('id','user_id as commentedBy','comment','modified'),
          'User' => array(
            'fields' => array('id'),
            'Profile' => array(
              'fields' => array('id','first_name','middle_name','last_name','image_id'),
              'ProfileImage' => array(
                'fields' => array('id','url')
              )
            )
          )
        ),
        'PostedBy' => array(
          'fields' => array('id'),
          'Profile' => array(
            'fields' => array('id','first_name','middle_name','last_name','image_id'),
            'ProfileImage' => array(
              'fields' => array('id','url')
            )
          )
        ),
        'Image' => array(
          'fields' => array('id','url')
        ),
        'Video' => array(
          'fields' => array('id','url')
        ),
      ),
      'fields' => array('id','type','posted_on_id','is_shared','room_id','post','image_id','video_id','hyperlink',
                         'wall_post_like_count','wall_post_comment_count','created','modified'),
      'order' => 'WallPostSent.modified DESC',
      'limit' => $numOfMatchWalls
		);
    
    if ($isTimeline) {
      $options['conditions'] = array(
        'WallPostSent.type' => $wallTypes,
        'WallPostSent.user_id' => $userId,
      );
    } else {
        $options['joins'] = array( 
          array(
           'table' => 'match_followers',
           'alias' => 'MatchFollowerJoin',
           'type' => 'inner',
           'conditions' => array(
             'MatchFollowerJoin.match_id = WallPostSent.posted_on_id',
             'MatchFollowerJoin.user_id' => $userId,          
             'WallPostSent.type' => $wallTypes
           )
          ) 
        );
        $options['fields'][count($options['fields'])] =  'MatchFollowerJoin.match_id';
    }
    $matchWallUpdates = $this->find('all', $options);

    if (empty($matchWallUpdates)) {
      return NULL;
    } 
    $matchIds = $this->getMatchIdsFromMatchWalls($matchWallUpdates,$isTimeline);
    $reArrange = 1;
    if (!empty($matchIds)) {
      $matchData = $this->PostedBy->MatchFollower->Match->getMatchDataForWallUpdate($matchIds,$reArrange);
    } else return array();      
    $matchTypeWallUpdateData = $this->__prepareDataForMatchUpdate($userId,$matchWallUpdates,$matchData);    
		return $matchTypeWallUpdateData;
    
	}

  public function fetchMatchFeeds($userId,$matchId,$numofmatchFeeds) {
    //$userId = trim($userId);
    $matchId = trim($matchId);
    $numofmatchFeeds = trim($numofmatchFeeds);
    if (empty($matchId)) {
      return array('status' => 100, 'message' => 'fetchMatchFeed : Invalid Input Arguments');
    }
    // if (!$this->_userExists($userId)) {
    //   return array('status' => 100, 'message' => 'fetchMatchFeed : Invalid User Id');
    // }
    if (!$this->_matchExists($matchId)) {
      return array('status' => 100, 'message' => 'fetchMatchFeed : Invalid Match Id');
    }
    if (empty($numofmatchFeeds)) {
      $numofmatchFeeds = Limit::NUM_OF_MATCH_FEEDS;
    }
    $feeds = array();
    $matchWalls = $this->find('all',array(
      'conditions' => array(
        'WallPost.category' => WallPostCategory::MATCH,
        'WallPost.posted_on_id' => $matchId
      ),
      'contain' => array(
        'WallPostLike' => array(
          'fields' => array('id','user_id as LikedBy','wall_post_id'),
          'User' => array('fields' => array('id'),
            'Profile' => array(
              'fields' => array('id','first_name','middle_name','last_name','image_id'),
              'ProfileImage' => array(
                'fields' => array('id','url')
              )
            )
          )
        ),
        'WallPostComment' => array(
          'fields' => array('id','user_id as commentedBy','comment','modified'),
          'User' => array(
            'fields' => array('id'),
            'Profile' => array(
              'fields' => array('id','first_name','middle_name','last_name','image_id'),
              'ProfileImage' => array(
                'fields' => array('id','url')
              )
            )
          )
        ),
        'PostedBy' => array(
          'fields' => array('id'),
          'Profile' => array(
            'fields' => array('id','first_name','middle_name','last_name','image_id'),
            'ProfileImage' => array(
              'fields' => array('id','url')
            )
          )
        ),
        'Image' => array(
          'fields' => array('id','url')
        ),
        'Video' => array(
          'fields' => array('id','url')
        ),
      ),
      'fields' => array('id','type','posted_on_id','is_shared','room_id','post','image_id','video_id','hyperlink',
                         'wall_post_like_count','wall_post_comment_count','created','modified'),
      'order' => 'WallPost.modified DESC',
      'limit' => $numofmatchFeeds
    ));
    if (!empty($matchWalls)) {
      $Match = new Match();
      $matchData = $Match->getMatchDataForMatchFeed($matchId);
      $feeds = $this->__prepareMatchFeedData($userId,$matchWalls,$matchData);
    }
    return array('status' => 200 , 'data' => $feeds);
  }

  private function __prepareMatchFeedData($userId,$matchWalls,$matchData) {
    $matchUpdateData = array(); 
    foreach ($matchWalls as $matchWall) {
      $data = array();
      $teams = array();
      $matchLocation = array();

      $wallPostType = $matchWall['WallPost']['type'];
      $has_image = !empty($matchWall['WallPost']['image_id']) ? true : false;
      $has_video = !empty($matchWall['WallPost']['video_id']) ? true : false;

      $likeListData = $this->__getLikedByData($userId,$matchWall['WallPostLike']);
      $likedByList = $likeListData['likeData'];
      $hasLiked = $likeListData['has_liked'];

      $commentedByList = $this->__getCommentedByData($matchWall['WallPostComment']);

      if( !empty($matchData['Location'])) {
        $matchLocation['id'] = $matchData['Location']['id'];
        $matchLocation['name'] = $matchData['Location']['name'];
        $matchLocation['city'] = !empty($matchData['Location']['City']) ? $matchData['Location']['City']['name'] : null;
      } else {
        $matchLocation['id'] = null;
        $matchLocation['name'] = null;
        $matchLocation['city'] = null;
      }

      $teams = $this->__getTeamsArrayMatchFeed($matchData['FirstTeam'],$matchData['SecondTeam']);
      $name = $this->__prepareUserName($matchWall['PostedBy']['Profile']['first_name'],$matchWall['PostedBy']['Profile']['middle_name'],$matchWall['PostedBy']['Profile']['last_name']);
      $image = !empty($matchWall['PostedBy']['Profile']['ProfileImage']) ? $matchWall['PostedBy']['Profile']['ProfileImage']['url'] : null;
      $isShared = !empty($matchWall['WallPost']['is_shared']) ? $matchWall['WallPost']['is_shared']: false;

      $data = array(
        'type' => WallPostType::stringValue($wallPostType),
        'id' => $matchWall['WallPost']['id'],
        'posted_by' => array(
                         'id' => $matchWall['PostedBy']['id'],
                         'name' => $name,
                         'image' => $image
                       ),
        'posted_on' => $matchWall['WallPost']['created'],
        'is_shared' => $isShared,
        'room_id' => $matchWall['WallPost']['room_id'],
        'text' => $matchWall['WallPost']['post'],
        'has_image' => $has_image,
        'has_video' => $has_video,
        'has_liked' => $hasLiked,
        'image_url' => $matchWall['Image']['url'],
        'video_url' => $matchWall['Video']['url'],
        'hyperlink' => $matchWall['WallPost']['hyperlink'],
        'match_id' => $matchWall['WallPost']['posted_on_id'],
        'match_name' => $matchData['Match']['name'],
        'match_start_date_time' => $matchData['Match']['start_date_time'],
        'match_location' => $matchLocation,
        'overs_per_innings' => $matchData['Match']['overs_per_innings'],
        'wickets_per_side' => $matchData['Match']['players_per_side'],
        'teams' => $teams,
        'total_likes' => $matchWall['WallPost']['wall_post_like_count'],
        'liked_by' => $likedByList,
        'total_comments' => $matchWall['WallPost']['wall_post_comment_count'],
        'comments' => $commentedByList,
        'modified' => $matchWall['WallPost']['modified']
      );
      if ($isShared == true) {
        $originalWall = $this->getDetailsOfSharedWall($matchWall['WallPost']['shared_id']);
        $data['shared_text'] = $matchWall['WallPost']['shared_text'];
        $data['original_wall_post_id'] = $matchWall['WallPost']['shared_id'];
        $data['original_wall_posted_on'] = $originalWall['created_on'];
        $data['original_post_by'] = $originalWall['posted_by'];
      }

      if ($wallPostType == WallPostType::TOSS_UPDATE ) {
        $toss = array();
        if (!empty($matchData['TossWinningTeam'])) {
          $toss['winning_team_id'] = $matchData['TossWinningTeam']['id'];
          $toss['name'] = $matchData['TossWinningTeam']['name'];
          $toss['image'] = !empty($matchData['TossWinningTeam']['ProfileImage']) ? $matchData['TossWinningTeam']['ProfileImage']['url'] : null;
          $toss['decision'] = MatchTossType::stringValue($matchData['Match']['toss_decision']);
        }
        $data['toss'] = $toss;
      }
      
      if ($wallPostType == WallPostType::SCORE_UPDATE || $wallPostType == WallPostType::RESULT_UPDATE ) {
        $innings = array();
        if (!empty($matchData['MatchInningScorecard'])) {
          foreach ($matchData['MatchInningScorecard'] as $matchInning) {
            $runRate = $this->_prepareRunRate($matchInning['total_runs'],$matchInning['overs']);      
            $innings[$matchInning['inning']-1] = array(
              'team_id' => $matchInning['team_id'],
              'runs' =>  $matchInning['total_runs'],
              'overs' =>  $matchInning['overs'],
              'wickets' =>  $matchInning['wickets'],
              'run_rate' => $runRate
            );
          }
        }
        $data['innings'] = $innings;
      }

      if ($wallPostType == WallPostType::RESULT_UPDATE) {
        $result = array();
        if (!empty($matchData['MatchWinningTeam'])) {
          if ( $matchData['Match']['result_type'] == MatchResultType::ABANDONED) {
            $result['abandoned'] = true;
          }
          else {
            $result['abandoned'] = false;
            $result['result_type'] = MatchResultType::stringValue($matchData['Match']['result_type']);
            $result['winning_team_id'] = $matchData['MatchWinningTeam']['id'];
            $result['victory_margin'] = $this->findWinMarginInMatch($innings,$matchData['Match']['match_type'],
                                                                       $matchData['Match']['players_per_side']);          
          }
        }
        $data['result'] = $result;        
      }
      array_push($matchUpdateData,$data);
    }
    return $matchUpdateData;
  }

  private function __prepareDataForStatusUpdate($userId,$statusUpdateWalls) {
    if (empty($statusUpdateWalls)){
      return;
    }
    $statusData = array();
    foreach ($statusUpdateWalls as $statusUpdateWall) {
      $has_image = false;
      $has_video = false;
      $data = array();
      if (empty($statusUpdateWall['WallPostSent'])) {
        return;
      }
      if (!empty($statusUpdateWall['WallPostSent']['image_id'])) {
        $has_image = true;
      }
      if (!empty($statusUpdateWall['WallPostSent']['video_id'])) {
        $has_video = true;
      }
      $likedByList = array();
      $hasLiked = false;
      $commentedByList = array();

      if (!empty($statusUpdateWall['WallPostLike'])) {
        $likeListData = $this->__getLikedByData($userId,$statusUpdateWall['WallPostLike']);
        $likedByList = $likeListData['likeData'];
        $hasLiked = $likeListData['has_liked'];
      }

      if (!empty($statusUpdateWall['WallPostComment'])) {
        $commentedByList = $this->__getCommentedByData($statusUpdateWall['WallPostComment']);
      }
      
      $name = $this->__prepareUserName($statusUpdateWall['PostedBy']['Profile']['first_name'],
                                          $statusUpdateWall['PostedBy']['Profile']['middle_name'],
                                            $statusUpdateWall['PostedBy']['Profile']['last_name']);

      $image = !empty($statusUpdateWall['PostedBy']['Profile']['ProfileImage']) ? $statusUpdateWall['PostedBy']['Profile']['ProfileImage']['url'] : null;
      $isShared = !empty($statusUpdateWall['WallPostSent']['is_shared']) ? $statusUpdateWall['WallPostSent']['is_shared'] : false;

      $data = array(
        'type' => WallPostType::stringValue($statusUpdateWall['WallPostSent']['type']),
        'id' => $statusUpdateWall['WallPostSent']['id'],
        'location' => array('id' => $statusUpdateWall['Location']['id'],
                            'name' => $statusUpdateWall['Location']['name']
                         ),
        'posted_by' => array(
                         'id' => $statusUpdateWall['PostedBy']['id'],
                         'name' => $name,
                         'image' => $image
                       ),
        'posted_on' => $statusUpdateWall['WallPostSent']['created'],
        'is_shared' => $isShared,
        'room_id' => $statusUpdateWall['WallPostSent']['room_id'],
        'text' => $statusUpdateWall['WallPostSent']['post'],
        'has_image' => $has_image,
        'has_video' => $has_video,
        'has_liked' => $hasLiked,
        'image_url' => $statusUpdateWall['Image']['url'],
        'video_url' => $statusUpdateWall['Video']['url'],
        'hyperlink' => $statusUpdateWall['WallPostSent']['hyperlink'],
        'total_likes' => $statusUpdateWall['WallPostSent']['wall_post_like_count'],
        'liked_by' => $likedByList,
        'total_comments' => $statusUpdateWall['WallPostSent']['wall_post_comment_count'],
        'comments' => $commentedByList,
        'modified' => $statusUpdateWall['WallPostSent']['modified']
      );
      if ($isShared == true) {
        $originalWall = $this->getDetailsOfSharedWall($statusUpdateWall['WallPostSent']['shared_id']);
        $data['shared_text'] = $statusUpdateWall['WallPostSent']['shared_text'];
        $data['original_wall_post_id'] = $statusUpdateWall['WallPostSent']['shared_id'];
        $data['original_wall_posted_on'] = $originalWall['created_on'];
        $data['original_post_by'] = $originalWall['posted_by'];
      }
      array_push($statusData,$data);
    }
    return $statusData;
  }

   private function getMatchIdsFromMatchWalls($matchWallUpdates,$isTimeline) {
    $matchIds = array();
    foreach ($matchWallUpdates as $matchWallUpdate) {
      if ($isTimeline) {
        $matchId = $matchWallUpdate['WallPostSent']['posted_on_id'];
      } else $matchId = $matchWallUpdate['MatchFollowerJoin']['match_id'];
      
      if (!in_array($matchId , $matchIds)) {
        array_push($matchIds , $matchId);
      }
    }
    return $matchIds;
  }

  private function __prepareDataForMatchUpdate($userId,$matchTypeUpdateWalls,$matchDataArray) {
    if(empty($matchTypeUpdateWalls) || empty($matchDataArray)){
      return;
    }
    $matchUpdateData = array();    
    foreach ($matchTypeUpdateWalls as $matchTypeUpdateWall) {
      $has_image = false;
      $has_video = false;
      $data = array();
      $wallPostType = $matchTypeUpdateWall['WallPostSent']['type'];
      $teams = array();
      if (!empty($matchTypeUpdateWall['WallPostSent']['image_id'])) {
        $has_image = true;
      }
      if (!empty($matchTypeUpdateWall['WallPostSent']['video_id'])) {
        $has_video = true;
      }
      $likeListData = $this->__getLikedByData($userId,$matchTypeUpdateWall['WallPostLike']);
      $likedByList = $likeListData['likeData'];
      $hasLiked = $likeListData['has_liked'];
      $commentedByList = $this->__getCommentedByData($matchTypeUpdateWall['WallPostComment']);
      $matchTeamData = $matchDataArray[$matchTypeUpdateWall['WallPostSent']['posted_on_id']]['MatchTeam'];
      $matchData = $matchDataArray[$matchTypeUpdateWall['WallPostSent']['posted_on_id']];
      $matchLocation = array();
      if( !empty($matchData['Location'])) {
        $matchLocation['id'] = $matchData['Location']['id'];
        $matchLocation['name'] = $matchData['Location']['name'];
      }
      if (!empty($matchTeamData)) {
        $teams = $this->getTeamsArray($matchTeamData);
      }
      else return;

      $name = $this->__prepareUserName($matchTypeUpdateWall['PostedBy']['Profile']['first_name'],
                                          $matchTypeUpdateWall['PostedBy']['Profile']['middle_name'],
                                            $matchTypeUpdateWall['PostedBy']['Profile']['last_name']);

      $image = !empty($matchTypeUpdateWall['PostedBy']['Profile']['ProfileImage']) ? $matchTypeUpdateWall['PostedBy']['Profile']['ProfileImage']['url'] : null;
      $isShared = !empty($matchTypeUpdateWall['WallPostSent']['is_shared']) ? $matchTypeUpdateWall['WallPostSent']['is_shared']: false;

      $data = array(
        'type' => WallPostType::stringValue($wallPostType),
        'id' => $matchTypeUpdateWall['WallPostSent']['id'],
        'posted_by' => array(
                         'id' => $matchTypeUpdateWall['PostedBy']['id'],
                         'name' => $name,
                         'image' => $image
                       ),
        'posted_on' => $matchTypeUpdateWall['WallPostSent']['created'],
        'is_shared' => $isShared,
        'room_id' => $matchTypeUpdateWall['WallPostSent']['room_id'],
        'text' => $matchTypeUpdateWall['WallPostSent']['post'],
        'has_image' => $has_image,
        'has_video' => $has_video,
        'has_liked' => $hasLiked,
        'image_url' => $matchTypeUpdateWall['Image']['url'],
        'video_url' => $matchTypeUpdateWall['Video']['url'],
        'hyperlink' => $matchTypeUpdateWall['WallPostSent']['hyperlink'],
        'match_id' => $matchTypeUpdateWall['WallPostSent']['posted_on_id'],
        'match_name' => $matchData['Match']['name'],
        'match_start_date_time' => $matchData['Match']['start_date_time'],
        'match_location' => $matchLocation,
        'overs_per_innings' => $matchData['Match']['overs_per_innings'],
        'wickets_per_side' => $matchData['Match']['players_per_side'],
        'teams' => $teams,
        'total_likes' => $matchTypeUpdateWall['WallPostSent']['wall_post_like_count'],
        'liked_by' => $likedByList,
        'total_comments' => $matchTypeUpdateWall['WallPostSent']['wall_post_comment_count'],
        'comments' => $commentedByList,
        'modified' => $matchTypeUpdateWall['WallPostSent']['modified']
      );
      if ($isShared == true) {
        $originalWall = $this->getDetailsOfSharedWall($matchTypeUpdateWall['WallPostSent']['shared_id']);
        $data['shared_text'] = $matchTypeUpdateWall['WallPostSent']['shared_text'];
        $data['original_wall_post_id'] = $matchTypeUpdateWall['WallPostSent']['shared_id'];
        $data['original_wall_posted_on'] = $originalWall['created_on'];
        $data['original_post_by'] = $originalWall['posted_by'];
      }

      if ($wallPostType == WallPostType::TOSS_UPDATE ) {
        $toss = array();
        if (!empty($matchData['MatchToss'])) {
          $toss['winning_team_id'] = $matchData['MatchToss']['winning_team_id'];
          $toss['decision'] = MatchTossType::stringValue($matchData['MatchToss']['toss_decision']);
        }
        $data['toss'] = $toss;
      }
      
      if ($wallPostType == WallPostType::SCORE_UPDATE || $wallPostType == WallPostType::RESULT_UPDATE ) {
        $innings = array();
        if (!empty($matchData['MatchInningScorecard'])) {
          foreach ($matchData['MatchInningScorecard'] as $matchInning) {
            $inning = $matchInning['inning'];
            if (!(empty($matchInning['overs']) || $matchInning['overs'] == 0)) {
              $runRate = round(($matchInning['total_runs'] / $matchInning['overs']) ,1, PHP_ROUND_HALF_UP);
            }
            else {
              $runRate = 'NA';
            }
            $innings[$inning-1] = array(
              'team_id' => $matchInning['team_id'],
              'runs' =>  $matchInning['total_runs'],
              'overs' =>  $matchInning['overs'],
              'wickets' =>  $matchInning['wickets'],
              'run_rate' => $runRate
            );
          }
        }
        $data['innings'] = $innings;
      }

      if ($wallPostType == WallPostType::RESULT_UPDATE) {
        $result = array();
        if (!empty($matchData['MatchResult'])) {
          if ( $matchData['MatchResult']['result_type'] == MatchResultType::ABANDONED) {
            $result['abandoned'] = true;
          }
          else {
            $result['abandoned'] = false;
            $result['result_type'] = MatchResultType::stringValue($matchData['MatchResult']['result_type']);
            $result['winning_team_id'] = $matchData['MatchResult']['winning_team_id'];
            $result['victory_margin'] = $this->findWinMarginInMatch($innings,$matchData['Match']['match_type'],
                                                                       $matchData['Match']['players_per_side']);          
          }
        }
        $data['result'] = $result;        
      }
      array_push($matchUpdateData,$data);
    }
    return $matchUpdateData;
  }

  private function getDetailsOfSharedWall($wallId) {
    $data = $this->find('first',array(
      'conditions' => array(
        'WallPostSent.id' => $wallId
      ),
      'fields' => array('user_id','created'),
      'contain' => array(
        'PostedBy' => array(
          'fields' => array('id'),
          'Profile' => array(
            'fields' => array('user_id','first_name','middle_name','last_name','image_id'),
            'ProfileImage' => array(
              'fields' => array('id','url')
            )
          )
        ) 
      )
    ));
    $response['created_on'] = !empty($data['WallPostSent']) ? $data['WallPostSent']['created'] : null;
    if (!empty($data['PostedBy']['Profile'])) {
      $response['posted_by']['id'] = $data['PostedBy']['Profile']['user_id'];
      $response['posted_by']['name'] = $this->__prepareUserName($data['PostedBy']['Profile']['first_name'],$data['PostedBy']['Profile']['middle_name'],$data['PostedBy']['Profile']['last_name']);
      $response['posted_by']['first_name'] = $data['PostedBy']['Profile']['first_name'];
      $response['posted_by']['middle_name'] = $data['PostedBy']['Profile']['middle_name'];
      $response['posted_by']['last_name'] = $data['PostedBy']['Profile']['last_name'];
    } else {
      $response['posted_by']['id'] = null;
      $response['posted_by']['name'] = null;
      $response['posted_by']['first_name'] = null;
      $response['posted_by']['middle_name'] = null;
      $response['posted_by']['last_name'] = null;
    }
    if (!empty($data['PostedBy']['Profile']['ProfileImage'])) {
      $response['posted_by']['image'] = $data['PostedBy']['Profile']['ProfileImage']['url'];
    } else {
      $response['posted_by']['image'] = null;
    }
    return $response;
  }

  private function findWinMarginInMatch($innings ,$matchType ,$wicketsPerSide) {
    $victoryMargin = NULL;
    
    //Assuming the match is a 2 innings match only
    if (count($innings) < 2) {
      return $victoryMargin;
    }
    if ($innings[0]['runs'] < $innings[1]['runs']) {
      $victoryMargin = 'WON BY '.($wicketsPerSide-$innings[1]['wickets'])."  WICKETS";
    }
    else if ($innings[0]['runs'] > $innings[1]['runs']) {
      $victoryMargin = 'WON BY '.($innings[0]['runs']-$innings[1]['runs'])."  RUNS";
    }
    else $victoryMargin = "MATCH DRAW";
    
    return $victoryMargin;
  }

  private function __getLikedByData($userId,$wallPostLikes) {
    $likeListData = array();
    $likeList = array();
    $hasLiked = false;
    foreach ($wallPostLikes as $key => $wallPostLike) {
      $name = $this->__prepareUserName($wallPostLike['User']['Profile']['first_name'],
                                          $wallPostLike['User']['Profile']['middle_name'],
                                            $wallPostLike['User']['Profile']['last_name']);
      if (!empty($wallPostLike['User']['Profile']['ProfileImage'])) {
        $image = $wallPostLike['User']['Profile']['ProfileImage']['url'];
      } else $image = NULL;

      $likeData = array(
        'id' => $wallPostLike['id'],
        'user' => array(
          'id' => $wallPostLike['User']['id'],
          'name' => $name,
          'image' => $image
        )
      );
      if ($userId == $wallPostLike['User']['id']) {
        $hasLiked = true;
      }
      $likeListData[$key] = $likeData;
    }
    $likeList['has_liked'] = $hasLiked;
    $likeList['likeData'] = $likeListData;
    return $likeList;
  }

  private function __getCommentedByData($wallPostComments) {
    $commentList = array();
    foreach ($wallPostComments as $wallPostComment) {
      if(!empty($wallPostComment['User']['Profile'])){       
        $name = $this->__prepareUserName($wallPostComment['User']['Profile']['first_name'],
                                          $wallPostComment['User']['Profile']['middle_name'],
                                            $wallPostComment['User']['Profile']['last_name']);
      } else $name = NULL;

      if (!empty($wallPostComment['User']['Profile']['ProfileImage'])) {
        $image = $wallPostComment['User']['Profile']['ProfileImage']['url'];
      } else $image = NULL;

      $commentData = array(
        'id' => $wallPostComment['id'],
        'user' => array(
          'id' => $wallPostComment['User']['id'],
           'name' => $name,
           'image' => $image
        ),
        'comment_text' => $wallPostComment['comment'],
        'modified' => $wallPostComment['modified']
      );
      array_push($commentList,$commentData);
    }
    return $commentList;
  }

  private function getTeamsArray($matchTeamData) {
    $teams = array();
    $index = 0;
    foreach ($matchTeamData as $teamData) {
      if (!empty($teamData['Team'])) {
        if (!empty($teamData['Team']['ProfileImage'])) {
          $image = $teamData['Team']['ProfileImage']['url'];
        } else $image = NULL;
        $teams[$index] = array(
          'id' => $teamData['Team']['id'],
          'name' => $teamData['Team']['name'],
          'image' => $image
        );
        $index = $index + 1;
      }
    }
    return $teams;
  }

  private function __getTeamsArrayMatchFeed($firstTeam,$secondTeam) {
    $index = 0;
    $teams = array();
    if (!empty($firstTeam)) {
      $teams[$index]['id'] = $firstTeam['id'];
      $teams[$index]['name'] = $firstTeam['name'];
      $teams[$index]['image'] = !empty($firstTeam['ProfileImage']) ?  $firstTeam['ProfileImage']['url'] : null;
      $index++;
    } 
    if (!empty($secondTeam)) {
      $teams[$index]['id'] = $secondTeam['id'];
      $teams[$index]['name'] = $secondTeam['name'];
      $teams[$index]['image'] = !empty($secondTeam['ProfileImage']) ?  $secondTeam['ProfileImage']['url'] : null;
    } 
    return $teams;
  }

  public function mergeWalls($statusUpdateWalls,$matchTypeUpdateWalls) {
    if (empty($statusUpdateWalls) && (!empty($matchTypeUpdateWalls))) {
      return $matchTypeUpdateWalls;
    }
    if (empty($matchTypeUpdateWalls) && (!empty($statusUpdateWalls))) {
      return $statusUpdateWalls;
    }

    $mergedWalls = array();
    $lengthStatusWalls = count($statusUpdateWalls);
    $lengthMatchWalls = count($matchTypeUpdateWalls);
    $lengthMergedWalls = $lengthStatusWalls + $lengthMatchWalls;
    $count = 0;
    $indexStatus = 0;
    $indexMatch = 0;
    for ($index=0; $index < ($lengthStatusWalls + $lengthMatchWalls); $index++) { 
      if (($indexStatus < ($lengthStatusWalls-1)) && ($indexMatch < ($lengthMatchWalls-1))) {
        if ($statusUpdateWalls[$indexStatus]['modified'] <= $matchTypeUpdateWalls[$indexMatch]['modified']) {
          $mergedWalls[$index] = $statusUpdateWalls[$indexStatus];
          $indexStatus = $indexStatus + 1;
        }
        else {
          $mergedWalls[$index] = $matchTypeUpdateWalls[$indexMatch];
          $indexMatch = $indexMatch + 1;
        }        
      }
      else if (($indexStatus == ($lengthStatusWalls-1)) && ($indexMatch < $lengthMatchWalls)) {
        $mergedWalls[$index] = $matchTypeUpdateWalls[$indexMatch];
        $indexMatch = $indexMatch + 1;
      }
      else {
        $mergedWalls[$index] = $statusUpdateWalls[$indexStatus];
        $indexStatus = $indexStatus + 1;
      }
    }
    return $mergedWalls;
  }

  public function getTimelinePhotoes($userId,$numOfRecords) {
    if (empty($numOfRecords)) {
      $numOfRecords = Limit::NUM_OF_USER_TIMELINE_PHOTOES;
    }
    $photoes = array();
    $dataArray = $this->find('all',array(
      'conditions' => array(
        'WallPostSent.user_id' => $userId,
        'OR' => array(
          'WallPostSent.type' => WallPostType::STATUS_UPDATE,
          'WallPostSent.posted_on_id' => $userId
        )
      ),
      'fields' => array('id','image_id','created'),
      'contain' => array(
        'Image' => array(
          'fields' => array('id','caption','url')
        ),
      ),     
      'order' => 'WallPostSent.created DESC',
      'limit' => $numOfRecords
    ));
    foreach ($dataArray as $key => $data) {
      $photoes[$key]['wall_post_id'] = $data['WallPostSent']['id'];
      
      $photoes[$key]['image_url'] = $data['Image']['url'];
      $photoes[$key]['image_caption'] = $data['Image']['caption'];
    }
    $countOfTimelinePhotoes = $this->getCountOfTimelinePhotoes($userId);
    return array('total' => $countOfTimelinePhotoes, 'timeline_photos' => $photoes);
  }

  public function getCountOfTimelinePhotoes($userId) {
    return $this->find('count',array(
      'conditions' => array(
        'WallPostSent.user_id' => $userId,
        'OR' => array(
          'WallPostSent.type' => WallPostType::STATUS_UPDATE,
          'WallPostSent.posted_on_id' => $userId
        )
      )
    ));
  }

  public function wallPostExists($wallPostId) {
    return $this->find('count',array(
      'conditions' => array(
        'id' => $wallPostId
      )
    ));
  }

  public function getWallPostRoom($wallPostId) {
    if (empty($wallPostId)) {
      return array('status' => 100, 'message' => 'getWallPostRoom : Invalid Input Arguments');
    }
    $wallPostId = trim($wallPostId);
    $data = $this->find('first',array(
      'conditions' => array(
        'id' => $wallPostId
      ),
      'fields' => array('id','room_id')
    ));
    if (!empty($data['WallPost']['room_id'])) {
      return array('status' => 200, 'data' => $data['WallPost']['room_id']);
    } else return array('status' => 108, 'message' => 'getWallPostRoom : No Room Found For the wall post');
  }

  private function __prepareUserName($firstName, $middleName, $lastName){
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

}
