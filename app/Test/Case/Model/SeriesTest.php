<?php
App::uses('Series', 'Model');

/**
 * Series Test Case
 *
 */
class SeriesTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		'app.series_privileges'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Series = ClassRegistry::init('Series');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Series);

		parent::tearDown();
	}

}
