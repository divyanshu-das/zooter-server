<?php
/**
 * PlaceTimingFixture
 *
 */
class PlaceTimingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'),
		'place_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'day_of_week' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'time_open' => array('type' => 'time', 'null' => false, 'default' => null),
		'working_time' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'deleted' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'place_id' => 1,
			'day_of_week' => 1,
			'time_open' => '02:18:23',
			'working_time' => 1,
			'deleted' => 1,
			'deleted_date' => '2015-07-04 02:18:23',
			'created' => '2015-07-04 02:18:23',
			'modified' => '2015-07-04 02:18:23'
		),
	);

}
