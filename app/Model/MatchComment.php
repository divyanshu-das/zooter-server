<?php
App::uses('AppModel', 'Model');
/**
 * MatchComment Model
 *
 * @property Match $Match
 * @property User $comment_by
 */
class MatchComment extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'match_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Comment_by' => array(
			'className' => 'User',
			'foreignKey' => 'comment_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
