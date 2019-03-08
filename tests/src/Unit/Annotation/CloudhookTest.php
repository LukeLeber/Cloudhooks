<?php

namespace Drupal\Tests\cloudhooks\Unit\Annotation;

use Drupal\cloudhooks\Annotation\Cloudhook;
use Drupal\Tests\UnitTestCase;

/**
 * Test case for the cloudhook plugin annotation.
 *
 * @package Drupal\Tests\cloudhook\Unit\Annotation
 *
 * @group cloudhooks
 */
class CloudhookTest extends UnitTestCase {

  /**
   * The properties that are expected to exist within the annotation class.
   *
   * @var array
   */
  protected static $expectedProperties = [
    'id',
    'label',
    'description',
    'events',
  ];

  /**
   * Tests that the expected properties exist within the annotation class.
   *
   * @covers \Drupal\cloudhooks\Annotation\Cloudhook
   */
  public function testProperties() {
    foreach(static::$expectedProperties as $property) {
      $this->assertClassHasAttribute($property, Cloudhook::class);
    }
  }
}
