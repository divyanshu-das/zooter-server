<?php
App::uses('Group', 'Model');

/**
 * Group Test Case
 *
 */
class GroupTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.group',
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
		$this->Group = ClassRegistry::init('Group');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Group);

		parent::tearDown();
	}

}
