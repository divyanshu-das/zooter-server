<?php
/**
 * AlbumContributorFixture
 *
 */
class AlbumContributorFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'album_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'album_id' => 1,
			'user_id' => 1,
			'deleted' => 1,
			'deleted_date' => '2014-11-27 03:12:41',
			'created' => '2014-11-27 03:12:41',
			'modified' => '2014-11-27 03:12:41'
		),
	);

}
