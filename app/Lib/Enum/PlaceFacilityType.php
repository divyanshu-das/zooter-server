<?php
  App::uses('Enum', 'Lib');
  class PlaceFacilityType extends Enum {
    const INDIVIDUAL_CLASSES = 1;
    const MEDICAL_FACILITIES = 2;
    const GYM = 3;
    const FOOD = 4;
    const KARYOKE = 5;
    const WIFI = 6;
    const CREDIT_CARD = 7;
    const CRICKET_CORNER = 8;
    const HOME_DELIVERY = 9;
    const DINE_IN = 10;
    const NON_VEG = 11;
    const ALCOHOL = 12;
    const AC = 13;
    const SMOKING_AREA = 14;
    const FLOODLIGHTS = 15;
    const TOILETS = 16;


  protected static $_options = array(
    self::INDIVIDUAL_CLASSES => 'Individual Classes',
    self::MEDICAL_FACILITIES => 'Medical Facilities',
    self::GYM => 'Gym',
    self::FOOD => 'Food',
    self::KARYOKE => 'Karyoke',
    self::WIFI => 'Wi-Fi',
    self::CREDIT_CARD => 'Credit Card',
    self::CRICKET_CORNER => 'Cricket Corner',
    self::HOME_DELIVERY => 'Home Delivery',
    self::DINE_IN => 'Dine In',
    self::NON_VEG => 'Non Veg',
    self::ALCOHOL => 'Alcohol',
    self::AC => 'A/c',
    self::SMOKING_AREA => 'Smoking Area',
    self::FLOODLIGHTS => 'Floodlights',
    self::TOILETS => 'Toilets'
  );
}