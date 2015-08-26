<?php
App::uses('Match', 'Model');

/**
 * Match Test Case
 *
 */
class MatchTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		'app.series_privileges'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Match = ClassRegistry::init('Match');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Match);

		parent::tearDown();
	}

}
