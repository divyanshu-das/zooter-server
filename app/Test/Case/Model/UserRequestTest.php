<?php
App::uses('UserRequest', 'Model');

/**
 * UserRequest Test Case
 *
 */
class UserRequestTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user_request'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserRequest = ClassRegistry::init('UserRequest');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserRequest);

		parent::tearDown();
	}

}
