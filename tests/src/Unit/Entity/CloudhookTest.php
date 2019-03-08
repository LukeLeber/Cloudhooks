<?php

namespace Drupal\Tests\cloudhooks\Entity;

use Drupal\cloudhooks\Entity\Cloudhook;
use Drupal\Tests\UnitTestCase;

/**
 * Test case for the cloudhook configuration entity type.
 *
 * @package Drupal\Tests\cloudhooks\Entity
 */
class CloudhookTest extends UnitTestCase {

  /**
   * The subject under test.
   *
   * @var \Drupal\cloudhooks\CloudhookInterface
   */
  protected $cloudhook;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {

    $this->cloudhook = new Cloudhook([
      'id' => 'test',
      'label' => 'Test',
      'plugin_id' => 'test_plugin',
      'event' => 'post-code-deploy',
      'weight' => 0,
    ], 'cloudhook');

    parent::setUp();
  }

  /**
   * @covers \Drupal\cloudhooks\Entity\Cloudhook::getId
   */
  public function testGetId() {
    $this->assertEquals('test', $this->cloudhook->getId());
  }

  /**
   * @covers \Drupal\cloudhooks\Entity\Cloudhook::getLabel
   */
  public function getGetLabel() {
    $this->assertEquals('Test', $this->cloudhook->getLabel());
  }

  /**
   * @covers \Drupal\cloudhooks\Entity\Cloudhook::getPluginId
   */
  public function testGetPluginId() {
    $this->assertEquals('test_plugin', $this->cloudhook->getPluginId());
  }

  /**
   * @covers \Drupal\cloudhooks\Entity\Cloudhook::getWeight
   */
  public function testGetWeight() {
    $this->assertEquals(0, $this->cloudhook->getWeight());
  }

  /**
   * @covers \Drupal\cloudhooks\Entity\Cloudhook::getEvent
   */
  public function testGetEvent() {
    $this->assertEquals('post-code-deploy', $this->cloudhook->getEvent());
  }

}
