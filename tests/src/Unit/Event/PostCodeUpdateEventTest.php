<?php

namespace Drupal\Tests\cloudhooks\Unit\Event;

use Drupal\cloudhooks\Event\PostCodeUpdateEvent;
use Drupal\Tests\UnitTestCase;

/**
 * Test case for the post-code-update event type.
 *
 * @package Drupal\Tests\cloudhooks\Unit\Event
 */
class PostCodeUpdateEventTest extends UnitTestCase {

  /**
   * The subject under test.
   *
   * @var \Drupal\cloudhooks\Event\PostCodeUpdateEvent
   */
  protected $event;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->event = new PostCodeUpdateEvent(
      'application',
      'environment',
      'source_branch',
      'deployed_tag',
      'repo_url',
      'repo_type'
    );
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostCodeUpdateEvent::getApplication
   */
  public function testGetApplication() {
    $this->assertEquals('application', $this->event->getApplication());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostCodeUpdateEvent::getEnvironment
   */
  public function testGetEnvironment() {
    $this->assertEquals('environment', $this->event->getEnvironment());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostCodeUpdateEvent::getSourceBranch
   */
  public function testGetSourceBranch() {
    $this->assertEquals('source_branch', $this->event->getSourceBranch());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostCodeUpdateEvent::getDeployedTag
   */
  public function testGetDeployedtag() {
    $this->assertEquals('deployed_tag', $this->event->getDeployedTag());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostCodeUpdateEvent::getRepoUrl
   */
  public function testGetRepoUrl() {
    $this->assertEquals('repo_url', $this->event->getRepoUrl());
  }

  /**
   * @covers \Drupal\cloudhooks\Event\PostCodeUpdateEvent::getRepoType
   */
  public function testGetRepoType() {
    $this->assertEquals('repo_type', $this->event->getRepoType());
  }

}
