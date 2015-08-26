<?php
App::uses('SportsPersonality', 'Model');

/**
 * SportsPersonality Test Case
 *
 */
class SportsPersonalityTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.sports_personality'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SportsPersonality = ClassRegistry::init('SportsPersonality');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SportsPersonality);

		parent::tearDown();
	}

}
