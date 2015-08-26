<?php
  App::uses('Enum', 'Lib');
  class UserLevel extends Enum {
    const ADMINISTRATOR = 100;
    const MEMBER = 1;

  protected static $_options = array(
    self::ADMINISTRATOR => 'Administrator',
    self::MEMBER => 'Member'
  );
}