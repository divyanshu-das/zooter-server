<?php
  App::uses('Enum', 'Lib');
  class SeriesSearchSubFilter extends Enum {
  	const TEXT = 1;
    const START_DATE = 2;
    const END_DATE = 3;
    const UPCOMING = 4;
    const ONGOING = 5;
    const FINISHED = 6;
    const LOCATION = 7;

	protected static $_options = array(
		self::TEXT => 'text',
		self::START_DATE => 'start_date',
		self::END_DATE => 'end_date',
		self::UPCOMING => 'upcoming',
		self::ONGOING => 'ongoing',
		self::FINISHED => 'finished',
		self::LOCATION => 'location'
	);
}