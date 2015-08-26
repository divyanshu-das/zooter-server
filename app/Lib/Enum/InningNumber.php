<?php
  App::uses('Enum', 'Lib');
  class InningNumber extends Enum {
    const FIRST = 1;
    const SECOND = 2;
    const THIRD = 3;
    const FOURTH = 4;

  protected static $_options = array(
    self::FIRST => 'First',
    self::SECOND => 'Second',
    self::THIRD => 'Third',
    self::FOURTH => 'Fourth'
  );
}