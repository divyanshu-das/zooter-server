<?php
/**
 * MatchTossFixture
 *
 */
class MatchTossFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'match_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'winning_team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'toss_decision' => array('type' => 'boolean', 'null' => false, 'default' => null),
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
			'toss_decision' => 1,
			'created' => '2014-08-13 21:19:07',
			'modified' => '2014-08-13 21:19:07'
		),
	);

}
