<?php
  App::uses('Enum', 'Lib');
  class TeamPlayerHistoryStatus extends Enum {
    const ACTIVE = 1;
    const INACTIVE = 0;

    protected static $_options = array(
      self::ACTIVE => 'active',
      self::INACTIVE => 'inactive',
    );
  }