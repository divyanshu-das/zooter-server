<?php
App::uses('VerifiedUser', 'Model');

/**
 * VerifiedUser Test Case
 *
 */
class VerifiedUserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.verified_user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->VerifiedUser = ClassRegistry::init('VerifiedUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->VerifiedUser);

		parent::tearDown();
	}

}
