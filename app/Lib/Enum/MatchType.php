<?php
  App::uses('Enum', 'Lib');
  class MatchType extends Enum {
    const UPCOMING = 1;
    const CURRENT = 2;
    const FINISHED = 3;
    const RECOMMENDED = 4;
    const FOLLOWING = 5;
    const ODI = 6;
    const T20 = 7;
    const TEST = 8;
    const TENNIS_BALL = 9;
    const CRICKET_BALL = 10;

	protected static $_options = array(
		self::UPCOMING => 'upcoming',
		self::CURRENT => 'current',
		self::FINISHED => 'finished',
        self::RECOMMENDED => 'recommended',
        self::FOLLOWING => 'following',
        self::ODI => 'odi',
        self::T20 => 't20',
        self::TEST => 'test',
        self::TENNIS_BALL => 'tennis',
		self::CRICKET_BALL => 'leather',
	);
}