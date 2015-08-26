<?php 
App::uses('Enum','Lib');
  class NotificationType extends Enum {
    const ZOOT = 1;
    const LIKE = 2;
    const COMMENT = 3;

    protected static $_options = array(
      self::ZOOT => 'zoot',
      self::LIKE => 'like',
      self::COMMENT => 'comment'
    );
  }