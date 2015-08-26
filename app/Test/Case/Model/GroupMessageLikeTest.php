<?php
App::uses('GroupMessageLike', 'Model');

/**
 * GroupMessageLike Test Case
 *
 */
class GroupMessageLikeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.group_message_like',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.wall_post',
		'app.wall_post_comment',
		'app.api_key',
		'app.friendship',
		'app.group_message',
		'app.group',
		'app.group_message_comment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GroupMessageLike = ClassRegistry::init('GroupMessageLike');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GroupMessageLike);

		parent::tearDown();
	}

}
