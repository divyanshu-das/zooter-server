<?php
/**
 * RoomFixture
 *
 */
class RoomFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'room_member_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'deleted' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
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
			'name' => 'Lorem ipsum dolor sit amet',
			'room_member_count' => 1,
			'deleted' => 1,
			'deleted_date' => '2015-01-28 01:54:14',
			'created' => '2015-01-28 01:54:14',
			'modified' => '2015-01-28 01:54:14'
		),
	);

}
