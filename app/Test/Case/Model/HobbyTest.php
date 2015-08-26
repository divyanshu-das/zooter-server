<?php
App::uses('Hobby', 'Model');

/**
 * Hobby Test Case
 *
 */
class HobbyTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.hobby'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Hobby = ClassRegistry::init('Hobby');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Hobby);

		parent::tearDown();
	}

}
