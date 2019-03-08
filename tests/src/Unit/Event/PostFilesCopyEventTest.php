<?php

namespace Drupal\Tests\cloudhooks\Unit\Event;

use Drupal\cloudhooks\Event\PostFilesCopyEvent;
use Drupal\Tests\UnitTestCase;

/**
 * Test case for the post-files-copy event type.
 *
 * @package Drupal\Tests\cloudhooks\Unit\Event
 *
 * @group cloudhooks
 */
class PostFilesCopyEventTest extends UnitTestCase {

  /**
   * The subject under test.
   *
   * @var \Drupal\cloudhooks\Event\PostFilesCopyEvent
   */
  protected $event;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->event = new PostFilesCopyEvent(
      'application',
      'environment',
      'source_environment'
    );
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostFilesCopyEvent::getApplication
   */
  public function testGetApplication() {
    $this->assertEquals('application', $this->event->getApplication());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostFilesCopyEvent::getEnvironment
   */
  public function testGetEnvironment() {
    $this->assertEquals('environment', $this->event->getEnvironment());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostFilesCopyEvent::getSourceEnvironment
   */
  public function testGetSourceEnvironment() {
    $this->assertEquals('source_environment', $this->event->getSourceEnvironment());
  }

}
