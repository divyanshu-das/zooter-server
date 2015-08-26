<?php
  App::uses('Enum', 'Lib');
  class PlayerSearchFilter extends Enum {
  	const TEXT = 1;
  	const FIRST_LETTER = 2;
  	const MINIMUM_MATCHES = 3;
  	const MAXIMUM_MATCHES = 4;
    const LOCATION = 5;
    const LEATHER = 6;
    const TENNIS = 7;

	protected static $_options = array(
		self::TEXT => 'text',
		self::FIRST_LETTER => 'first_letter',
		self::MINIMUM_MATCHES => 'minimum_matches',
		self::MAXIMUM_MATCHES => 'maximum_matches',
		self::LOCATION => 'location',
		self::LEATHER => 'leather',
		self::TENNIS => 'tennis'
	);
}