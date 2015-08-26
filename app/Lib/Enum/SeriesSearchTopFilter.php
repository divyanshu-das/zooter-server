<?php
  App::uses('Enum', 'Lib');
  class SeriesSearchTopFilter extends Enum {
  	const ALL = 1;
    const FOLLOWED = 2;
    const RECOMMENDED = 3;
    const MY = 4;

	protected static $_options = array(
		self::ALL => 'all',
		self::FOLLOWED => 'followed',
		self::RECOMMENDED => 'recommended',
		self::MY => 'my'
	);
}