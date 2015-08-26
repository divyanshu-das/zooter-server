<?php
  App::uses('Enum', 'Lib');
  class InvitationStatus extends Enum {
    const INVITED = 1;
    const ACCEPTED = 2;
    const REJECTED = 3;
    const CONFIRMED = 4;
    const ACTIVE = 5;
    const INACTIVE = 6;
    const PLAYED = 7;
    const REMOVED = 8;
    const NOT_INVITED = 9;
    const BLOCKED =10;
    const UNBLOCKED = 11;
    const REQUEST_PENDING = 12;

    protected static $_options = array(
      self::INVITED => 'invited',
      self::ACCEPTED => 'accepted',
      self::REJECTED => 'rejected',
      self::CONFIRMED => 'confirmed',
      self::ACTIVE => 'active',
      self::INACTIVE => 'inactive',
      self::PLAYED => 'played',
      self::REMOVED => 'removed',
      self::NOT_INVITED => 'not_invited',
      self::BLOCKED => 'blocked',
      self::UNBLOCKED => 'unblocked',
      self::REQUEST_PENDING => 'request_pending'
    );
  }