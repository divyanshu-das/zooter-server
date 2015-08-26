<?php
/**
 * PlayerProfileFixture
 *
 */
class PlayerProfileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'batting_arm' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'bowling_arm' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'bowling_style' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'playing_roles' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'user_id' => 1,
			'batting_arm' => 1,
			'bowling_arm' => 1,
			'bowling_style' => 1,
			'playing_roles' => 1,
			'created' => '2014-08-10 21:22:32',
			'modified' => '2014-08-10 21:22:32'
		),
	);

}
