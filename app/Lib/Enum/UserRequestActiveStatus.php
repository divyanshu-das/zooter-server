<?php 
App::uses('Enum','Lib');
	class UserRequestActiveStatus extends Enum {
		const INACTIVE = 0;
		const ACTIVE = 1;

		protected static $_options = array(
			self::INACTIVE => 'inactive',
			self::ACTIVE => 'active',
	  );
  }