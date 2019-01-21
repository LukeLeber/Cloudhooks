<?php

namespace Drupal\cloudhooks_notification\Plugin\Cloudhook;

use Drupal\cloudhooks\Plugin\Cloudhook\PostCodeDeployPluginInterface;
use Drupal\cloudhooks\Plugin\Cloudhook\PostCodeUpdatePluginInterface;
use Drupal\cloudhooks\Plugin\Cloudhook\PostDbCopyPluginInterface;
use Drupal\cloudhooks\Plugin\Cloudhook\PostFilesCopyPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;

/**
 * Provides slack notifications on events.
 *
 * @Cloudhook(
 *   id = "slack_notification",
 *   label = @Translation("Slack notifications"),
 *   description = @Translation("Posts messages to a slack channel on events."),
 *   category = @Translation("Notifications"),
 *   events = {
 *     "post-code-deploy",
 *     "post-code-update",
 *     "post-db-copy",
 *     "post-files-copy",
 *   }
 * )
 */
class CloudhookSlackNotification extends CloudhookNotificationBase implements PostCodeDeployPluginInterface, PostFilesCopyPluginInterface, PostDbCopyPluginInterface, PostCodeUpdatePluginInterface {

  use StringTranslationTrait;

  /**
   *
   */
  protected function getConfigurationFileLocation() {
    return "{$_SERVER['HOME']}/.slack";
  }

  /**
   *
   */
  protected function getConfiguration() {

    $configuration = NULL;
    $settings_file = $this->getConfigurationFileLocation();
    if (\file_exists($settings_file) && \is_readable($settings_file)) {
      $settings_file_content = file_get_contents($settings_file);
      $configuration = \json_decode($settings_file_content, TRUE);
    }
    else {
      $this->logger->error('Configuration file at @location does not exist or does not contain valid JSON.', [
        '@location' => $this->getConfigurationFileLocation(),
      ]);
    }
    foreach (['channel'] as $required_key) {
      if (!\array_key_exists($required_key, $configuration)) {
        $this->logger->error('Could not find "@key" within the configuration at "@location"', [
          '@key' => $required_key,
          '@location' => $this->getConfigurationFileLocation(),
        ]);
        $configuration = NULL;
        break;
      }
    }
    return $configuration;
  }

  /**
   *
   */
  protected function sendNotification(TranslatableMarkup $notification) {

    if (($configuration = $this->getConfiguration())) {
      $client = new Client([
        'base_uri' => 'https://hooks.slack.com/',
      ]);
      try {

        $client->post($configuration['channel'], [
          RequestOptions::JSON => [
            'text' => $notification->render(),
          ],
        ]);
        $this->logger->notice('Sent slack notification.');
      }
      catch (RequestException $e) {
        $this->logger->error('Unable to send slack notification: @message\n\n@stack_trace', [
          '@message' => $e->getMessage(),
          '@stack_trace' => $e->getTraceAsString(),
        ]);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onPostCodeDeploy($application, $environment, $source_branch, $deployed_tag, $repo_url, $repo_type) {

    $notification = NULL;

    if ($source_branch !== $deployed_tag) {
      $notification = $this->t('An updated deployment has been made to *@environment* using branch *@source_branch* as *@deployed_tag* on application *@application*.', [
        '@environment' => $environment,
        '@source_branch' => $source_branch,
        '@deployed_tag' => $deployed_tag,
        '@application' => $application,
      ]);
    }
    else {
      $notification = $this->t('An updated deployment has been made to *@environment* using tag *@deployed_tag* on application *@application*.', [
        '@environment' => $environment,
        '@deployed_tag' => $deployed_tag,
        '@application' => $application,

      ]);
    }

    $this->sendNotification($notification);
  }

  /**
   * {@inheritdoc}
   */
  public function onPostCodeUpdate($application, $environment, $source_branch, $deployed_tag, $repo_url, $repo_type) {
    $this->sendNotification($this->t('New code was pushed *@environment (@source_branch)* on application *@application*', [
      '@environment' => $environment,
      '@source_branch' => $source_branch,
      '@application' => $application,
    ]));
  }

  /**
   * {@inheritdoc}
   */
  public function onPostDatabaseCopy($application, $environment, $database_name, $source_environment) {
    $this->sendNotification($this->t('The database "@database_name" was copied from "@source_environment" to "@environment" on application "@application".', [
      '@database_name' => $database_name,
      '@source_environment' => $source_environment,
      '@environment' => $environment,
      '@application' => $application,
    ]));
  }

  /**
   * {@inheritdoc}
   */
  public function onPostFilesCopy($application, $environment, $source_environment) {
    $this->sendNotification($this->t('Files were copied from "@source_environment" to "@environment" on application "@application".', [
      '@source_environment' => $source_environment,
      '@environment' => $environment,
      '@application' => $application,
    ]));
  }

}
