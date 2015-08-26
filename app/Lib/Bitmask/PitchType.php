<?php
  App::uses('Bitmask', 'Lib');
  class PitchType extends Bitmask {
    const TURF = 1;
    const ARTIFICIAL_TURF = 2;
    const MAT = 4;

  protected static $_options = array(
    self::ARTIFICIAL_TURF => 'Turf',
    self::TURF => 'Artifical turf',
    self::MAT => 'Mat'
  );
}