<?php

namespace Drupal\Tests\cloudhooks\Unit\EventSubscriber;

use Drupal\cloudhooks\CloudhookPluginManagerInterface;
use Drupal\cloudhooks\Entity\Cloudhook;
use Drupal\cloudhooks\Event\PostCodeDeployEvent;
use Drupal\cloudhooks\Event\PostCodeUpdateEvent;
use Drupal\cloudhooks\Event\PostDatabaseCopyEvent;
use Drupal\cloudhooks\Event\PostFilesCopyEvent;
use Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber;
use Drupal\cloudhooks\Plugin\Cloudhook\PostCodeDeployPluginInterface;
use Drupal\cloudhooks\Plugin\Cloudhook\PostCodeUpdatePluginInterface;
use Drupal\cloudhooks\Plugin\Cloudhook\PostDatabaseCopyPluginInterface;
use Drupal\cloudhooks\Plugin\Cloudhook\PostFilesCopyPluginInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Tests\UnitTestCase;
use Psr\Log\LoggerInterface;

/**
 * Class CloudhookEventSubscriberTest
 *
 * @package Drupal\Tests\cloudhooks\EventSubscriber
 *
 * @group cloudhooks
 */
class CloudhookEventSubscriberTest extends UnitTestCase {

  /**
   * The subject under test.
   *
   * @var \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber
   */
  protected $eventSubscriber;

  /**
   * The mocked entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface|\PHPUnit\Framework\MockObject\MockBuilder
   */
  protected $entityTypeManager;

  /**
   * The mocked plugin manager service.
   *
   * @var \Drupal\cloudhooks\CloudhookPluginManagerInterface|\PHPUnit\Framework\MockObject\MockObject
   */
  protected $pluginManager;

  /**
   * The mocked logger service.
   *
   * @var \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
   */
  protected $logger;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $cloudhook_entity = $this->getMockBuilder(Cloudhook::class)
      ->disableOriginalConstructor()
      ->getMock();

    $cloudhook_entity
      ->method('getWeight')
      ->willReturn(0);

    $cloudhook_entity
      ->method('getPluginId')
      ->willReturn('test_plugin');

    $cloudhook_storage = $this->getMockBuilder(ConfigEntityStorageInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
    $cloudhook_storage
      ->method('loadByProperties')
      ->willReturn([$cloudhook_entity]);

    $this->entityTypeManager = $this->getMockBuilder(EntityTypeManagerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->entityTypeManager
      ->method('getStorage')
      ->willReturn($cloudhook_storage);

    $this->pluginManager = $this->getMockBuilder(CloudhookPluginManagerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->logger = $this->getMockBuilder(LoggerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->logger
      ->expects($this->exactly(2))
      ->method('notice');

    $this->eventSubscriber = new CloudhookEventSubscriber($this->entityTypeManager, $this->pluginManager, $this->logger);
  }

  protected function getPluginMock($plugin_class) {

    $plugin = $this->getMockBuilder($plugin_class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->pluginManager
      ->expects($this->once())
      ->method('createInstance')
      ->with('test_plugin')
      ->willReturn($plugin);

    return $plugin;
  }

  /**
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::onPostCodeDeploy
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::logStarting
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::logFinished
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function testOnPostCodeDeploy() {
    $plugin = $this->getPluginMock(PostCodeDeployPluginInterface::class);
    $plugin
      ->expects($this->once())
      ->method('onPostCodeDeploy');

    /* @var $post_code_deploy_event \Drupal\cloudhooks\Event\PostCodeDeployEvent|\PHPUnit\Framework\MockObject\MockObject */
    $post_code_deploy_event = $this->getMockBuilder(PostCodeDeployEvent::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->eventSubscriber->onPostCodeDeploy($post_code_deploy_event);
  }

  /**
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::onPostCodeUpdate
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::logStarting
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::logFinished
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function testOnPostCodeUpdate() {
    $plugin = $this->getPluginMock(PostCodeUpdatePluginInterface::class);
    $plugin
      ->expects($this->once())
      ->method('onPostCodeUpdate');

    /* @var $post_code_update_event \Drupal\cloudhooks\Event\PostCodeUpdateEvent|\PHPUnit\Framework\MockObject\MockObject */
    $post_code_update_event = $this->getMockBuilder(PostCodeUpdateEvent::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->eventSubscriber->onPostCodeUpdate($post_code_update_event);
  }

  /**
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::onPostDatabaseCopy
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::logStarting
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::logFinished
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function testOnPostDatabaseCopy() {
    $plugin = $this->getPluginMock(PostDatabaseCopyPluginInterface::class);
    $plugin
      ->expects($this->once())
      ->method('onPostDatabaseCopy');

    /* @var $post_code_deploy_event \Drupal\cloudhooks\Event\PostDatabaseCopyEvent|\PHPUnit\Framework\MockObject\MockObject */
    $post_code_deploy_event = $this->getMockBuilder(PostDatabaseCopyEvent::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->eventSubscriber->onPostDatabaseCopy($post_code_deploy_event);
  }

  /**
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::onPostFilesCopy
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::logStarting
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::logFinished
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function testOnPostFilesCopy() {
    $plugin = $this->getPluginMock(PostFilesCopyPluginInterface::class);
    $plugin
      ->expects($this->once())
      ->method('onPostFilesCopy');

    /* @var $post_files_copy_event \Drupal\cloudhooks\Event\PostFilesCopyEvent|\PHPUnit\Framework\MockObject\MockObject */
    $post_files_copy_event = $this->getMockBuilder(PostFilesCopyEvent::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->eventSubscriber->onPostFilesCopy($post_files_copy_event);
  }

  /**
   * @covers \Drupal\cloudhooks\EventSubscriber\CloudhookEventSubscriber::getSubscribedEvents
   */
  public function getSubscribedEventsTest() {

    $expected = [
      PostCodeDeployEvent::POST_CODE_DEPLOY => 'onPostCodeDeploy',
      PostCodeUpdateEvent::POST_CODE_UPDATE => 'onPostCodeDeploy',
      PostDatabaseCopyEvent::POST_DB_COPY => 'onPostCodeDeploy',
      PostFilesCopyEvent::POST_FILES_COPY => 'onPostCodeDeploy',
    ];

    $actual = CloudhookEventSubscriber::getSubscribedEvents();

    foreach ($expected as $event => $method) {
      $this->assertArrayHasKey($event, $actual);
      $this->assertTrue(method_exists($this->eventSubscriber, $method));
    }
  }

}
