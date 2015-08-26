<?php
App::uses('WallPostLike', 'Model');

/**
 * WallPostLike Test Case
 *
 */
class WallPostLikeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.wall_post_like',
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
		$this->WallPostLike = ClassRegistry::init('WallPostLike');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->WallPostLike);

		parent::tearDown();
	}

}
