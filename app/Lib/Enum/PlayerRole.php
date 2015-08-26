<?php
  App::uses('Enum', 'Lib');
  class PlayerRole extends Enum {
    const BATSMAN = 1;
    const BOWLER = 2;
    const ALLROUNDER = 3;
    const WICKETKEEPER = 4;
    const TWELFTH_MAN = 5;

  protected static $_options = array(
    self::BATSMAN => 'Batsman',
    self::BOWLER => 'Bowler',
    self::ALLROUNDER => 'All Rounder',
    self::WICKETKEEPER => 'Wicket Keeper',
    self::TWELFTH_MAN => 'Twelfth Man'
  );
}