<?php
  App::uses('Enum', 'Lib');
  class MatchResultType extends Enum {
    const ABANDONED = 1;
    const WON = 2;
    const LOST = 3;
    const CANCELLED = 4;
    const DRAW = 5;

    protected static $_options = array(
      self::ABANDONED => 'abandoned',
      self::WON => 'won',
      self::LOST => 'lost',
      self::CANCELLED => 'cancelled',
      self::DRAW => 'Draw'
    );
  }