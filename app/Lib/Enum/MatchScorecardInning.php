<?php
  App::uses('Enum', 'Lib');
  class MatchScorecardInning extends Enum {
    const FIRST_INNINGS = 1;
    const SECOND_INNINGS = 2;
    const THIRD_INNINGS = 3;
    const FOURTH_INNINGS = 4;

    protected static $_options = array(
      self::FIRST_INNINGS => 'first_Innings',
      self::SECOND_INNINGS => 'second_Innings',
      self::THIRD_INNINGS => 'third_Innings',
      self::FOURTH_INNINGS => 'fourth_Innings',
    );
 }