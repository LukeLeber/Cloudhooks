<?php

namespace Drupal\cloudhooks\Event;

/**
 * The trait for all cloudhook events that involve database operations.
 *
 * @package Drupal\cloudhooks\Event
 */
trait DatabaseEventTrait {

  /**
   * The name of the database that was copied.
   *
   * @var string
   */
  protected $databaseName;

  /**
   * Trait constructor.
   *
   * @param string $database_name
   *   The name of the database that was copied.
   */
  public function __construct($database_name) {
    $this->databaseName = $database_name;
  }

  /**
   * {@inheritdoc}
   */
  public function getDatabaseName() {
    return $this->databaseName;
  }

}
