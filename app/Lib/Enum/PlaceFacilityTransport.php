<?php
  App::uses('Enum', 'Lib');
  class PlaceFacilityTransport extends Enum {
    const PICK_UP = 1;
    const DROP_OFF = 2;
    const BOTH = 3;
    const NONE = 4;

  protected static $_options = array(
    self::PICK_UP => 'Pick Up',
    self::DROP_OFF => 'Drop Off',
    self::BOTH => 'Both',
    self::NONE => 'None',
  );
}