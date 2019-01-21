<?php

namespace Drupal\cloudhooks;

/**
 * Interface for cloudhook plugins.
 */
interface CloudhookPluginInterface {

  /**
   * Gets the translated plugin label.
   *
   * @return string
   *   The translated plugin label.
   */
  public function label();

  /**
   * Gets the translated plugin description.
   *
   * @return string
   *   The translated plugin description
   */
  public function description();

  /**
   * Gets the array of events that this plugin is compatible with.
   *
   * @return array|string[]
   *   The array of events that this plugin is compatible with.
   */
  public function getEvents();

}
