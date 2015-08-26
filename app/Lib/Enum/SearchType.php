<?php
  App::uses('Enum', 'Lib');
  class SearchType extends Enum {
    const ALL = 1;
    const MATCHES = 2;
    const TEAMS = 3;
    const PEOPLE = 4;
    const GROUPS = 5;
    const FANCLUBS = 6;
    const FRIENDS = 7;

    protected static $_options = array(
      self::ALL => 'All',
      self::MATCHES => 'Matches',
      self::TEAMS => 'Teams',
      self::PEOPLE => 'People',
      self::GROUPS => 'Groups',
      self::FANCLUBS => 'FanClubs',
      self::FRIENDS => 'Friends'
    );
  }