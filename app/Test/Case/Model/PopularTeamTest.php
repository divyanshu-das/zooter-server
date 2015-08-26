<?php
App::uses('PopularTeam', 'Model');

/**
 * PopularTeam Test Case
 *
 */
class PopularTeamTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.popular_team'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PopularTeam = ClassRegistry::init('PopularTeam');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PopularTeam);

		parent::tearDown();
	}

}
