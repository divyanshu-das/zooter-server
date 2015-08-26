<?php
  App::uses('Enum', 'Lib');
  class SpecialUsers extends Enum {
  	const PUBLIC_USER = 6;

	protected static $_options = array(
		self::PUBLIC_USER => 'public_user'
	);
}