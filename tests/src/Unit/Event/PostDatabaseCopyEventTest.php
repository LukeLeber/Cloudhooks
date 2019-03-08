<?php

namespace Drupal\Tests\cloudhooks\Unit\Event;

use Drupal\cloudhooks\Event\PostDatabaseCopyEvent;
use Drupal\Tests\UnitTestCase;

/**
 * Test case for the post-db-copy event type.
 *
 * @package Drupal\Tests\cloudhooks\Unit\Event
 *
 * @group cloudhooks
 */
class PostDatabaseCopyEventTest extends UnitTestCase {

  /**
   * The subject under test.
   *
   * @var \Drupal\cloudhooks\Event\PostDatabaseCopyEvent
   */
  protected $event;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->event = new PostDatabaseCopyEvent(
      'application',
      'environment',
      'database_name',
      'source_environment',
    );
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostDatabaseCopyEvent::getApplication
   */
  public function testGetApplication() {
    $this->assertEquals('application', $this->event->getApplication());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostDatabaseCopyEvent::getEnvironment
   */
  public function testGetEnvironment() {
    $this->assertEquals('environment', $this->event->getEnvironment());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostDatabaseCopyEvent::getDatabaseName
   */
  public function testGetDatabaseName() {
    $this->assertEquals('database_name', $this->event->getDatabaseName());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostDatabaseCopyEvent::getSourceEnvironment
   */
  public function testGetSourceEnvironment() {
    $this->assertEquals('source_environment', $this->event->getSourceEnvironment());
  }

}
