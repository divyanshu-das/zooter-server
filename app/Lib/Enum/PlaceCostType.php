<?php
  App::uses('Enum', 'Lib');
  class PlaceCostType extends Enum {
    const WEEKLY = 1;
    const MONTHLY = 2;
    const YEARLY = 3;
    const COST_FOR_TWO = 4;
    const WEEKENDS = 5;
    const COST_PER_DAY = 6;

  protected static $_options = array(
    self::WEEKLY => 'Weekly',
    self::MONTHLY => 'Monthly',
    self::YEARLY => 'Yearly',
    self::COST_FOR_TWO => 'Cost for two',
    self::WEEKENDS => 'Weekends',
    self::COST_PER_DAY => 'Cost per day',
  );
}