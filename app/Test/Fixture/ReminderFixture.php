<?php
/**
 * ReminderFixture
 *
 */
class ReminderFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'main_text' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'subtext' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'reminder_time' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'priority' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'active_status' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'deleted' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'modified_by' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'user_id' => 'Lorem ipsum dolor sit amet',
			'main_text' => 'Lorem ipsum dolor sit amet',
			'subtext' => 'Lorem ipsum dolor sit amet',
			'reminder_time' => '2014-10-07 11:38:07',
			'priority' => 1,
			'active_status' => 1,
			'deleted' => 1,
			'deleted_date' => '2014-10-07 11:38:07',
			'created_by' => 'Lorem ipsum dolor sit amet',
			'modified_by' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-10-07 11:38:07',
			'modified' => '2014-10-07 11:38:07'
		),
	);

}
