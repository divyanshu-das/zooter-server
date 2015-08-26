<?php
  App::uses('Enum', 'Lib');
  class MatchLevel extends Enum {
    const TEST = 1;
    const ODI = 2;
    const T20 = 3;
    const FIRST_CLASS = 4;
    const LIST_A = 5;

  protected static $_options = array(
    self::TEST => 'Test',
    self::ODI => 'ODI',
    self::T20 => 'Twenty20',
    self::FIRST_CLASS => 'First Class',
    self::LIST_A => 'List A'
  );
}