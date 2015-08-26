<?php
  App::uses('Enum', 'Lib');
  class TeamSearchSubFilter extends Enum {
  	const TEXT = 1;
    const LOCATION = 2;

	protected static $_options = array(
		self::TEXT => 'text',
		self::LOCATION => 'location'
	);
}