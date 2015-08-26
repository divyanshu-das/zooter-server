<?php
  App::uses('Enum', 'Lib');
  class MatchSearchSubFilter extends Enum {
  	const TEXT = 1;
    const DATE = 2;
    const UPCOMING = 3;
    const ONGOING = 4;
    const FINISHED = 5;
    const LOCATION = 6;
    const LEATHER = 7;
    const TENNIS = 8;

	protected static $_options = array(
		self::TEXT => 'text',
		self::DATE => 'date',
		self::UPCOMING => 'upcoming',
		self::ONGOING => 'ongoing',
		self::FINISHED => 'finished',
		self::LOCATION => 'location',
		self::LEATHER => 'leather',
		self::TENNIS => 'tennis'
	);
}