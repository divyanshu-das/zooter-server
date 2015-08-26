<?php
/**
 * MatchBatsmanScoreFixture
 *
 */
class MatchBatsmanScoreFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'match_inning_score_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'runs' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'balls' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'fours' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'sixes' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'status' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 3, 'unsigned' => false),
		'out_type' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 3, 'unsigned' => false),
		'out_by_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
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
			'match_inning_score_id' => 1,
			'user_id' => 1,
			'runs' => 1,
			'balls' => 1,
			'fours' => 1,
			'sixes' => 1,
			'status' => 1,
			'out_type' => 1,
			'out_by_id' => 1,
			'out_other_by_id' => 1,
			'created' => '2015-03-13 20:01:25',
			'modified' => '2015-03-13 20:01:25'
		),
	);

}
