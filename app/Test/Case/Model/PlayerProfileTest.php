<?php
App::uses('PlayerProfile', 'Model');

/**
 * PlayerProfile Test Case
 *
 */
class PlayerProfileTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.player_profile',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.wall_post',
		'app.wall_post_comment',
		'app.api_key'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PlayerProfile = ClassRegistry::init('PlayerProfile');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PlayerProfile);

		parent::tearDown();
	}

}
