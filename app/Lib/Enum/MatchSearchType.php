<?php
  App::uses('Enum', 'Lib');
  class MatchSearchType extends Enum {
    const ALL = 1;
    const LOCATION = 2;
    const NAME = 3;
    const DATE = 4;

    protected static $_options = array(
      self::ALL => 'All',
      self::LOCATION => 'Location',
      self::NAME => 'Name',
      self::DATE => 'Date'
    );
  }