<?php
App::uses('MatchTeam', 'Model');

/**
 * MatchTeam Test Case
 *
 */
class MatchTeamTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.match_team',
		'app.match',
		'app.series',
		'app.location',
		'app.profile',
		'app.user',
		'app.type',
		'app.api_key',
		'app.wall_post',
		'app.wall_post_comment',
		'app.host_country',
		'app.series_awards',
		'app.series_teams',
		'app.series_privileges',
		'app.team',
		'app.match_inning_scorecard',
		'app.series_team'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MatchTeam = ClassRegistry::init('MatchTeam');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MatchTeam);

		parent::tearDown();
	}

}
