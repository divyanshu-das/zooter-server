<?php
  App::uses('Enum', 'Lib');
  class PlaceTypePlural extends Enum {
    const ACADEMIES = 1;
    const CLUBS = 2;
    const SCHOOLS = 3;
    const GROUNDS = 4;
    const SHOPS = 5;
    const SPORTS_BAR = 6;

  protected static $_options = array(
    self::ACADEMIES => 'Academies',
    self::CLUBS => 'Clubs',
    self::SCHOOLS => 'Schools',
    self::GROUNDS => 'Grounds',
    self::SHOPS => 'Shops',
    self::SPORTS_BAR => 'Sports Bar'
  );
}