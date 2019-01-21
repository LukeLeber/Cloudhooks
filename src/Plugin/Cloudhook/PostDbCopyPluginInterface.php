<?php

namespace Drupal\cloudhooks\Plugin\Cloudhook;

use Drupal\cloudhooks\CloudhookPluginInterface;

/**
 * Interface PostDbCopyInterface.
 *
 * @package Drupal\cloudhooks\Cloudhook
 */
interface PostDbCopyPluginInterface extends CloudhookPluginInterface {

  /**
   * Fires on post-db-copy.
   *
   * @param string $application
   *   The application that the action has occurred on.
   * @param string $environment
   *   The environment that the action has occurred on.
   * @param string $database_name
   *   The name of the database that was copied.
   * @param string $source_environment
   *   The name of the environment that the database was copied from.
   */
  public function onPostDatabaseCopy($application, $environment, $database_name, $source_environment);

}
