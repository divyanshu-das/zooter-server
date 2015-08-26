<?php
App::uses('ScoreUpdate', 'Model');

/**
 * ScoreUpdate Test Case
 *
 */
class ScoreUpdateTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.score_update',
		'app.match_inning_scorecard',
		'app.match',
		'app.series',
		'app.location',
		'app.profile',
		'app.user',
		'app.type',
		'app.player_profile',
		'app.player_statistic',
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
		'app.match_award',
		'app.award_type',
		'app.series_award',
		'app.match_comment',
		'app.match_player',
		'app.team',
		'app.team_player',
		'app.team_privilege',
		'app.team_staff',
		'app.match_team',
		'app.series_team',
		'app.match_player_scorecard',
		'app.match_privilege',
		'app.match_staff',
		'app.match_follower',
		'app.series_privilege',
		'app.match_result',
		'app.match_toss'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ScoreUpdate = ClassRegistry::init('ScoreUpdate');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ScoreUpdate);

		parent::tearDown();
	}

}
