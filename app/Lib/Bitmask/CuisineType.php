<?php
  App::uses('Bitmask', 'Lib');
  class CuisineType extends Bitmask {
    const NORTH_INDIAN = 1;
    const CHINESE = 2;
    const GOAN = 4;
    const SEAFOOD = 8;
    const CONTINENTAL = 16;
    const FAST_FOOD = 32;
    const ASIAN = 64;
    const THAI = 128;

  protected static $_options = array(
    self::NORTH_INDIAN => 'North Indian',
    self::CHINESE => 'Chinese',
    self::GOAN => 'Goan',
    self::SEAFOOD => 'Seafood',
    self::CONTINENTAL => 'Continental',
    self::FAST_FOOD => 'Fast Food',
    self::ASIAN => 'Asian',
    self::THAI => 'Thai'
  );
}