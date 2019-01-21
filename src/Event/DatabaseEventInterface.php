<?php

namespace Drupal\cloudhooks\Event;

/**
 * The interface for all cloudhook events that involve database operations.
 *
 * @package Drupal\cloudhooks\Event
 */
interface DatabaseEventInterface {

  /**
   * Gets the name of the database that was copied.
   *
   * @return string
   *   The name of the database that was copied.
   */
  public function getDatabaseName();

}
