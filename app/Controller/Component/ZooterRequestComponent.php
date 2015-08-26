<?php
App::uses('Component', 'Controller');
App::uses('Validation', 'Utility');
App::uses('HttpSocket', 'Network/Http');

class ZooterRequestComponent extends Component {

	public function validateRequestData($requestData, $api, $action, $requestType = 'post') {
    if ($requestType == 'post') {
        $isValidRequestType = $this->__isPost($requestData);
    } else {
        $isValidRequestType = $this->__isGet($requestData);
    }
    if ($isValidRequestType) {
      if ($this->__hasRequestData($requestData)) {
        $requestData = $this->__extractRequestData($requestData);
        $requiredParams = array('head' => '', 'data' => '');
        switch($api){
          case 'album':
            $requiredParams = $this->__getRequiredParamsForAlbumAPIRequests($action);
            break;
          case 'image':
            $requiredParams = $this->__getRequiredParamsForImageAPIRequests($action);
            break;
          case 'fan_club':
            $requiredParams = $this->__getRequiredParamsForFanClubAPIRequests($action);
            break;
          case 'group':
            $requiredParams = $this->__getRequiredParamsForGroupAPIRequests($action);
            break;
          case 'location':
            $requiredParams = $this->__getRequiredParamsForLocationAPIRequests($action);
            break;
          case 'match' :
            $requiredParams = $this->__getRequiredParamsForMatchAPIRequests($action);
            break;
          case 'match_award' :
            $requiredParams = $this->__getRequiredParamsForMatchAwardAPIRequests($action);
            break;
          case 'match_inning_scorecard' :
            $requiredParams = $this->__getRequiredParamsForMatchInningScorecardAPIRequests($action);
            break;
          case 'match_player' :
            $requiredParams = $this->__getRequiredParamsForMatchPlayerAPIRequests($action);
            break;
          case 'match_player_scorecard' :
            $requiredParams = $this->__getRequiredParamsForMatchPlayerScorecardAPIRequests($action);
            break;
          case 'match_privilege' :
            $requiredParams = $this->__getRequiredParamsForMatchPrivilegeAPIRequests($action);
            break;
          case 'match_result' :
            $requiredParams = $this->__getRequiredParamsForMatchResultAPIRequests($action);
            break;
          case 'match_staff' :
            $requiredParams = $this->__getRequiredParamsForMatchStaffAPIRequests($action);
            break;
          case 'match_team' :
            $requiredParams = $this->__getRequiredParamsForMatchTeamAPIRequests($action);
            break;
          case 'match_toss' :
            $requiredParams = $this->__getRequiredParamsForMatchTossAPIRequests($action);
            break;
          case 'match_follower' :
            $requiredParams = $this->__getRequiredParamsForMatchFollowerAPIRequests($action);
            break;
          case 'match_recommendation' :
            $requiredParams = $this->__getRequiredParamsForMatchRecommendedAPIRequests($action);
            break;
          case 'zooter_basket' :
            $requiredParams = $this->__getRequiredParamsForZooterBucketAPIRequests($action);
            break;
          case 'player_profile' :
            $requiredParams = $this->__getRequiredParamsForPlayerProfileAPIRequests($action);
            break;
          case 'player_statistic' :
            $requiredParams = $this->__getRequiredParamsForPlayerStatisticAPIRequests($action);
            break;
          case 'series':
            $requiredParams = $this->__getRequiredParamsForSeriesAPIRequests($action);
            break;
          case 'series_award':
            $requiredParams = $this->__getRequiredParamsForSeriesAwardAPIRequests($action);
            break;
          case 'series_privilege':
            $requiredParams = $this->__getRequiredParamsForSeriesPrivilegeAPIRequests($action);
            break;
          case 'series_team':
            $requiredParams = $this->__getRequiredParamsForSeriesTeamAPIRequests($action);
            break;
          case 'team' :
            $requiredParams = $this->__getRequiredParamsForTeamAPIRequests($action);
            break;
          case 'team_player':
            $requiredParams = $this->__getRequiredParamsForTeamPlayerAPIRequests($action);
            break;
          case 'team_privilege':
            $requiredParams = $this->__getRequiredParamsForTeamPrivilegeAPIRequests($action);
            break;
          case 'team_staff':
            $requiredParams = $this->__getRequiredParamsForTeamStaffAPIRequests($action);
            break;
          case 'type':
            $requiredParams = $this->__getRequiredParamsForTypeAPIRequests($action);
            break;
          case 'user':
            $requiredParams = $this->__getRequiredParamsForUserAPIRequests($action);
            break;
          case 'profile':
            $requiredParams = $this->__getRequiredParamsForProfileAPIRequests($action);
            break;
          case 'user_friend':
            $requiredParams = $this->__getRequiredParamsForUserFriendAPIRequests($action);
            break;
          case 'user_request':
            $requiredParams = $this->__getRequiredParamsForUserRequestAPIRequests($action);
            break;
          case 'wall_post':
            $requiredParams = $this->__getRequiredParamsForWallPostAPIRequests($action);
            break;
          case 'wall_post_comment':
            $requiredParams = $this->__getRequiredParamsForWallPostCommentAPIRequests($action);
            break;
          case 'wall_post_like':
            $requiredParams = $this->__getRequiredParamsForWallPostLikeAPIRequests($action);
            break;
          case 'notification':
            $requiredParams = $this->__getRequiredParamsForNotificationAPIRequests($action);
            break;
          case 'place':
            $requiredParams = $this->__getRequiredParamsForPlaceAPIRequests($action);
            break;
          case 'ground':
            $requiredParams = $this->__getRequiredParamsForGroundAPIRequests($action);
            break;
        }
        $isValidRequest = $this->__validateHeadAndDataParams($requestData, $requiredParams['head'], $requiredParams['data']);
        if ($isValidRequest) {
          $this->Application = ClassRegistry::init('Application');
          $isValidApplication = $this->Application->find('count', array('conditions' => array('Application.guid' => $requestData['appGuid'], 'Application.is_active' => true)));
          if ($isValidApplication) {
            //  Validate Api Endpoint
            // $apiResult=$this->validateAPIEndPoint($requestData,$this->Application);
            // if (!$apiResult) {
            //   return array('validation_result' => false, 'request_data' => $requestData, 'message' => 'Access to this API endpoint is restricted.', 'api_return_code' => 8000);
            // }
            if (in_array('apiKey', $requiredParams['head']) && in_array('user_id', $requiredParams['data'])) {
              $this->APIKey = ClassRegistry::init('APIKey');
              $hasValidAPIKey = $this->APIKey->find('count', array('conditions' => array('APIKey.api_key' => $requestData['apiKey'], 'APIKey.user_id' => $requestData['data']['user_id'])));
              if ($hasValidAPIKey) {
                $out = array('validation_result' => true, 'request_data' => $requestData, 'message' => 'Validation success.', 'api_return_code' => 200);
              } else {
                $out = array('validation_result' => false, 'request_data' => $requestData, 'message' => 'Authorization required. Login data or api key might be incorrect or missing.', 'api_return_code' => 401);
              }
            } else {
              $out = array('validation_result' => true, 'request_data' => $requestData, 'message' => 'Validation success.', 'api_return_code' => 200);
            }
          } else {
            $out = array('validation_result' => false, 'request_data' => $requestData, 'message' => 'Authorization Required. The appGuid does not have a corresponding application.'.$requestData['appGuid'], 'api_return_code' => 401);
          }
        } else {
          $out = array('validation_result' => false, 'request_data' => $requestData, 'message' => 'Validation failed. Missing required head or data params', 'api_return_code' => 400);
        }
      } else {
        $out = array('validation_result' => false, 'request_data' => '', 'message' => 'No valid request data', 'api_return_code' => 400);
      }
    } else {
      $out = array('validation_result' => false, 'request_data' => '', 'message' => 'Not valid REST request type', 'api_return_code' => 400);
    }
    return $out;
  }
  private function __isPost($currentRequest) {
    return 1 || ($currentRequest->is('post') || $currentRequest->is('put'));
  }

  private  function __isGet($currentRequest) {
    return 1 || $currentRequest->is('get');
  }
  private function __hasRequestData($currentRequest) {
    return isset($currentRequest->data[0]);
  }
  private function __extractRequestData($currentRequest) {
    return json_decode($currentRequest->data[0], true);
  }

  /* Required params for Album API */
  private function __getRequiredParamsForAlbumAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'album', 'images');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for Image API */
  private function __getRequiredParamsForImageAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','images');
      break;
      case 'edit':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','id');
      break;
      case 'delete_match_pic':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','match_id','id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for Fan Club API */
  private function __getRequiredParamsForFanClubAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid', 'apiKey');
        $requiredDataParams = array('user_id', 'favorite_movies', 'favorite_music', 'favorite_hobbies', 'favorite_singers', 'favorite_holiday_destinations', 'favorite_shots', 'favorite_dishes');
      break;
      case 'update':
        $requiredRequestHead = array('appGuid', 'apiKey');
        $requiredDataParams = array('id', 'user_id');
      break;
      case 'view':
        $requiredRequestHead = array('appGuid', 'apiKey');
        $requiredDataParams = array('id', 'user_id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for Group API */
  private function __getRequiredParamsForGroupAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'get_eligible_users':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
      break;
      case 'add':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'name', 'members');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }


    /* Required params for Location API */

  private function __getRequiredParamsForLocationAPIRequests($action){
    $requiredRequestHead = '' ; $requiredDataParams = '';
    switch ($action) {
      case 'nearby_locations':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('latitude','longitude','distance');
      break;
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('latitude','longitude','unique_identifier','place');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

   /* Required params for Match API */

  private function __getRequiredParamsForMatchAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','basic_info');
        break;
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
       break;
      case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
       break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
       break;
      case 'update_or_add_match_team_player':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_players_roles_status');
        break;
      case 'search':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','searchInput','searchType');
       break;
      case 'match_corner_public':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('is_public');
       break;
      case 'match_corner':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
       break;
      case 'match_search_public':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('filters');
       break;
      case 'match_search':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','top_filter','sub_filter');
       break;
      case 'match_live':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id');
       break;
      case 'match_miniscorecard':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id');
       break;
      case 'match_scorecard':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id');
       break;
      case 'match_teams':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id');
       break;
      case 'match_exists':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id');
       break;
      case 'match_checks':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id');
       break;
      case 'match_pics':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id');
       break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

     /* Required params for MatchAwardApi API */

  private function __getRequiredParamsForMatchAwardAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','match_awards');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

   /* Required params for MatchInningScorecardApi API */

  private function __getRequiredParamsForMatchInningScorecardAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id', 'match_inning_scorecards');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

 /* Required params for MatchPlayerApi API */

  private function __getRequiredParamsForMatchPlayerAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_players');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }


  /* Required params for MatchPlayerScorecardApi API */

  private function __getRequiredParamsForMatchPlayerScorecardAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id', 'match_player_scorecards');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'toggle_graph_type':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','year','is_batting');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for MatchPrivilegeApi API */

  private function __getRequiredParamsForMatchPrivilegeAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','match_privileges');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'match_admin_check':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id');
      break;
      case 'invite_admin':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id','invite_user_id');
      break;
      case 'delete_admin':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id','delete_user_id');
      break;
      case 'search_match_admin':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id','input');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for MatchResultApi API */

  private function __getRequiredParamsForMatchResultAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','winning_team_id','result_type');
      break;
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id','match_id','winning_team_id','result_type');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for MatchStaffApi API */

  private function __getRequiredParamsForMatchStaffAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_staffs');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }


  /* Required params for MatchTeamApi API */

  private function __getRequiredParamsForMatchTeamAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_teams');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'invite_team_match':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','match_id','team_id');
      break;
      case 'match_admin_teams':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','match_id');
      break;
      case 'match_team_join':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','match_id','team_id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

    /* Required params for MatchTossApi API */

  private function __getRequiredParamsForMatchTossAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','winning_team_id','toss_decision');
      break;
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id','match_id','winning_team_id','toss_decision');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for Match Follower API */
  private function __getRequiredParamsForMatchFollowerAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'follow_match':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id');
      break;
      case 'unfollow_match':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id');
      break;
      case 'get_followed_matches':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','num_of_records');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  private function __getRequiredParamsForMatchRecommendedAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'get_users_to_recommend':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id','num_of_records');
        break;
      case 'add_users_to_recommend':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id',"recommended_to");
        break;
      case 'search_users_to_recommend':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id','input','num_of_records');
        break;
      case 'get_recommended_matches':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','num_of_records');
        break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  private function __getRequiredParamsForZooterBucketAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'add_or_invite':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id','invite_user_id');
        break;
      case 'search_player_zooter_basket':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('match_id','user_id','input');
        break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for Player Profile API */
  private function __getRequiredParamsForPlayerProfileAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'edit':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
        break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

   /* Required params for Player Statistic API */
  private function __getRequiredParamsForPlayerStatisticAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update_or_add_player_career_statistics':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('player_career_statistics');
        break;
      case 'player_search_public':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('filters');
      break;
      case 'player_search':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','filters');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

   
   /* Required params for Series API */
  private function __getRequiredParamsForSeriesAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('name', 'user_id');
      break;
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'tournament_search_public':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('filters');
      break;
      case 'tournament_search':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','top_filter','sub_filter');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

    /* Required params for SeriesAward API */

  private function __getRequiredParamsForSeriesAwardAPIRequests($action){
    $requiredRequestHead = '' ; $requiredDataParams = '';
    switch ($action) {
      case 'update':
          $requiredRequestHead = array('appGuid');
          $requiredDataParams = array('series_id', 'series_awards');
        break;
      case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

    /* Required params for SeriesPrivilege API */

  private function __getRequiredParamsForSeriesPrivilegeAPIRequests($action){
    $requiredRequestHead = '' ; $requiredDataParams = '';
    switch ($action) {
      case 'update':
          $requiredRequestHead = array('appGuid');
          $requiredDataParams = array('series_id', 'series_privileges');
        break;
      case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }


    /* Required params for SeriesTeam API */

  private function __getRequiredParamsForSeriesTeamAPIRequests($action){
    $requiredRequestHead = '' ; $requiredDataParams = '';
    switch ($action) {
      case 'update':
          $requiredRequestHead = array('appGuid');
          $requiredDataParams = array('series_id', 'series_teams');
        break;
      case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for Team API */
  private function __getRequiredParamsForTeamAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','basic_info');
      break;
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'team_search_public':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('filters');
      break;
      case 'team_search':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','top_filter','sub_filter');
      break;
      case 'search_match_teams':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','match_id','input');
      break;
      case 'user_teams':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','num_of_records');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

    /* Required params for TeamPlayerApi API */

  private function __getRequiredParamsForTeamPlayerAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('team_id','team_players');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'invite_zooter_basket_player_to_team':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','match_id','team_id','invite_user_id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for TeamPrivilegeApi API */

  private function __getRequiredParamsForTeamPrivilegeAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('team_id','team_privileges');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for TeamStaffApi API */

  private function __getRequiredParamsForTeamStaffAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'update':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('team_id', 'team_staffs');
      break;
       case 'show':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'delete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required Params for Type API*/

  private function __getRequiredParamsForTypeAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'add_type':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('type');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required Params for User Request API*/

  private function __getRequiredParamsForUserRequestAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'handle_request':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','user_request_id','status');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for User API */

  private function __getRequiredParamsForUserAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'register':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('email', 'password');
        break;
      case 'authenticate':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('password');
        break;
      case 'forgot_password':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('email');
        break;
      case 'is_correct_user':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'password_reset');
        break;
      case 'reset_password':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'password');
        break;
      case 'zoot':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','category','type');
        break;
      case 'get_news_feed':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
        break;
      case 'autocomplete':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'searchWord');
        break;
      case 'social_login':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('email', 'facebook_id', 'facebook_access_token');
        break;
      case 'twitter_login':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('email', 'twitter_oauth_key', 'twitter_oauth_secret');
        break;
      case 'twitter_user_exists':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('twitter_oauth_key', 'twitter_oauth_secret');
        break;
      case 'zooter_verification':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('twitter_handle');
        break;
      case 'prepare_notification_area':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
        break;
      case 'prepare_dashboard':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
        break;
      case 'view_profile':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'profile_user_id');
        break;
      case 'edit_profile':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id', 'name');
        break;
      case 'get_user_details':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'profile_id');
        break;
      case 'prepare_profile_activities':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
        break;
      case 'prepare_profile_about':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
        break;
      case 'prepare_profile_career':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
        break;
      case 'prepare_profile_social':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
        break;
      case 'search_box':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','search_input','num_of_records');
        break;
      case 'search_friends':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','input','num_of_records');
        break;
      case 'search_social':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','search_input','num_of_records');
        break;
      case 'filter_social':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','filter','num_of_records');
        break;
      case 'update_last_seen_notification_time':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','date');
        break;
      case 'update_last_seen_request_time':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','date');
      break;
      case 'update_last_seen_message_time':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','date');
        break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

    /* Required params for WallPost API */
  private function __getRequiredParamsForProfileAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'edit_personal_info':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
      break;
      case 'edit_location':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','latitude','longitude','unique_identifier','place');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for UserFriend API */
  private function __getRequiredParamsForUserFriendAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'send_friend_request':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'friend_id');
      break;
      case 'get_pending_friend_requests':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
      break;
      case 'sent_friend_requests':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
      break;
      case 'get_friendlist':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
      break;
      case 'cancel_pending_request':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
      break;
      case 'cancel_sent_request':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
      break;
      case 'accept_pending_request':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
      break;
      case 'toggle_friendship':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','friend_id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }


    /* Required params for WallPost API */
  private function __getRequiredParamsForWallPostAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'share':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','wall_post_id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }


    /* Required params for WallPostComment API */
  private function __getRequiredParamsForWallPostCommentAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'add':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'comment', 'wall_post_id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for WallPostLike API */
  private function __getRequiredParamsForWallPostLikeAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'toggle_like':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id', 'wall_post_id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for Notification API */

  private function __getRequiredParamsForNotificationAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('type','users','sender_id','direct_link','data','date_time');
      break;
      case 'create_for_room':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('room_id','notification_data');
      break;
      case 'update_notification_read':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id','notification_id');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for Place API */

  private function __getRequiredParamsForPlaceAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'create':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('name', 'type', 'phone');
      break;
      case 'edit':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id', 'name', 'type', 'phone');
      break;
      case 'search':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('location', 'place_type', 'limit', 'page', 'distance');
      break;
      case 'view':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'view_place':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('id');
      break;
      case 'list_places':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('user_id');
      break;
      case 'add_to_favorite':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('place_id', 'user_id');
      break;
      case 'remove_from_favorite':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('place_id', 'user_id');
      break;
      case 'get_place_details':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('place_id', 'user_id');
      break;
      case 'add_review':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('place_id', 'user_id', 'rating', 'review');
      break;
      case 'add_rating':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('place_id', 'user_id', 'rating');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  /* Required params for Ground API */

  private function __getRequiredParamsForGroundAPIRequests($action) {
    $requiredRequestHead = ''; $requiredDataParams = '';
    switch ($action) {
      case 'get_suggestions':
        $requiredRequestHead = array('appGuid');
        $requiredDataParams = array('key');
      break;
    }
    return array('head' => $requiredRequestHead, 'data' => $requiredDataParams);
  }

  public function __validateHeadAndDataParams($requestData, $requiredRequestHead = '', $requiredDataParams = '') {
    if (!empty($requiredRequestHead)) {
      $hasValidHeadParams = $this->__validateRequiredParams($requestData, $requiredRequestHead);
      if ($hasValidHeadParams) {
        if(!empty($requiredDataParams)) {
          if (isset($requestData['data'])) {
            $hasValidDataParams = $this->__validateRequiredParams($requestData['data'], $requiredDataParams);
            if ($hasValidDataParams) {
              return true;
            }
          }
        }
      }
    }
    return false;
  }
  

  private function __validateRequiredParams($requestArray, $requiredParams) {
    foreach ($requiredParams as $param) {
      if (isset($requestArray[$param])) {
        continue;
      }else{
        return false;
      }
    }
    return true;
  }
  public function getRequestParam($requestData, $param, $paramType = 'data', $defaultValue = '') {
    if($paramType == 'data'){
      $value = isset($requestData['data'][$param]) ? $requestData['data'][$param] : $defaultValue;
    }else{
      $value = isset($requestData[$param]) ? $requestData[$param] : $defaultValue;
    }
    return $value;
  }

  public function validateUpdateParams(&$array, $wanted_key , $key_value, $top_level = true) {
    if ( ! $top_level) {
      if (array_key_exists($wanted_key, $array)){
        if ($array[$wanted_key] != $key_value) {
          return false;
        }
      } else {
        if ( ! array_key_exists(0, $array)) { 
          $array[$wanted_key] = $key_value;
        }
      }
    }
    foreach ($array as &$value) {
      if (is_array($value)) {
        if( ! $this->validateUpdateParams($value, $wanted_key, $key_value, false)) {
          return false;
        }
      }
    }
    return true;
  }

  public function validateCreateParams(&$array, $unwanted_key) {
    if (array_key_exists($unwanted_key, $array)) {
      return false;
    }
    foreach ($array as &$value) {
      if (is_array($value)) {
        if ( ! $this->validateCreateParams($value, $unwanted_key)) {
          return false;
        }
      }
    }
    return true;
  }
}