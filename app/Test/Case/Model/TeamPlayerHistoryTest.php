<?php
App::uses('TeamPlayerHistory', 'Model');

/**
 * TeamPlayerHistory Test Case
 *
 */
class TeamPlayerHistoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.team_player_history',
		'app.team',
		'app.location',
		'app.profile',
		'app.user',
		'app.type',
		'app.player_profile',
		'app.api_key',
		'app.wall_post',
		'app.wall_post_comment',
		'app.wall_post_like',
		'app.user_friend',
		'app.group_message',
		'app.group',
		'app.user_group',
		'app.group_message_comment',
		'app.group_message_like',
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
		'app.match_inning_scorecard',
		'app.match_player',
		'app.match_player_scorecard',
		'app.match_privilege',
		'app.match_staff',
		'app.match_team',
		'app.match_follower',
		'app.team_player',
		'app.team_privilege',
		'app.team_staff',
		'app.reminder',
		'app.user_request',
		'app.notification',
		'app.fan_club',
		'app.image',
		'app.album',
		'app.album_contributor',
		'app.image_comment',
		'app.fan_club_favorite_member',
		'app.fan_club_favorite',
		'app.player_statistic',
		'app.video',
		'app.user_favorite'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TeamPlayerHistory = ClassRegistry::init('TeamPlayerHistory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TeamPlayerHistory);

		parent::tearDown();
	}

}
