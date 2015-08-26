<?php 
App::uses('Enum','Lib');
	class SeriesPrivilegeType extends Enum {
		const ADMIN = 1;
		const NOT_ADMIN = 0;

		protected static $_options = array(
			self::ADMIN => 'admin',
			self::NOT_ADMIN => 'not_admin',
	  );
  }