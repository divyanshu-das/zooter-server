<?php
  App::uses('Enum', 'Lib');
  class PlaceFacilityAccomodation extends Enum {
    const SINGLE = 1;
    const SHARING = 2;
    const NONE = 3;

  protected static $_options = array(
    self::SINGLE => 'Single',
    self::SHARING => 'Sharing',
    self::NONE => 'None',
  );
}