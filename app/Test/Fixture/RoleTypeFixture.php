<?php
/**
 * RoleTypeFixture
 *
 */
class RoleTypeFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'role_type';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'role' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'bit_position' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 2, 'unsigned' => false),
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
			'role' => 1,
			'bit_position' => 1,
			'created' => '2014-08-06 11:44:49',
			'modified' => '2014-08-06 11:44:49'
		),
	);

}
