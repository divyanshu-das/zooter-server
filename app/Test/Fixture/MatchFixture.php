<?php
/**
 * MatchFixture
 *
 */
class MatchFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'match';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'series_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'scale' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'is_public' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'is_cancelled' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'location_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'series_match_level' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'is_test' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'is_cricket_ball_used' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'over_per_innings' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'series_id' => 1,
			'start_time' => '2014-08-06 11:53:28',
			'end_time' => '2014-08-06 11:53:28',
			'scale' => 'Lorem ipsum dolor sit amet',
			'is_public' => 1,
			'is_cancelled' => 1,
			'location_id' => 1,
			'series_match_level' => 'Lorem ipsum dolor sit amet',
			'is_test' => 1,
			'is_cricket_ball_used' => 1,
			'over_per_innings' => 1,
			'created' => '2014-08-06 11:53:28',
			'modified' => '2014-08-06 11:53:28'
		),
	);

}
