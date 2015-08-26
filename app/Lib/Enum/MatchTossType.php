<?php
  App::uses('Enum', 'Lib');
  class MatchTossType extends Enum {
    const BATTING_FIRST = 1;
    const BOWLING_FIRST = 2;

    protected static $_options = array(
      self::BATTING_FIRST => 'batting_first',
      self::BOWLING_FIRST => 'bowling_first',
    );
  }