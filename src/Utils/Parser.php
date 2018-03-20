<?php

namespace PNX\Dashboard\Utils;

/**
 * Parser utility class.
 */
class Parser {

  /**
   * Parses a string value, to return it boolean.
   *
   * @param string $value
   *   The value to parse.
   *
   * @return bool
   *   The boolean value.
   */
  public static function parseBoolean($value) {
    return strtolower($value) == 'y';
  }

}
