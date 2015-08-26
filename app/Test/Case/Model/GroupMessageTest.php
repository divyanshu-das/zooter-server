<?php
App::uses('GroupMessage', 'Model');

/**
 * GroupMessage Test Case
 *
 */
class GroupMessageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.group_message',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.wall_post',
		'app.wall_post_comment',
		'app.api_key',
		'app.friendship',
		'app.group',
		'app.group_message_comment',
		'app.group_message_like'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GroupMessage = ClassRegistry::init('GroupMessage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GroupMessage);

		parent::tearDown();
	}

}
