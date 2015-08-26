<?php
App::uses('MatchBowlerScore', 'Model');

/**
 * MatchBowlerScore Test Case
 *
 */
class MatchBowlerScoreTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.match_bowler_score',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.city',
		'app.state',
		'app.country',
		'app.series',
		'app.match',
		'app.match_result',
		'app.team',
		'app.image',
		'app.album',
		'app.album_contributor',
		'app.fan_club',
		'app.fan_club_favorite_member',
		'app.fan_club_favorite',
		'app.notification',
		'app.group',
		'app.group_message',
		'app.group_message_comment',
		'app.group_message_like',
		'app.user_group',
		'app.image_comment',
		'app.match_inning_scorecard',
		'app.match_toss',
		'app.team_player',
		'app.team_privilege',
		'app.team_staff',
		'app.team_player_history',
		'app.match_player',
		'app.match_team',
		'app.series_team',
		'app.match_award',
		'app.award_type',
		'app.series_award',
		'app.match_comment',
		'app.match_player_scorecard',
		'app.match_privilege',
		'app.match_staff',
		'app.match_follower',
		'app.match_recommendation',
		'app.series_privilege',
		'app.wall_post',
		'app.video',
		'app.room',
		'app.room_member',
		'app.wall_post_comment',
		'app.wall_post_like',
		'app.player_profile',
		'app.api_key',
		'app.user_friend',
		'app.reminder',
		'app.user_request',
		'app.player_statistic',
		'app.user_favorite',
		'app.fan_club_member'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MatchBowlerScore = ClassRegistry::init('MatchBowlerScore');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MatchBowlerScore);

		parent::tearDown();
	}

}
