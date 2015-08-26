<?php
  App::uses('Enum', 'Lib');
  class TeamBattingStatus extends Enum {
    const UPCOMING = 1;
    const ONGOING = 2;
    const FINISHED = 3;

	protected static $_options = array(
		self::UPCOMING => 'upcoming',
		self::ONGOING => 'ongoing',
		self::FINISHED => 'finished',
	);
}