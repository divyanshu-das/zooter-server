<?php
/**
 * MatchBowlerScoreFixture
 *
 */
class MatchBowlerScoreFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'match_inning_score_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'overs' => array('type' => 'float', 'null' => true, 'default' => '0.0', 'length' => '4,1', 'unsigned' => false),
		'maidens' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'dot_balls' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'wickets' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'runs_conceded' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'no_balls' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'wides' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
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
			'match_inning_score_id' => 1,
			'user_id' => 1,
			'overs' => 1,
			'maidens' => 1,
			'dot_balls' => 1,
			'wickets' => 1,
			'runs_conceded' => 1,
			'no_balls' => 1,
			'wides' => 1,
			'created' => '2015-03-13 20:03:24',
			'modified' => '2015-03-13 20:03:24'
		),
	);

}
