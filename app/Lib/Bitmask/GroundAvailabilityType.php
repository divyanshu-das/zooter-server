<?php
  App::uses('Bitmask', 'Lib');
  class GroundAvailabilityType extends Bitmask {
    const INTERNATIONAL_MATCHES = 1;
    const NATIONAL_MATCHES = 2;
    const CLUB_MATCHES = 4;
    const LOCAL_MATCHES = 8;

  protected static $_options = array(
    self::INTERNATIONAL_MATCHES => 'International Matches',
    self::NATIONAL_MATCHES => 'National Matches',
    self::CLUB_MATCHES => 'Club Matches',
    self::LOCAL_MATCHES => 'Local Matches',
  );
}