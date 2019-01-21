<?php

namespace Drupal\cloudhooks_maintenance\Plugin\Cloudhook;

use Drupal\cloudhooks\CloudhookPluginBase;
use Drupal\cloudhooks\Plugin\Cloudhook\PostCodeDeployPluginInterface;
use Drupal\cloudhooks\Plugin\Cloudhook\PostCodeUpdatePluginInterface;
use Drupal\cloudhooks\Plugin\Cloudhook\PostDbCopyPluginInterface;
use Drupal\cloudhooks\Plugin\Cloudhook\PostFilesCopyPluginInterface;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for maintenance mode cloudhook plugins.
 *
 * @package Drupal\cloudhooks_maintenance\Plugin\Cloudhook
 */
abstract class MaintenanceModeBase extends CloudhookPluginBase implements ContainerFactoryPluginInterface, PostCodeDeployPluginInterface, PostCodeUpdatePluginInterface, PostDbCopyPluginInterface, PostFilesCopyPluginInterface {

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The cache tags invalidator service.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new plugin.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tags_invalidator
   *   The cache tags invalidator service.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, StateInterface $state, CacheTagsInvalidatorInterface $cache_tags_invalidator, LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->state = $state;
    $this->cacheTagsInvalidator = $cache_tags_invalidator;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    /* @var $state \Drupal\Core\State\StateInterface */
    $state = $container->get('state');

    /* @var $cache_tags_invalidator \Drupal\Core\Cache\CacheTagsInvalidatorInterface */
    $cache_tags_invalidator = $container->get('cache_tags.invalidator');

    /* @var $logger \Psr\Log\LoggerInterface */
    $logger = $container->get('cloudhooks_maintenance.logger');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $state,
      $cache_tags_invalidator,
      $logger
    );
  }

  /**
   * {@inheritdoc}
   */
  public function onPostCodeDeploy($application, $environment, $source_branch, $deployed_tag, $repo_url, $repo_type) {
    $this->setMaintenanceMode();
  }

  /**
   * {@inheritdoc}
   */
  public function onPostCodeUpdate($application, $environment, $source_branch, $deployed_tag, $repo_url, $repo_type) {
    $this->setMaintenanceMode();
  }

  /**
   * {@inheritdoc}
   */
  public function onPostDatabaseCopy($application, $environment, $database_name, $source_environment) {
    $this->setMaintenanceMode();
  }

  /**
   * {@inheritdoc}
   */
  public function onPostFilesCopy($application, $environment, $source_environment) {
    $this->setMaintenanceMode();
  }

  /**
   * Sets the system maintenance mode flag appropriately.
   */
  protected function setMaintenanceMode() {

    $maintenance_state = $this->getMaintenanceModeState();

    $this->state->set('system.maintenance_mode', $maintenance_state ? 1 : 0);
    $this->logger->notice('Maintenance mode flag has been turned @state', [
      '@state' => new TranslatableMarkup($maintenance_state ? 'On' : 'Off'),
    ]);

    $this->cacheTagsInvalidator->invalidateTags(['rendered']);
    $this->logger->notice('Render cache has been invalidated');
  }

  /**
   * Gets the desired maintenance mode state.
   *
   * @return bool
   *   TRUE if maintenance mode should be on, otherwise FALSE.
   */
  abstract protected function getMaintenanceModeState();

}
