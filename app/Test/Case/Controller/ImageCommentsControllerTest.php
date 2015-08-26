<?php
App::uses('ImageCommentsController', 'Controller');

/**
 * ImageCommentsController Test Case
 *
 */
class ImageCommentsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.image_comment',
		'app.image',
		'app.album',
		'app.user',
		'app.type',
		'app.profile',
		'app.location',
		'app.wall_post',
		'app.wall_post_comment',
		'app.wall_post_like',
		'app.match',
		'app.series',
		'app.series_award',
		'app.award_type',
		'app.match_award',
		'app.series_team',
		'app.team',
		'app.match_inning_scorecard',
		'app.match_result',
		'app.match_toss',
		'app.team_player',
		'app.team_privilege',
		'app.team_staff',
		'app.match_team',
		'app.series_privilege',
		'app.match_comment',
		'app.match_player',
		'app.match_player_scorecard',
		'app.match_privilege',
		'app.match_staff',
		'app.match_follower',
		'app.player_profile',
		'app.api_key',
		'app.user_friend',
		'app.group_message',
		'app.group',
		'app.user_group',
		'app.group_message_comment',
		'app.group_message_like',
		'app.reminder',
		'app.user_request',
		'app.notification',
		'app.fan_club',
		'app.fan_club_favorite_member',
		'app.fan_club_favorite',
		'app.player_statistic',
		'app.album_contributor'
	);

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->markTestIncomplete('testAdminIndex not implemented.');
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$this->markTestIncomplete('testAdminView not implemented.');
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
		$this->markTestIncomplete('testAdminAdd not implemented.');
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$this->markTestIncomplete('testAdminEdit not implemented.');
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
		$this->markTestIncomplete('testAdminDelete not implemented.');
	}

}
