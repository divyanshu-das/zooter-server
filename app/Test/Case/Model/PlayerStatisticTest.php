<?php
App::uses('PlayerStatistic', 'Model');

/**
 * PlayerStatistic Test Case
 *
 */
class PlayerStatisticTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.player_statistic',
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
		$this->PlayerStatistic = ClassRegistry::init('PlayerStatistic');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PlayerStatistic);

		parent::tearDown();
	}

}
