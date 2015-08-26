<?php
App::uses('Notification', 'Model');

/**
 * Notification Test Case
 *
 */
class NotificationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.notification',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.wall_post',
		'app.wall_post_comment',
		'app.wall_post_like',
		'app.match',
		'app.series',
		'app.series_award',
		'app.award_type',
		'app.match_award',
		'app.series_team',
		'app.team',
		'app.match_inning_scorecard',
		'app.team_player',
		'app.team_privilege',
		'app.team_staff',
		'app.match_team',
		'app.series_privilege',
		'app.match_result',
		'app.match_toss',
		'app.match_comment',
		'app.match_player',
		'app.match_player_scorecard',
		'app.match_privilege',
		'app.match_staff',
		'app.match_follower',
		'app.player_profile',
		'app.player_statistic',
		'app.api_key',
		'app.user_friend',
		'app.group_message',
		'app.group',
		'app.user_group',
		'app.group_message_comment',
		'app.group_message_like'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Notification = ClassRegistry::init('Notification');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Notification);

		parent::tearDown();
	}

}
