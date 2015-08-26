<?php
/**
 * MatchInningScorecardFixture
 *
 */
class MatchInningScorecardFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'match_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'inning' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_runs' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'overs_consumed' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'wickets_fallen' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'wide_balls' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'leg_byes' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'no_balls' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'match_id' => 1,
			'inning' => 1,
			'team_id' => 1,
			'total_runs' => 1,
			'overs_consumed' => 1,
			'wickets_fallen' => 1,
			'wide_balls' => 1,
			'leg_byes' => 1,
			'no_balls' => 1,
			'created' => '2014-08-10 20:30:23',
			'modified' => '2014-08-10 20:30:23'
		),
	);

}
