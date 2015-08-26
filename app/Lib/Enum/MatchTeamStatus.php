<?php
  App::uses('Enum', 'Lib');
  class MatchTeamStatus extends Enum {
    const INVITED = 1;
    const ACCEPTED = 2;
    const REJECTED = 3;
    const CONFIRMED = 4;
    const ACTIVE = 5;
    const INACTIVE = 6;
    const PLAYED = 7;

    protected static $_options = array(
      self::INVITED => 'invited',
      self::ACCEPTED => 'accepted',
      self::REJECTED => 'rejected',
      self::CONFIRMED => 'confirmed',
      self::ACTIVE => 'active',
      self::INACTIVE => 'inactive',
      self::PLAYED => 'played',
    );
  }