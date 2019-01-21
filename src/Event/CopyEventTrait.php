<?php

namespace Drupal\cloudhooks\Event;

/**
 * The trait for all cloudhook events that involve copy operations.
 *
 * @package Drupal\cloudhooks\Event
 */
trait CopyEventTrait {

  /**
   * The name of the environment from where the artifact was copied.
   *
   * @var string
   */
  protected $sourceEnvironment;

  /**
   * Trait constructor.
   *
   * @param string $source_environment
   *   The name of the environment from where the artifact was copied.
   */
  public function __construct($source_environment) {
    $this->sourceEnvironment = $source_environment;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceEnvironment() {
    return $this->sourceEnvironment;
  }

}
