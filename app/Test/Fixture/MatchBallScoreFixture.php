<?php
/**
 * MatchBallScoreFixture
 *
 */
class MatchBallScoreFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'match_inning_scorecard_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'bowler_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'overs' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'over_balls' => array('type' => 'float', 'null' => true, 'default' => '0.0', 'length' => '4,1', 'unsigned' => false),
		'striker_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'non_striker_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'runs' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'runs_taken_by_batsman' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'wides' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'no_balls' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'byes' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'leg_byes' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'is_four' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'is_six' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'is_out' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'is_retired_hurt' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'out_type' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false),
		'out_batsman_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'out_other_by_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
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
			'bowler_id' => 1,
			'overs' => 1,
			'over_balls' => 1,
			'striker_id' => 1,
			'non_striker_id' => 1,
			'runs' => 1,
			'runs_taken_by_batsman' => 1,
			'wides' => 1,
			'no_balls' => 1,
			'byes' => 1,
			'leg_byes' => 1,
			'is_four' => 1,
			'is_six' => 1,
			'is_out' => 1,
			'is_retired_hurt' => 1,
			'out_type' => 1,
			'out_batsman_id' => 1,
			'out_other_by_id' => 1,
			'created' => '2015-03-24 03:56:09',
			'modified' => '2015-03-24 03:56:09'
		),
	);

}
