<?php

namespace Drupal\cloudhooks;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a cloudhook configuration entity.
 *
 * @package Drupal\cloudhooks
 */
interface CloudhookInterface extends ConfigEntityInterface {

  /**
   * Retrieve the id of this cloudhook.
   *
   * @return int
   *   The id of this cloudhook.
   */
  public function getId();

  /**
   * Retrieves the label of this cloudhook.
   *
   * @return string
   *   The label of this cloudhook.
   */
  public function getLabel();

  /**
   * Retrieves the weight of this cloudhook.
   *
   * @return int
   *   The weight of this cloudhook.
   */
  public function getWeight();

  /**
   * Retrieves the event that this cloudhook should fire on.
   *
   * @return string
   *   The event that this cloudhook should fire on.
   */
  public function getEvent();

  /**
   * Retrieves the plugin id that this cloudhook should use to execute.
   *
   * @return string
   *   The plugin id that this cloudhook should use to execute.
   */
  public function getPluginId();

}
