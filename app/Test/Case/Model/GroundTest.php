<?php
App::uses('Ground', 'Model');

/**
 * Ground Test Case
 *
 */
class GroundTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.ground'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Ground = ClassRegistry::init('Ground');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Ground);

		parent::tearDown();
	}

}
