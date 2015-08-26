<?php
  App::uses('Enum', 'Lib');
  class FollowerStatus extends Enum {
    const FOLLOWING = 1;
    const UNFOLLOWED = 2;

  protected static $_options = array(
    self::FOLLOWING => 'Following',
    self::UNFOLLOWED => 'UnFollowed',
  );
}