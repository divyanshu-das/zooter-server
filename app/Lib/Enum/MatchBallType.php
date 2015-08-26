<?php
  App::uses('Enum', 'Lib');
  class MatchBallType extends Enum {
    const TENNIS_BALL = 1;
    const CRICKET_BALL = 2;

	protected static $_options = array(
    self::TENNIS_BALL => 'tennis',
		self::CRICKET_BALL => 'leather',
	);
}