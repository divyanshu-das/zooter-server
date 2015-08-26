<?php
  App::uses('Enum', 'Lib');
  class Priority extends Enum {
  	const LOW = 1;
  	const MEDIUM = 2;
  	const HIGH = 3;

    protected static $_options = array(
      self::LOW => 'low',
      self::MEDIUM => 'medium',
      self::HIGH => 'high',
    );
  }