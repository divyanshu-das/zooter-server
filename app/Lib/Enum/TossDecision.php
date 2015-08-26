<?php
  App::uses('Enum', 'Lib');
  class TossDecision extends Enum {
    const BATTING = 1;
    const BOWLING = 2;

  protected static $_options = array(
    self::BATTING => 'Batting',
    self::BOWLING => 'Bowling'
  );
}