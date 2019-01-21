<?php

namespace Drupal\cloudhooks_maintenance\Plugin\Cloudhook;

/**
 * Provides a mechanism to turn off maintenance mode.
 *
 * @Cloudhook(
 *   id = "maintenance_mode_off",
 *   label = @Translation("Turn maintenance mode off"),
 *   description = @Translation("Takes the site out of maintenance mode and flushes the render cache."),
 *   category = @Translation("Basic"),
 *   events = {
 *     "post-code-deploy",
 *     "post-code-update",
 *     "post-db-copy",
 *     "post-files-copy",
 *   }
 * )
 */
class MaintenanceModeOff extends MaintenanceModeBase {

  /**
   * {@inheritdoc}
   */
  protected function getMaintenanceModeState() {
    return FALSE;
  }

}
