<?php

namespace Drupal\cloudhooks_maintenance\Plugin\Cloudhook;

/**
 * Provides a mechanism to turn on maintenance mode.
 *
 * @Cloudhook(
 *   id = "maintenance_mode_on",
 *   label = @Translation("Turn maintenance mode on"),
 *   description = @Translation("Puts the site into maintenance mode and flushes the render cache."),
 *   category = @Translation("Basic"),
 *   events = {
 *     "post-code-deploy",
 *     "post-code-update",
 *     "post-db-copy",
 *     "post-files-copy",
 *   }
 * )
 */
class MaintenanceModeOn extends MaintenanceModeBase {

  /**
   * {@inheritdoc}
   */
  protected function getMaintenanceModeState() {
    return TRUE;
  }

}
