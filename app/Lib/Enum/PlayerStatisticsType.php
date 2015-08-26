<?php
  App::uses('Enum', 'Lib');
  class PlayerStatisticsType extends Enum {
    const FULL_CAREER = 1;
    const CAREER_HIGHLIGHTS = 2;

    protected static $_options = array(
      self::FULL_CAREER => 'FullCareer',
      self::CAREER_HIGHLIGHTS => 'CareerHighlights',
    );
  }