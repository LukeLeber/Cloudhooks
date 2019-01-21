<?php

namespace Drupal\cloudhooks\Event;

/**
 * The ultimate base interface for all cloudhook events.
 *
 * @package Drupal\cloudhooks\Event
 */
interface CloudhookEventInterface {

  /**
   * Retrieves the name of the application.
   *
   * @return string
   *   The name of the application.
   */
  public function getApplication();

  /**
   * Retrieves the name of the environment.
   *
   * @return string
   *   The name of the environment.
   */
  public function getEnvironment();

}
