<?php
App::uses('UserGroup', 'Model');

/**
 * UserGroup Test Case
 *
 */
class UserGroupTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user_group',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.wall_post',
		'app.wall_post_comment',
		'app.api_key',
		'app.friendship',
		'app.group',
		'app.group_message'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserGroup = ClassRegistry::init('UserGroup');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserGroup);

		parent::tearDown();
	}

}
