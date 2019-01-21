<?php

namespace Drupal\cloudhooks;

use Drupal\Component\Plugin\CategorizingPluginManagerInterface;
use Drupal\Component\Plugin\Discovery\CachedDiscoveryInterface;
use Drupal\Component\Plugin\FallbackPluginManagerInterface;
use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Interface implemented by all cloudhook plugin managers.
 */
interface CloudhookPluginManagerInterface extends PluginManagerInterface, CachedDiscoveryInterface, FallbackPluginManagerInterface, CategorizingPluginManagerInterface {

  /**
   * Gets all plugin definitions that are compatible with the provided event.
   *
   * @return array
   *   All plugin definitions that are compatible with the provided event.
   */
  public function getDefinitionsForEvent($event);

}
