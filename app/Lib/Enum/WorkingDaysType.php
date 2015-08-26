<?php
  App::uses('Enum', 'Lib');
  class WorkingDaysType extends Enum {
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

  protected static $_options = array(
    self::MONDAY => 'Mon',
    self::TUESDAY => 'Tue',
    self::WEDNESDAY => 'Wed',
    self::THURSDAY => 'Thu',
    self::FRIDAY => 'Fri',
    self::SATURDAY => 'Sat',
    self::SUNDAY => 'Sun'
  );
}