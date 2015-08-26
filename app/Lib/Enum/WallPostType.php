<?php
  App::uses('Enum', 'Lib');
  class WallPostType extends Enum {
    const STATUS_UPDATE = 1;
    const TOSS_UPDATE = 2;
    const SCORE_UPDATE = 3;
    const RESULT_UPDATE = 4;

    protected static $_options = array(
      self::STATUS_UPDATE => 'status_update',
      self::TOSS_UPDATE => 'toss_update',
      self::SCORE_UPDATE => 'score_update',
      self::RESULT_UPDATE => 'result_update',
    );
  }