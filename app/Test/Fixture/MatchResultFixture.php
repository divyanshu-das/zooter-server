<?php
/**
 * MatchResultFixture
 *
 */
class MatchResultFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'match_result';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'match_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'winning_team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'result_type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'winning_team_id' => 1,
			'result_type' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-08-06 12:00:18',
			'modified' => '2014-08-06 12:00:18'
		),
	);

}
