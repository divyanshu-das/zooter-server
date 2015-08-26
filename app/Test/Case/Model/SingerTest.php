<?php
App::uses('Singer', 'Model');

/**
 * Singer Test Case
 *
 */
class SingerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.singer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Singer = ClassRegistry::init('Singer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Singer);

		parent::tearDown();
	}

}
