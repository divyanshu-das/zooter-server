<?php
App::uses('TeamStaff', 'Model');

/**
 * TeamStaff Test Case
 *
 */
class TeamStaffTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.team_staff',
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
		$this->TeamStaff = ClassRegistry::init('TeamStaff');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TeamStaff);

		parent::tearDown();
	}

}
