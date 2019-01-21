<?php

namespace Drupal\cloudhooks\Event;

/**
 * The trait for all cloudhook events that involve code operations.
 *
 * @package Drupal\cloudhooks\Event
 */
trait CodeEventTrait {

  /**
   * The code branch or tag that is being deployed.
   *
   * @var string
   */
  protected $sourceBranch;

  /**
   * The code branch or tag that is being deployed.
   *
   * @var string
   */
  protected $deployedTag;

  /**
   * The URL of the code repository.
   *
   * @var string
   */
  protected $repoUrl;

  /**
   * The name of the version control system being used.
   *
   * @var string
   */
  protected $repoType;

  /**
   * Trait constructor.
   *
   * @param string $source_branch
   *   The code branch or tag that is being deployed.
   * @param string $deployed_tag
   *   The code branch or tag that is being deployed.
   * @param string $repo_url
   *   The URL of the code repository.
   * @param string $repo_type
   *   The name of the version control system being used.
   */
  public function __construct($source_branch, $deployed_tag, $repo_url, $repo_type) {
    $this->sourceBranch = $source_branch;
    $this->deployedTag = $deployed_tag;
    $this->repoUrl = $repo_url;
    $this->repoType = $repo_type;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceBranch() {
    return $this->sourceBranch;
  }

  /**
   * {@inheritdoc}
   */
  public function getDeployedTag() {
    return $this->sourceBranch;
  }

  /**
   * {@inheritdoc}
   */
  public function getRepoUrl() {
    return $this->repoUrl;
  }

  /**
   * {@inheritdoc}
   */
  public function getRepoType() {
    return $this->repoType;
  }

}
