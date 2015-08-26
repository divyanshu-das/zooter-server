<?php
  App::uses('Enum', 'Lib');
  class WallPostCategory extends Enum {
    const USER = 1;
    const MATCH = 2;
    const TEAM = 3;
    const SERIES = 4;

    protected static $_options = array(
      self::USER => 'User',
      self::MATCH => 'Match',
      self::TEAM => 'Team',
      self::SERIES => 'Series',
    );
  }