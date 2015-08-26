<?php
  App::uses('Enum', 'Lib');
    class PlayerProfileType extends Enum {
      const RIGHT = 1;
      const LEFT = 2;
      const FAST = 3;
      const MEDIUM_PACE = 4;
      const SLOW = 5;
      const OFF_BREAK = 6;
      const LEG_BREAK = 7;

      protected static $_options = array(
      self::RIGHT => 'right',
      self::LEFT => 'left',
      self::FAST => 'fast',
      self::MEDIUM_PACE => 'medium pace',
      self::SLOW => 'slow',
      self::OFF_BREAK => 'off break',
      self::LEG_BREAK => 'leg break'
    );
  }