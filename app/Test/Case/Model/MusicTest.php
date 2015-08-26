<?php
App::uses('Music', 'Model');

/**
 * Music Test Case
 *
 */
class MusicTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.music'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Music = ClassRegistry::init('Music');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Music);

		parent::tearDown();
	}

}
