<?php

namespace Drupal\cloudhooks\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * The ultimate base class for all cloudhook events.
 *
 * @package Drupal\cloudhooks\Event
 */
abstract class CloudhookEventBase extends Event implements CloudhookEventInterface {

  /**
   * The name of the application.
   *
   * @var string
   */
  protected $application;

  /**
   * The name of the environment.
   *
   * @var string
   */
  protected $environment;

  /**
   * Creates a new cloudhook event.
   *
   * @param string $application
   *   The name of the application.
   * @param string $environment
   *   The name of the application.
   */
  public function __construct($application, $environment) {
    $this->application = $application;
    $this->environment = $environment;
  }

  /**
   * {@inheritdoc}
   */
  public function getApplication() {
    return $this->application;
  }

  /**
   * {@inheritdoc}
   */
  public function getEnvironment() {
    return $this->environment;
  }

}
