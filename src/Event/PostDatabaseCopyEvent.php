<?php

namespace Drupal\cloudhooks\Event;

/**
 * An event type for representing a post-database-copy operation.
 *
 * @package Drupal\cloudhooks\Event
 */
class PostDatabaseCopyEvent extends CloudhookEventBase implements CopyEventInterface, DatabaseEventInterface {

  use CopyEventTrait {
    CopyEventTrait::__construct as private __copyEventConstruct;
  }

  use DatabaseEventTrait {
    DatabaseEventTrait::__construct as private __databaseEventConstruct;
  }

  const POST_DB_COPY = 'post-db-copy';

  /**
   * Creates a post-database-copy event.
   *
   * @param string $application
   *   The name of the application.
   * @param string $environment
   *   The name of the application.
   * @param string $database_name
   *   The name of the database that was copied.
   * @param string $source_environment
   *   The name of the environment from where the artifact was copied.
   */
  public function __construct($application, $environment, $database_name, $source_environment) {
    parent::__construct($application, $environment);
    $this->__databaseEventConstruct($database_name);
    $this->__copyEventConstruct($source_environment);
  }

}
