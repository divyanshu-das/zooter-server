<?php
/**
 * ScoreUpdateFixture
 *
 */
class ScoreUpdateFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'match_inning_scorecard_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'total_runs' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'overs' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'wickets' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'wide_balls' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'leg_byes' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'byes' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'no_balls' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'match_inning_scorecard_id' => 1,
			'total_runs' => 1,
			'overs' => 1,
			'wickets' => 1,
			'wide_balls' => 1,
			'leg_byes' => 1,
			'byes' => 1,
			'no_balls' => 1,
			'deleted' => 1,
			'deleted_date' => '2014-09-26 16:46:14',
			'created' => '2014-09-26 16:46:14',
			'modified' => '2014-09-26 16:46:14'
		),
	);

}
