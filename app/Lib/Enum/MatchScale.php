
<?php
  App::uses('Enum', 'Lib');
  class MatchType extends Enum {
    const INTERNATIONAL = 1;
    const NATIONAL = 2;
    const STATE = 2;
    const DISTRICT = 2;
    const LOCAL= 2;

      protected static $_options = array(
        self::INTERNATIONAL => 'international',
        self::NATIONAL => 'national',
        self::STATE => 'state',
        self::DISTRICT => 'district',
        self::LOCAL => 'local',
      );
  }