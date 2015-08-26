<?php 
App::uses('Enum','Lib');
  class UserType extends Enum {
    const PROFESSIONAL = 1;
    const AMATEUR = 2;
    const DATA_COLLECTOR = 3;

    protected static $_options = array(
      self::PROFESSIONAL => 'professional',
      self::AMATEUR => 'amateur',
      self::DATA_COLLECTOR => 'Data Collector'
    );
  }