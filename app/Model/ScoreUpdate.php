<?php
App::uses('AppModel', 'Model');
/**
 * ScoreUpdate Model
 *
 * @property MatchInningScorecard $MatchInningScorecard
 */
class ScoreUpdate extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'MatchInningScorecard' => array(
			'className' => 'MatchInningScorecard',
			'foreignKey' => 'match_inning_scorecard_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
