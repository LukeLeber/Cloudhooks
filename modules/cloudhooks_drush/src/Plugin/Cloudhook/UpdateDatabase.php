<?php

namespace Drupal\cloudhooks_drush\Plugin\Cloudhook;

use Drupal\cloudhooks\Plugin\Cloudhook\PostCodeDeployPluginInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Provides a mechanism to turn off maintenance mode.
 *
 * @Cloudhook(
 *   id = "update_database",
 *   label = @Translation("Update the drupal database"),
 *   description = @Translation("Runs all pending database updates."),
 *   category = @Translation("Basic"),
 *   events = {
 *     "post-code-deploy",
 *     "post-code-update",
 *     "post-db-copy",
 *   }
 * )
 */
class UpdateDatabase extends CloudhookDrushPluginBase implements PostCodeDeployPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function onPostCodeDeploy($application, $environment, $source_branch, $deployed_tag, $repo_url, $repo_type) {

    $this->logger->notice('Running database updates (if applicable).');
    try {
      (new Process(['drush', 'updatedb', '--yes']))->mustRun()->wait();
      $this->logger->notice('Finished running database updates.');
    }
    catch (ProcessFailedException $e) {
      $this->logger->critical('Failed to run database updates!');
    }
  }

}
