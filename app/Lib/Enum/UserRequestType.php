<?php 
App::uses('Enum','Lib');
	class UserRequestType extends Enum {
		const FRIEND_REQUEST = 1;
		const GROUP_JOIN_REQUEST = 2;
		const SERIES_TEAM_JOIN_REQUEST = 3;
		const TEAM_PLAYER_JOIN_REQUEST = 4;
		const TEAM_ADMIN_ADD_INVITE = 5;
		const TEAM_STAFF_ADD_INVITE = 6;
		const MATCH_TEAM_PLAYER_JOIN_REQUEST = 7;
		const MATCH_TEAM_JOIN_REQUEST = 8;
		const MATCH_STAFF_ADD_INVITE = 9;
		const MATCH_ADMIN_ADD_INVITE = 10;
		const FAN_CLUB_JOIN_REQUEST = 11;
		const SERIES_ADMIN_ADD_INVITE = 12;
		const SERIES_TEAM_ADD_INVITE = 13;
		const TEAM_PLAYER_ADD_INVITE = 14;
		const MATCH_TEAM_PLAYER_ADD_INVITE = 15;
		const MATCH_TEAM_ADD_INVITE = 16;
		const GROUP_ADD_INVITE = 17;
		const FAN_CLUB_ADD_INVITE = 18;
		const MATCH_ZOOTER_BUCKET_ADD_INVITE = 19;
		const MATCH_ZOOTER_BUCKET_JOIN_REQUEST = 20;

		protected static $_options = array(
			self::FRIEND_REQUEST => 'friend_request',
			self::GROUP_JOIN_REQUEST => 'group_join_request',
			self::SERIES_TEAM_JOIN_REQUEST => 'series_team_join_request',
			self::TEAM_PLAYER_JOIN_REQUEST => 'team_player_join_request',
			self::TEAM_ADMIN_ADD_INVITE => 'team_admin_add_invite',
			self::TEAM_STAFF_ADD_INVITE => 'team_staff_add_invite',
			self::MATCH_TEAM_PLAYER_JOIN_REQUEST => 'match_team_player_join_request',
			SELF::MATCH_TEAM_JOIN_REQUEST => 'match_team_join_request',
			SELF::MATCH_STAFF_ADD_INVITE => 'match_staff_add_invite',
			SELF::MATCH_ADMIN_ADD_INVITE => 'match_admin_add_invite',
			self::FAN_CLUB_JOIN_REQUEST => 'fan_club_join_request',
			self::SERIES_ADMIN_ADD_INVITE => 'series_admin_add_invite',
			self::SERIES_TEAM_ADD_INVITE => 'series_team_add_invite',
			SELF::TEAM_PLAYER_ADD_INVITE => 'team_player_add_invite',
			SELF::MATCH_TEAM_PLAYER_ADD_INVITE => 'match_team_player_join_invite',
			SELF::MATCH_TEAM_ADD_INVITE => 'match_team_add_invite',
			self::GROUP_ADD_INVITE => 'group_add_invite',
			self::FAN_CLUB_ADD_INVITE => 'fan_club_add_invite',
			self::MATCH_ZOOTER_BUCKET_ADD_INVITE => 'match_zooter_buket_add_invite',
			self::MATCH_ZOOTER_BUCKET_JOIN_REQUEST => 'match_zooter_buket_join_request'
		);
  }