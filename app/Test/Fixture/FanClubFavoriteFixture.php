<?php
/**
 * FanClubFavoriteFixture
 *
 */
class FanClubFavoriteFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'fan_club_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'favorite_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'type' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'deleted' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'fan_club_id' => 1,
			'favorite_id' => 1,
			'type' => 1,
			'deleted' => 1,
			'deleted_date' => '2014-11-27 04:10:53',
			'created' => '2014-11-27 04:10:53',
			'modified' => '2014-11-27 04:10:53'
		),
	);

}
