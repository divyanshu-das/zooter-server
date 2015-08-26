<?php
App::uses('AwardType', 'Model');

/**
 * AwardType Test Case
 *
 */
class AwardTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.award_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AwardType = ClassRegistry::init('AwardType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AwardType);

		parent::tearDown();
	}

}
