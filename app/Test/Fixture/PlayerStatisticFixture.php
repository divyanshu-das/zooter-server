<?php
/**
 * PlayerStatisticFixture
 *
 */
class PlayerStatisticFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'is_cricket_ball_used' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'match_scale' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'match_type' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'total_matches' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_runs' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_balls_faced' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_wickets_taken' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_overs_bowled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_wides_bowled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_fours_hit' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_sixes_hit' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_noballs_bowled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_maidens_bowled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_runs_conceded' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'is_cricket_ball_used' => 1,
			'match_scale' => 1,
			'match_type' => 1,
			'total_matches' => 1,
			'total_runs' => 1,
			'total_balls_faced' => 1,
			'total_wickets_taken' => 1,
			'total_overs_bowled' => 1,
			'total_wides_bowled' => 1,
			'total_fours_hit' => 1,
			'total_sixes_hit' => 1,
			'total_noballs_bowled' => 1,
			'total_maidens_bowled' => 1,
			'total_runs_conceded' => 1,
			'created' => '2014-08-10 21:25:37',
			'modified' => '2014-08-10 21:25:37'
		),
	);

}
