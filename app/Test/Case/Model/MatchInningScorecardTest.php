<?php
App::uses('MatchInningScorecard', 'Model');

/**
 * MatchInningScorecard Test Case
 *
 */
class MatchInningScorecardTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		'app.match_award',
		'app.award_type',
		'app.match_comment',
		'app.match_player',
		'app.match_player_scorecard',
		'app.match_privilege',
		'app.match_staff',
		'app.country',
		'app.series_awards',
		'app.series_teams',
		'app.series_privileges',
		'app.match_result',
		'app.match_toss',
		'app.match_team'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MatchInningScorecard = ClassRegistry::init('MatchInningScorecard');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MatchInningScorecard);

		parent::tearDown();
	}

}
