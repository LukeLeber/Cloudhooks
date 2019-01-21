<?php

namespace Drupal\cloudhooks_drush\Plugin\Cloudhook;

use Drupal\cloudhooks\CloudhookPluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class CloudhookDrushPluginBase extends CloudhookPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   *
   */
  public function __construct(array $configuration, string $plugin_id, $plugin_definition, LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    /* @var $logger \Psr\Log\LoggerInterface */
    $logger = $container->get('cloudhooks_drush.logger');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $logger
    );
  }

}
