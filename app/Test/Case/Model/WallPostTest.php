<?php
App::uses('WallPost', 'Model');

/**
 * WallPost Test Case
 *
 */
class WallPostTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.wall_post',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.api_key'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->WallPost = ClassRegistry::init('WallPost');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->WallPost);

		parent::tearDown();
	}

}
