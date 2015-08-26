<?php
/**
 * UserRequestFixture
 *
 */
class UserRequestFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'type' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'request_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'active_status' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'deleted' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'modified_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'type' => 1,
			'request_id' => 1,
			'active_status' => 1,
			'deleted' => 1,
			'deleted_date' => '2014-10-07 16:04:00',
			'created_by' => 1,
			'modified_by' => 1,
			'created' => '2014-10-07 16:04:00',
			'modified' => '2014-10-07 16:04:00'
		),
	);

}
