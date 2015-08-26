<?php
  App::uses('Enum', 'Lib');
  class PlaceEssentialType extends Enum {
    const BUS_STOP = 1;
    const RAILWAY_STATION = 2;
    const AIRPORT = 3;
    const ATM = 4;
    const HOSPITAL = 5;
    const HOSTEL = 6;
    const HOTEL = 7;

  protected static $_options = array(
    self::BUS_STOP => 'Bus',
    self::RAILWAY_STATION => 'Railway',
    self::AIRPORT => 'Airport',
    self::ATM => 'ATM',
    self::HOSPITAL => 'Hospital',
    self::HOSTEL => 'Hostel',
    self::HOTEL => 'Hotel',
  );
}