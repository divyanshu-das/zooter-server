<?php
App::uses('GroupMessageComment', 'Model');

/**
 * GroupMessageComment Test Case
 *
 */
class GroupMessageCommentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.group_message_comment',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.wall_post',
		'app.wall_post_comment',
		'app.api_key',
		'app.friendship',
		'app.group_message'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GroupMessageComment = ClassRegistry::init('GroupMessageComment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GroupMessageComment);

		parent::tearDown();
	}

}
