<?php
  App::uses('Enum', 'Lib');
  class TeamSearchType extends Enum {
    const ALL = 1;
    const NAME = 2;
    const LOCATION = 3;

    protected static $_options = array(
      self::ALL => 'All',
      self::NAME => 'Name',
      self::LOCATION => 'Location'
    );
  }