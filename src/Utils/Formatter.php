<?php

namespace PNX\Dashboard\Utils;

/**
 * Provides formatting utilities.
 */
class Formatter {

  /**
   * Formats the timestamp string.
   *
   * If the timestamp is over 24 hours in the past, the returned string is
   * formatted as an error.
   *
   * @param string $timestamp
   *   The timestamp.
   *
   * @return string
   *   The formatted timestamp.
   */
  public static function formatTimestamp($timestamp) {
    $yesterday = time() - (24 * 60 * 60);
    $date      = new \DateTime($timestamp);

    if ($date->getTimestamp() > $yesterday) {
      return $timestamp;
    }
    return "<error>$timestamp</error>";
  }

  /**
   * Formats a boolean as a string.
   *
   * @param bool $value
   *   The boolean value.
   *
   * @return string
   *   The string representation.
   */
  public static function formatBoolean($value) {
    return $value ? 'true' : 'false';
  }

}
