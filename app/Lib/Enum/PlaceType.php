<?php
  App::uses('Enum', 'Lib');
  class PlaceType extends Enum {
    const ACADEMY = 1;
    const CLUB = 2;
    const SCHOOL = 3;
    const GROUND = 4;
    const SHOP = 5;
    const SPORTS_BAR = 6;

  protected static $_options = array(
    self::ACADEMY => 'Academy',
    self::CLUB => 'Club',
    self::SCHOOL => 'School',
    self::GROUND => 'Ground',
    self::SHOP => 'Shop',
    self::SPORTS_BAR => 'Sports bar'
  );
}