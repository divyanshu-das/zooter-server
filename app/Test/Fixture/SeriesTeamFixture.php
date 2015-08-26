<?php
/**
 * SeriesTeamFixture
 *
 */
class SeriesTeamFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'series_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'invitation_status' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'team_id' => 1,
			'invitation_status' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-07-31 03:00:23',
			'modified' => '2014-07-31 03:00:23'
		),
	);

}
