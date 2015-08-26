<?php
  App::uses('Enum', 'Lib');
  class TeamStaffRole extends Enum {
    const COACH = 1;
    const SUPPORT_STAFF = 2;
    const PHYSIO = 3;
    const MANAGER = 4;

  protected static $_options = array(
    self::COACH => 'Coach',
    self::SUPPORT_STAFF => 'Support Staff',
    self::PHYSIO => 'Physio',
    self::MANAGER => 'Manager'
  );
}