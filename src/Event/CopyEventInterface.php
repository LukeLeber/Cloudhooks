<?php

namespace Drupal\cloudhooks\Event;

/**
 * The interface for all cloudhook events that involve copy operations.
 *
 * @package Drupal\cloudhooks\Event
 */
interface CopyEventInterface {

  /**
   * Gets the name of the environment from where the artifact was copied.
   *
   * @return string
   *   The name of the environment from where the artifact was copied.
   */
  public function getSourceEnvironment();

}
