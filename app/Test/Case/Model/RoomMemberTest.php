<?php
App::uses('RoomMember', 'Model');

/**
 * RoomMember Test Case
 *
 */
class RoomMemberTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.room_member',
		'app.room',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.wall_post',
		'app.image',
		'app.album',
		'app.album_contributor',
		'app.fan_club',
		'app.fan_club_favorite_member',
		'app.fan_club_favorite',
		'app.notification',
		'app.group',
		'app.group_message',
		'app.group_message_comment',
		'app.group_message_like',
		'app.user_group',
		'app.team',
		'app.match_inning_scorecard',
		'app.match',
		'app.series',
		'app.series_award',
		'app.award_type',
		'app.match_award',
		'app.series_team',
		'app.series_privilege',
		'app.match_result',
		'app.match_toss',
		'app.match_comment',
		'app.match_player',
		'app.match_player_scorecard',
		'app.match_privilege',
		'app.match_staff',
		'app.match_team',
		'app.match_follower',
		'app.match_recommendation',
		'app.team_player',
		'app.team_privilege',
		'app.team_staff',
		'app.team_player_history',
		'app.image_comment',
		'app.wall_post_comment',
		'app.wall_post_like',
		'app.player_profile',
		'app.api_key',
		'app.user_friend',
		'app.reminder',
		'app.user_request',
		'app.player_statistic',
		'app.video',
		'app.user_favorite',
		'app.fan_club_member'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RoomMember = ClassRegistry::init('RoomMember');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RoomMember);

		parent::tearDown();
	}

}
