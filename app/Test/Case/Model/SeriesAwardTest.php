<?php
App::uses('SeriesAward', 'Model');

/**
 * SeriesAward Test Case
 *
 */
class SeriesAwardTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.series_award',
		'app.series',
		'app.location',
		'app.profile',
		'app.user',
		'app.type',
		'app.api_key',
		'app.wall_post',
		'app.wall_post_comment',
		'app.host_country',
		'app.match',
		'app.series_awards',
		'app.series_teams',
		'app.series_privileges',
		'app.award_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SeriesAward = ClassRegistry::init('SeriesAward');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SeriesAward);

		parent::tearDown();
	}

}
