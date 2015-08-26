<?php
  App::uses('Enum', 'Lib');
  class BatsmanStatus extends Enum {
    const STRIKER = 1;
    const NON_STRIKER = 2;
    const OUT = 3;
    const RETIRED_HURT = 4;
    const NOT_OUT = 5;

  protected static $_options = array(
    self::STRIKER => 'striker',
    self::NON_STRIKER => 'non striker',
    self::OUT => 'out',
    self::RETIRED_HURT => 'retired Hurt',
    self::NOT_OUT => 'not Out'
  );
}