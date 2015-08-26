<?php
App::uses('ZooterBucket', 'Model');

/**
 * ZooterBucket Test Case
 *
 */
class ZooterBucketTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.zooter_bucket',
		'app.match',
		'app.series',
		'app.location',
		'app.city',
		'app.state',
		'app.country',
		'app.profile',
		'app.user',
		'app.type',
		'app.player_profile',
		'app.api_key',
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
		'app.match_team',
		'app.match_inning_scorecard',
		'app.match_ball_score',
		'app.match_batsman_score',
		'app.match_bowler_score',
		'app.match_result',
		'app.match_toss',
		'app.team_player',
		'app.team_privilege',
		'app.team_staff',
		'app.team_player_history',
		'app.match_player',
		'app.series_team',
		'app.image_comment',
		'app.video',
		'app.room',
		'app.room_member',
		'app.wall_post_comment',
		'app.wall_post_like',
		'app.user_friend',
		'app.match_award',
		'app.award_type',
		'app.series_award',
		'app.match_comment',
		'app.match_player_scorecard',
		'app.match_privilege',
		'app.match_staff',
		'app.match_follower',
		'app.match_recommendation',
		'app.series_privilege',
		'app.reminder',
		'app.user_request',
		'app.player_statistic',
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
		$this->ZooterBucket = ClassRegistry::init('ZooterBucket');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ZooterBucket);

		parent::tearDown();
	}

}
