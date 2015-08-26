<?php
  abstract class Bitmask {
  protected static $_options;

  public static function intValue($string) {
    return array_search($string, static::$_options);
  }

  public static function stringValue($int) {
    return array_key_exists($int, static::$_options) ? static::$_options[$int] : null;
  }

  public static function options() {
    return static::$_options;
  }
    
  public static function keys() {
    return array_keys(static::$_options);
  }
  
  public static function optionsString($glue = ', ') {
    return join($glue, static::options());
  }

  /**
   * @param array $value Bitmask array.
   * @param mixed $defaultValue Default bitmask value.
   * @return int Bitmask (from APP to DB).
   */

  public static function encodeBitmask($value, $defaultValue = null) {
    $res = 0;
    if (empty($value)) {
      return $defaultValue;
    }
    foreach ((array)$value as $key => $val) {
      $res |= (int)$val;
    }
    if ($res === 0) {
      return $defaultValue; // Make sure notEmpty validation rule triggers
    }
    return $res;
  }
  /**
   * @param int $value Bitmask.
   * @return array Bitmask array (from DB to APP).
   */
  public static function decodeBitmask($value) {
    $res = [];
    $value = (int)$value;
    foreach (static::$_options as $key => $valueString) {
      $val = (($value & $key) !== 0) ? true : false;
      if ($val) {
        $res[] = $key;
      }
    }
    return $res;
  }
  /**
   * @param int $value Bitmask.
   * @return string Bitmask string (from DB to APP).
   */
  public static function decodeBitmaskOptionString($value, $glue = ', ') {
    $res = [];
    $value = (int)$value;
    foreach (static::$_options as $key => $valueString) {
      $val = (($value & $key) !== 0) ? true : false;
      if ($val) {
        $res[] = $valueString;
      }
    }
    return join($glue, $res);
  }
  

}