<?php
App::uses('MatchToss', 'Model');

/**
 * MatchToss Test Case
 *
 */
class MatchTossTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.match_toss',
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
		'app.match_award',
		'app.award_type',
		'app.series_award',
		'app.match_comment',
		'app.match_player',
		'app.match_player_scorecard',
		'app.match_privilege',
		'app.match_staff',
		'app.series_privilege',
		'app.team',
		'app.match_inning_scorecard',
		'app.team_player',
		'app.team_privilege',
		'app.team_staff',
		'app.match_team',
		'app.series_team',
		'app.country',
		'app.match_result'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MatchToss = ClassRegistry::init('MatchToss');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MatchToss);

		parent::tearDown();
	}

}
