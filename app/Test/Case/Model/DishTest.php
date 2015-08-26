<?php
App::uses('Dish', 'Model');

/**
 * Dish Test Case
 *
 */
class DishTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.dish'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Dish = ClassRegistry::init('Dish');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Dish);

		parent::tearDown();
	}

}
