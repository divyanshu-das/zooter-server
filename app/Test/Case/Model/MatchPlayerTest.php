<?php
App::uses('MatchPlayer', 'Model');

/**
 * MatchPlayer Test Case
 *
 */
class MatchPlayerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.match_player',
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
		$this->MatchPlayer = ClassRegistry::init('MatchPlayer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MatchPlayer);

		parent::tearDown();
	}

}
