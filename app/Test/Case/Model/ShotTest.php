<?php
App::uses('Shot', 'Model');

/**
 * Shot Test Case
 *
 */
class ShotTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shot'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Shot = ClassRegistry::init('Shot');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Shot);

		parent::tearDown();
	}

}
