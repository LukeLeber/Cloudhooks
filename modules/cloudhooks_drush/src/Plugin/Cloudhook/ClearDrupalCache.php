<?php

namespace Drupal\cloudhooks_drush\Plugin\Cloudhook;

use Drupal\cloudhooks\Plugin\Cloudhook\PostCodeDeployPluginInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Provides a mechanism to clear the Drupal cache.
 *
 * @Cloudhook(
 *   id = "clear_drupal_cache",
 *   label = @Translation("Clears the Drupal cache"),
 *   description = @Translation("Clears the Drupal cache."),
 *   category = @Translation("Basic"),
 *   events = {
 *     "post-code-deploy",
 *     "post-code-update",
 *     "post-db-copy",
 *     "post-files-copy",
 *   }
 * )
 */
class ClearDrupalCache extends CloudhookDrushPluginBase implements PostCodeDeployPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function onPostCodeDeploy($application, $environment, $source_branch, $deployed_tag, $repo_url, $repo_type) {

    try {
      (new Process(['drush', 'cr']))->mustRun()->wait();
      $this->logger->notice('Cleared the Drupal cache.');
    }
    catch (ProcessFailedException $e) {
      $this->logger->critical('Failed to clear the Drupal cache!');
    }
  }

}
