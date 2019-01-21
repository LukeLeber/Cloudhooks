<?php

namespace Drupal\cloudhooks;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for cloudhook plugins.
 */
abstract class CloudhookPluginBase extends PluginBase implements CloudhookPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function label() {
    return (string) $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function description() {
    return (string) $this->pluginDefinition['description'];
  }

  /**
   * {@inheritdoc}
   */
  public function getEvents() {
    return (array) $this->pluginDefinition['events'];
  }

}
