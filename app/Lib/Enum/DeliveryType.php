<?php
  App::uses('Enum', 'Lib');
  class DeliveryType extends Enum {
    const DOT_BALL = 1;
    const WIDE_BALL = 2;
    const NO_BALL = 3;
    const BYES = 4;
    const LEG_BYES = 5;
    const BALL_WITH_RUN_WITHOUT_EXTRA = 6;

  protected static $_options = array(
    self::DOT_BALL => 'Dot Ball',
    self::WIDE_BALL => 'Wide Ball',
    self::NO_BALL => 'No Ball',
    self::BYES => 'Byes',
    self::LEG_BYES => 'Leg Byes',
    self::BALL_WITH_RUN_WITHOUT_EXTRA => 'Ball With Run Without Extra'
  );
}