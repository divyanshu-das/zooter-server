<?php
App::uses('TeamPlayer', 'Model');

/**
 * TeamPlayer Test Case
 *
 */
class TeamPlayerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.team_player',
		'app.team',
		'app.location',
		'app.profile',
		'app.user',
		'app.type',
		'app.api_key',
		'app.wall_post',
		'app.wall_post_comment',
		'app.match_inning_scorecard',
		'app.match',
		'app.match_team',
		'app.series',
		'app.host_country',
		'app.series_awards',
		'app.series_teams',
		'app.series_privileges',
		'app.series_team'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TeamPlayer = ClassRegistry::init('TeamPlayer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TeamPlayer);

		parent::tearDown();
	}

}
