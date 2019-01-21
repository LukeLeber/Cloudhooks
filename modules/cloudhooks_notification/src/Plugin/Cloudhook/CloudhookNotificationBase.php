<?php

namespace Drupal\cloudhooks_notification\Plugin\Cloudhook;

use Drupal\cloudhooks\CloudhookPluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for all cloudhook notification plugins.
 *
 * @package Drupal\cloudhooks_notification\Plugin\Cloudhook
 */
abstract class CloudhookNotificationBase extends CloudhookPluginBase implements ContainerFactoryPluginInterface {

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
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    /* @var $logger \Psr\Log\LoggerInterface */
    $logger = $container->get('cloudhooks_maintenance.logger');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $logger
    );
  }

}
