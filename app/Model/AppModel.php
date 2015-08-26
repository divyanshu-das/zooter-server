<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	public $recursive = -1;
	public $actsAs = array('Containable','SoftDelete','CustomValidation');

	public function createChecksum($data){
		$raw_data =  implode ('.',$data);
		$checksum = sha1($raw_data);
		return $checksum;
	}

	protected function _userExists($userId) {
		App::import('model', 'User');
		$User = new User();
		return $User->find('count', array(
			'conditions' => array(
				'User.id' => $userId
			)
		));
	}

	protected function _teamExists($teamId) {
		App::import('model', 'Team');
		$Team = new Team();
		return $Team->find('count', array(
			'conditions' => array(
				'Team.id' => $teamId
			)
		));
	}

	protected function _matchExists($matchId) {
		App::import('model', 'Match');
		$Match = new Match();
		return $Match->find('count', array(
			'conditions' => array(
				'Match.id' => $matchId
			)
		));
	}

	protected function _seriesExists($seriesId) {
		App::import('model', 'Series');
		$Series = new Series();
		return $Series->find('count', array(
			'conditions' => array(
				'Series.id' => $seriesId
			)
		));
	}

	protected function _prepareRunRate($runs,$overs) {
		if (empty($overs) || $overs == 0) {
			return 'NA';
		} else if (empty($runs) || $runs == 0) {
			return 0.0;
		} else {
			$oversInBalls = ( ((((int)$overs) * 10) / 10) * 6) + (($overs * 10) % 10);
			return round( ( ($runs / $oversInBalls) * 6),1,PHP_ROUND_HALF_UP );
		}
	}

	protected function _prepareUserName($firstName, $middleName, $lastName){
    $name = NULL;
    if (!empty($firstName)) {
      $name = $firstName." ";
    }
    if (!empty($middleName)) {
      $name = $name.$middleName." ";
    }
    if (!empty($lastName)) {
      $name = $name.$lastName." ";
    }
    return $name;
  }
  protected function __getTimeDifferenceInMinutes($startTime, $endTime) {
		$openingTime = strtotime($startTime);
		$closingTime = strtotime($endTime);
		$timeDiffInSeconds = $closingTime - $openingTime;
		if ($timeDiffInSeconds <= 0) {
			$closingTime = strtotime('tomorrow' . $endTime);
		}
		$timeDiffInSeconds = abs($closingTime - $openingTime);
		$timeDiffInMniutes = abs($timeDiffInSeconds / 60);
		return $timeDiffInMniutes;
	}
	protected function __getEndTime($startTime, $timeDiffInMniutes) {
		$time = date('H:i A', strtotime('+' . $timeDiffInMniutes . 'minutes', strtotime($startTime)));
		return $time;
	}
	public function sendMandrillEmail($template, $toEmail, $fromEmail, $emailVars, $subjectText, $emailAttachment = ''){
    $email = new CakeEmail();
    $email->config('mandrill')
          ->to($toEmail)
          ->from($fromEmail)
          ->viewVars($emailVars);
    $email->subject($subjectText);
    if(!empty($emailAttachment)){
        $email->attachments($emailAttachment);
    }
    if(!empty($template)){
      $email->config(array('mandrillTemplate' => $template));   
    }
    $result = $email->send();
    return $result;
  }
}
