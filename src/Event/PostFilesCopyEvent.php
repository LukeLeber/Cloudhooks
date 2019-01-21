<?php

namespace Drupal\cloudhooks\Event;

/**
 * An event type for representing a post-files-copy operation.
 *
 * @package Drupal\cloudhooks\Event
 */
class PostFilesCopyEvent extends CloudhookEventBase implements CopyEventInterface {

  use CopyEventTrait {
    CopyEventTrait::__construct as private __copyEventConstruct;
  }

  const POST_FILES_COPY = 'post-files-copy';

  /**
   * Creates a post-database-copy event.
   *
   * @param string $application
   *   The name of the application.
   * @param string $environment
   *   The name of the application.
   * @param string $source_environment
   *   The name of the environment from where the artifact was copied.
   */
  public function __construct($application, $environment, $source_environment) {
    parent::__construct($application, $environment);
    $this->__copyEventConstruct($source_environment);
  }

}
