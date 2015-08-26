<?php
/**
 * PlaceFacilityFixture
 *
 */
class PlaceFacilityFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'),
		'place_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'ground_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'bowling_machine_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'coach_student_ratio' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'accomodation' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'transport' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'turf_nets' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'cement_nets' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'mat_nets' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'cuisine' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'has_individual_classes' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'has_medical_facilities' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'has_gym' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'has_food' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'has_karyoke' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'has_wifi' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'accept_credit_card' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'deleted' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
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
			'place_id' => 1,
			'ground_id' => 1,
			'bowling_machine_count' => 1,
			'coach_student_ratio' => 1,
			'accomodation' => 1,
			'transport' => 1,
			'turf_nets' => 1,
			'cement_nets' => 1,
			'mat_nets' => 1,
			'cuisine' => 1,
			'has_individual_classes' => 1,
			'has_medical_facilities' => 1,
			'has_gym' => 1,
			'has_food' => 1,
			'has_karyoke' => 1,
			'has_wifi' => 1,
			'accept_credit_card' => 1,
			'deleted' => 1,
			'deleted_date' => '2015-06-13 00:44:18',
			'created' => '2015-06-13 00:44:18',
			'modified' => '2015-06-13 00:44:18'
		),
	);

}
