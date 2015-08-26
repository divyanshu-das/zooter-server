<?php 
App::uses('Enum','Lib');
	class ReminderStatus extends Enum {
		const ENABLED = 1;
		const DISABLED = 2;
		const RESET = 3;

		protected static $_options = array(
		self::ENABLED => 'enabled',
		self::DISABLED => 'disabled',
		self::RESET => 'reset',
	  );
  }