<?php

namespace Drupal\cloudhooks\Event;

/**
 * An event type for representing a post-database-copy operation.
 *
 * @package Drupal\cloudhooks\Event
 */
class PostDatabaseCopyEvent extends CloudhookEventBase implements CopyEventInterface {

  use CopyEventTrait {
    CopyEventTrait::__construct as private __copyEventConstruct;
  }
  /**
   * The name of the database that was copied.
   *
   * @var string
   */
  protected $databaseName;

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
    $this->__copyEventConstruct($source_environment);
    $this->databaseName = $database_name;
  }

  /**
   * {@inheritdoc}
   */
  public function getDatabaseName() {
    return $this->databaseName;
  }
}
