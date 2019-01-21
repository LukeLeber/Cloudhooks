<?php

namespace Drupal\cloudhooks;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * This list builder is used if the views module is unavailable.
 *
 * @package Drupal\cloudhooks\Controller
 */
class CloudhookListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['plugin_id'] = $this->t('Plugin id');
    $header['event'] = $this->t('Event');
    $header['weight'] = $this->t('Weight');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\cloudhooks\CloudhookInterface */

    $row['label'] = $entity->label();
    $row['plugin_id'] = $entity->getPluginId();
    $row['event'] = $entity->getEvent();
    $row['weight'] = $entity->get('weight');
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    $entities = parent::load();

    // Sort by event name.
    uasort($entities, function (CloudhookInterface $lhs, CloudhookInterface $rhs) {
      $result = strnatcmp($lhs->getEvent(), $rhs->getEvent());

      // Inner sort by weight.
      if ($result === 0) {
        $result = $lhs->getWeight() - $rhs->getWeight();
      }
      return $result;
    });

    return $entities;
  }

}
