<?php
/**
 * ProfileFixture
 *
 */
class ProfileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'middle_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'gender' => array('type' => 'integer', 'null' => true, 'default' => '1', 'unsigned' => false),
		'date_of_birth' => array('type' => 'date', 'null' => true, 'default' => null),
		'address' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'city_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'pincode' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'last_logged_in' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'last_logged_ip' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'id' => '',
			'user_id' => 1,
			'first_name' => 'Lorem ipsum dolor sit amet',
			'middle_name' => 'Lorem ipsum dolor sit amet',
			'last_name' => 'Lorem ipsum dolor sit amet',
			'gender' => 1,
			'date_of_birth' => '2014-06-03',
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'city_id' => 1,
			'pincode' => 1,
			'last_logged_in' => '2014-06-03 15:41:04',
			'last_logged_ip' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-06-03 15:41:04',
			'modified' => '2014-06-03 15:41:04'
		),
	);

}
