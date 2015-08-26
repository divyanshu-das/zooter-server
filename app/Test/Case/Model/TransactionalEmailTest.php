<?php
App::uses('TransactionalEmail', 'Model');

/**
 * TransactionalEmail Test Case
 *
 */
class TransactionalEmailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.transactional_email'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TransactionalEmail = ClassRegistry::init('TransactionalEmail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TransactionalEmail);

		parent::tearDown();
	}

}
