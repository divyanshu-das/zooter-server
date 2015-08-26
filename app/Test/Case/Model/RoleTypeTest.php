<?php
App::uses('RoleType', 'Model');

/**
 * RoleType Test Case
 *
 */
class RoleTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.role_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RoleType = ClassRegistry::init('RoleType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RoleType);

		parent::tearDown();
	}

}
