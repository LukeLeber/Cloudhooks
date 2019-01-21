<?php

namespace Drupal\cloudhooks\Event;

/**
 * An event type for representing a post-code-update operation.
 *
 * @package Drupal\cloudhooks\Event
 */
class PostCodeUpdateEvent extends CloudhookEventBase implements CodeEventInterface {

  use CodeEventTrait {
    CodeEventTrait::__construct as private __codeEventConstruct;
  }

  const POST_CODE_UPDATE = 'post-code-update';

  /**
   * Creates a post-code-update event.
   *
   * @param string $application
   *   The name of the application.
   * @param string $environment
   *   The name of the application.
   * @param string $source_branch
   *   The code branch or tag that is being deployed.
   * @param string $deployed_tag
   *   The code branch or tag that is being deployed.
   * @param string $repo_url
   *   The URL of the code repository.
   * @param string $repo_type
   *   The name of the version control system being used.
   */
  public function __construct($application, $environment, $source_branch, $deployed_tag, $repo_url, $repo_type) {
    parent::__construct($application, $environment);
    $this->__codeEventConstruct($source_branch, $deployed_tag, $repo_url, $repo_type);
  }

}
