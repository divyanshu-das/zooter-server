<?php
  App::uses('Enum', 'Lib');
  class ResultType extends Enum {
    const WON = 1;
    const LOST = 2;
    const TIE = 3;
    const DRAWN = 4;
    const ABANDONED = 5;
    const CANCELLED = 6;

  protected static $_options = array(
    self::WON => 'Won',
    self::LOST => 'Lost',
    self::TIE => 'Tie',
    self::DRAWN => 'Draw',
    self::ABANDONED => 'Abandoned',
    self::CANCELLED => 'Cancelled'
  );
}