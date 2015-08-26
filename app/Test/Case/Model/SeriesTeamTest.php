<?php
App::uses('SeriesTeam', 'Model');

/**
 * SeriesTeam Test Case
 *
 */
class SeriesTeamTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.series_team',
		'app.series',
		'app.location',
		'app.profile',
		'app.user',
		'app.type',
		'app.api_key',
		'app.wall_post',
		'app.wall_post_comment',
		'app.host_country',
		'app.match',
		'app.series_awards',
		'app.series_teams',
		'app.series_privileges',
		'app.team'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SeriesTeam = ClassRegistry::init('SeriesTeam');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SeriesTeam);

		parent::tearDown();
	}

}
