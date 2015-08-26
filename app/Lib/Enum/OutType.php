<?php
  App::uses('Enum', 'Lib');
  class OutType extends Enum {
    const BOWLED = 1;
    const CAUGHT = 2;
    const RUNOUT = 3;
    const STUMPED = 4;
    const LBW = 5;
    const HIT_WICKET = 6;
    const HANDLED_THE_BALL = 7;
    const HIT_THE_BALL_TWICE = 8;
    const TIMED_OUT = 9;
    const OBSTRUCTING_THE_FIELD = 10;
    const RETIRED = 11;

  protected static $_options = array(
    self::BOWLED => 'bowled',
    self::CAUGHT => 'caught',
    self::RUNOUT => 'run Out',
    self::STUMPED => 'stumped',
    self::LBW => 'lbw',
    self::HIT_WICKET => 'hit wicket',
    self::HANDLED_THE_BALL => 'handled the ball',
    self::HIT_THE_BALL_TWICE => 'hit the ball twice',
    self::TIMED_OUT => 'timed out',
    self::OBSTRUCTING_THE_FIELD => 'obstructing the field',
    self::RETIRED => 'retired',
  );
}