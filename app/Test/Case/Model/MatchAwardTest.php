<?php
App::uses('MatchAward', 'Model');

/**
 * MatchAward Test Case
 *
 */
class MatchAwardTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.match_award'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MatchAward = ClassRegistry::init('MatchAward');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MatchAward);

		parent::tearDown();
	}

}
