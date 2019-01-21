<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Drupal\cloudhooks\Entity\Cloudhook;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines general application features used by other feature files.
 */
class CloudhookFeatureContext extends RawDrupalContext implements SnippetAcceptingContext {

  /**
   * Creates content of a given type provided in the form:
   * |id             | label          | plugin_id      | event            | weight |
   * |test_cloudhook | Test cloudhook | test_cloudhook | post-code-deploy | 0      |
   * | ...           | ...            | ...            | ...              | ...    |
   *
   * @Given cloudhooks:
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *   This should never happen.
   */
  public function createCloudhooks(TableNode $nodesTable) {
    foreach ($nodesTable->getHash() as $nodeHash) {
      Cloudhook::create((array) $nodeHash)->save();
    }
  }

  /**
   * @AfterScenario
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *   This should never happen.
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *   This should never happen.
   * @throws \Drupal\Core\Entity\EntityStorageException
   *   This should never happen.
   */
  public static function deleteAllCloudhooks(AfterScenarioScope $event) {

    $cloudhooks = \Drupal::entityTypeManager()->getStorage('cloudhook')->loadMultiple();

    foreach($cloudhooks as $cloudhook) {
      $cloudhook->delete();
    }
  }

  /**
   * @When I pause for :seconds seconds
   */
  public function iPauseForSeconds($seconds) {
    sleep($seconds);
  }
}
