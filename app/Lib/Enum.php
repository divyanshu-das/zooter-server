<?php
  abstract class Enum {
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
}