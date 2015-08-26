<?php
/**
 * TeamPlayerHistoryFixture
 *
 */
class TeamPlayerHistoryFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'team_player_history';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'team_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'active_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'deleted' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4, 'unsigned' => false),
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
			'team_id' => 1,
			'user_id' => 1,
			'active_status' => 1,
			'deleted' => 1,
			'deleted_date' => '2014-12-21 03:44:36',
			'created' => '2014-12-21 03:44:36',
			'modified' => '2014-12-21 03:44:36'
		),
	);

}
