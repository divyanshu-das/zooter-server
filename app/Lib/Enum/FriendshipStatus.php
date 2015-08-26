<?php
  App::uses('Enum', 'Lib');
  class FriendshipStatus extends Enum {
  	const INVITED = 1;
    const ACCEPTED = 2;
    const REJECTED = 3;
    const BLOCKED = 4;
    const PENDING = 5;
    const UNFRIEND = 6;
    const NOT_A_FRIEND = 7;

	protected static $_options = array(
		self::INVITED => 'invited',
		self::ACCEPTED => 'accepted',
		self::REJECTED => 'rejected',
		self::BLOCKED => 'blocked',
		self::PENDING => 'pending',
		self::UNFRIEND => 'unfriend',
		self::NOT_A_FRIEND => 'not_a_friend'
	);
}