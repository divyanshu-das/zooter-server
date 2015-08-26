<?php
App::uses('WallPostComment', 'Model');

/**
 * WallPostComment Test Case
 *
 */
class WallPostCommentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.wall_post_comment',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.wall_post',
		'app.api_key'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->WallPostComment = ClassRegistry::init('WallPostComment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->WallPostComment);

		parent::tearDown();
	}

}
