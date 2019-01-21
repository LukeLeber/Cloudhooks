<?php

namespace Drupal\cloudhooks\EventSubscriber;

use Drupal\cloudhooks\CloudhookInterface;
use Drupal\cloudhooks\CloudhookPluginManagerInterface;
use Drupal\cloudhooks\Event\PostCodeDeployEvent;
use Drupal\cloudhooks\Event\PostCodeUpdateEvent;
use Drupal\cloudhooks\Event\PostDatabaseCopyEvent;
use Drupal\cloudhooks\Event\PostFilesCopyEvent;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber that reacts to cloudhook events.
 *
 * @package Drupal\cloudhooks\EventSubscriber
 */
class CloudhookEventSubscriber implements EventSubscriberInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The cloudhook plugin manager service.
   *
   * @var \Drupal\cloudhooks\CloudhookPluginManagerInterface
   */
  protected $cloudhookPluginManager;

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Creates a new event subscriber.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\cloudhooks\CloudhookPluginManagerInterface $cloudhook_plugin_manager
   *   The cloudhook plugin manager service.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, CloudhookPluginManagerInterface $cloudhook_plugin_manager, LoggerInterface $logger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->cloudhookPluginManager = $cloudhook_plugin_manager;
    $this->logger = $logger;
  }

  /**
   * Gets all hooks that are registered for the provided event.
   *
   * The hooks are sorted in descending order by weight.
   *
   * @param string $event
   *   The event to get hooks for.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   All hooks that are registered for the provided event.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   *   This should never happen.
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *   This should never happen.
   */
  protected function getPlugins($event) {

    $hooks = $this->entityTypeManager->getStorage('cloudhook')
      ->loadByProperties([
        'event' => $event,
      ]);

    // Sort according to weight...
    uasort($hooks, function (CloudhookInterface $lhs, CloudhookInterface $rhs) {
      return $lhs->getWeight() - $rhs->getWeight();
    });

    // Map each hook to its plugin type.
    $plugins = array_map(function (CloudhookInterface $hook) {
      $plugin_id = $hook->getPluginId();
      return $this->cloudhookPluginManager->createInstance($plugin_id);
    }, $hooks);

    return $plugins;
  }

  /**
   * Creates a log entry that the provided event is starting.
   *
   * @param string $event
   *   The event that is starting.
   */
  protected function logStarting($event) {
    $this->logger->notice('Cloudhook event "@event" is starting.', [
      '@event' => $event,
    ]);
  }

  /**
   * Creates a log entry that the provided event has finished.
   *
   * @param string $event
   *   The event that has finished.
   */
  protected function logFinished($event) {
    $this->logger->notice('Cloudhook event "@event" has completed.', [
      '@event' => $event,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      PostCodeDeployEvent::POST_CODE_DEPLOY => 'onPostCodeDeploy',
      PostCodeUpdateEvent::POST_CODE_UPDATE => 'onPostCodeDeploy',
      PostDatabaseCopyEvent::POST_DB_COPY => 'onPostCodeDeploy',
      PostFilesCopyEvent::POST_FILES_COPY => 'onPostCodeDeploy',
    ];
  }

  /**
   * The method invoked upon post-code-deploy.
   *
   * @param \Drupal\cloudhooks\Event\PostCodeDeployEvent $event
   *   The event that was subscribed to.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   *   This should never happen.
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *   This should never happen.
   */
  public function onPostCodeDeploy(PostCodeDeployEvent $event) {

    $this->logStarting(PostCodeDeployEvent::POST_CODE_DEPLOY);

    foreach ($this->getPlugins(PostCodeDeployEvent::POST_CODE_DEPLOY) as $plugin) {
      /* @var $plugin \Drupal\cloudhooks\Plugin\Cloudhook\PostCodeDeployPluginInterface */

      $plugin->onPostCodeDeploy(
        $event->getApplication(),
        $event->getEnvironment(),
        $event->getSourceBranch(),
        $event->getDeployedTag(),
        $event->getRepoUrl(),
        $event->getRepoType()
      );
    }

    $this->logFinished(PostCodeDeployEvent::POST_CODE_DEPLOY);

  }

  /**
   * The method invoked upon post-code-update.
   *
   * @param \Drupal\cloudhooks\Event\PostCodeUpdateEvent $event
   *   The event that was subscribed to.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   *   This should never happen.
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *   This should never happen.
   */
  public function onPostCodeUpdate(PostCodeUpdateEvent $event) {

    $this->logStarting(PostCodeUpdateEvent::POST_CODE_UPDATE);

    foreach ($this->getPlugins(PostCodeUpdateEvent::POST_CODE_UPDATE) as $plugin) {
      /* @var $plugin \Drupal\cloudhooks\Plugin\Cloudhook\PostCodeUpdatePluginInterface */

      $plugin->onPostCodeUpdate(
        $event->getApplication(),
        $event->getEnvironment(),
        $event->getSourceBranch(),
        $event->getDeployedTag(),
        $event->getRepoUrl(),
        $event->getRepoType()
      );
    }

    $this->logFinished(PostCodeUpdateEvent::POST_CODE_UPDATE);
  }

  /**
   * The method invoked upon post-code-update.
   *
   * @param \Drupal\cloudhooks\Event\PostDatabaseCopyEvent $event
   *   The event that was subscribed to.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   *   This should never happen.
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *   This should never happen.
   */
  public function onPostDatabaseCopy(PostDatabaseCopyEvent $event) {

    $this->logStarting(PostDatabaseCopyEvent::POST_DB_COPY);

    foreach ($this->getPlugins(PostDatabaseCopyEvent::POST_DB_COPY) as $plugin) {
      /* @var $plugin \Drupal\cloudhooks\Plugin\Cloudhook\PostDbCopyPluginInterface */

      $plugin->onPostDatabaseCopy(
        $event->getApplication(),
        $event->getEnvironment(),
        $event->getDatabaseName(),
        $event->getSourceEnvironment()
      );
    }

    $this->logFinished(PostDatabaseCopyEvent::POST_DB_COPY);
  }

  /**
   * The method invoked upon post-code-update.
   *
   * @param \Drupal\cloudhooks\Event\PostFilesCopyEvent $event
   *   The event that was subscribed to.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   *   This should never happen.
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *   This should never happen.
   */
  public function onPostFilesCopy(PostFilesCopyEvent $event) {

    $this->logStarting(PostFilesCopyEvent::POST_FILES_COPY);

    foreach ($this->getPlugins(PostFilesCopyEvent::POST_FILES_COPY) as $plugin) {
      /* @var $plugin \Drupal\cloudhooks\Plugin\Cloudhook\PostFilesCopyPluginInterface */

      $plugin->onPostFilesCopy(
        $event->getApplication(),
        $event->getEnvironment(),
        $event->getSourceEnvironment()
      );
    }

    $this->logFinished(PostFilesCopyEvent::POST_FILES_COPY);
  }

}
