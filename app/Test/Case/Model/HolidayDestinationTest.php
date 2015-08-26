<?php
App::uses('HolidayDestination', 'Model');

/**
 * HolidayDestination Test Case
 *
 */
class HolidayDestinationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.holiday_destination'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->HolidayDestination = ClassRegistry::init('HolidayDestination');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->HolidayDestination);

		parent::tearDown();
	}

}
