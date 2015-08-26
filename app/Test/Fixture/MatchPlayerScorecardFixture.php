<?php
/**
 * MatchPlayerScorecardFixture
 *
 */
class MatchPlayerScorecardFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'match_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'inning' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'runs_scored' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'balls_faced' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'fours_hit' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'sixes_hit' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'wicket_taken' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'overs_bowled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'maidens_bowled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'runs_conceded' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'wides_bowled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'no_balls_bowled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'user_id' => 1,
			'inning' => 1,
			'runs_scored' => 1,
			'balls_faced' => 1,
			'fours_hit' => 1,
			'sixes_hit' => 1,
			'wicket_taken' => 1,
			'overs_bowled' => 1,
			'maidens_bowled' => 1,
			'runs_conceded' => 1,
			'wides_bowled' => 1,
			'no_balls_bowled' => 1,
			'created' => '2014-08-10 20:32:17',
			'modified' => '2014-08-10 20:32:17'
		),
	);

}
