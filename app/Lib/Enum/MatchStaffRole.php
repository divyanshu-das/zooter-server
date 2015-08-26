<?php
  App::uses('Enum', 'Lib');
  class MatchStaffRole extends Enum {
    const FIRST_UMPIRE = 1;
    const SECOND_UMPIRE = 2;
    const THIRD_UMPIRE = 3;
    const RESERVE_UMPIRE = 4;
    const REFEREE = 5;
    const FIRST_SCORER = 6;

  protected static $_options = array(
    self::FIRST_UMPIRE => 'First Umpire',
    self::SECOND_UMPIRE => 'Second Umpire',
    self::THIRD_UMPIRE => 'Third Umpire',
    self::RESERVE_UMPIRE => 'Reserve Umpire',
    self::REFEREE => 'Referee',
    self::FIRST_SCORER => 'First Scorer'
  );
}