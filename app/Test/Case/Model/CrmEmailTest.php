<?php
App::uses('CrmEmail', 'Model');

/**
 * CrmEmail Test Case
 *
 */
class CrmEmailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.crm_email'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CrmEmail = ClassRegistry::init('CrmEmail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CrmEmail);

		parent::tearDown();
	}

}
