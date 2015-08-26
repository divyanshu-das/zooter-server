<?php
App::uses('AppModel', 'Model');
/**
 * AwardType Model
 *
 */
class AwardType extends AppModel {


/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'MatchAward' => array(
			'className' => 'MatchAward',
			'foreignKey' => 'award_type_id',
		),
		'SeriesAward' => array(
			'className' => 'SeriesAward',
			'foreignKey' => 'award_type_id',
		)
	);


}
