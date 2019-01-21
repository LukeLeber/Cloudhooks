<?php

namespace Drupal\cloudhooks\Event;

/**
 * The interface for all cloudhook events that involve code operations.
 *
 * @package Drupal\cloudhooks\Event
 */
interface CodeEventInterface {

  /**
   * Gets the code branch or tag that is being deployed.
   *
   * @return string
   *   The code branch or tag that is being deployed.
   */
  public function getSourceBranch();

  /**
   * Gets the code branch or tag that is being deployed.
   *
   * @return string
   *   The code branch or tag that is being deployed.
   */
  public function getDeployedTag();

  /**
   * Gets the URL of the code repository.
   *
   * @return string
   *   The URL of the code repository.
   */
  public function getRepoUrl();

  /**
   * Gets the name of the version control system being used.
   *
   * @return string
   *   The name of the version control system being used.
   */
  public function getRepoType();

}
