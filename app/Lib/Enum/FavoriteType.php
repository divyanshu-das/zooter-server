<?php
  App::uses('Enum', 'Lib');
  class FavoriteType extends Enum {
    const MOVIE = 1;
    const MUSIC = 2;
    const HOBBY = 3;
    const DISH = 4;
    const SINGER = 5;
    const HOLIDAY_DESTINATION = 6;
    const SHOT = 7;
    const SPORTS_PERSONALITY = 8;
    const SPORTS_GROUND = 9;
    const POPULAR_TEAM = 10;
    const OTHER_TEAM = 11;

    protected static $_options = array(
      self::MOVIE => 'Movie',
      self::MUSIC => 'Music',
      self::HOBBY => 'Hobbby',
      self::DISH => 'Dish',
      self::SINGER => 'Singer',
      self::HOLIDAY_DESTINATION => 'Holiday Destination',
      self::SHOT => 'Shot',
      self::SPORTS_PERSONALITY => 'SportsPersonality',
      self::SPORTS_GROUND => 'SportsGround',
      self::POPULAR_TEAM => 'PopularTeam',
      self::OTHER_TEAM => 'OtherTeam'
    );
  }