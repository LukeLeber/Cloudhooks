<?php

namespace Drupal\cloudhooks\Entity;

use Drupal\cloudhooks\CloudhookInterface;
use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines the cloudhook entity.
 *
 * @ConfigEntityType(
 *   id = "cloudhook",
 *   label = @Translation("Cloudhook"),
 *   handlers = {
 *     "list_builder" = "Drupal\cloudhooks\CloudhookListBuilder",
 *     "form" = {
 *       "add" = "Drupal\cloudhooks\Form\CloudhookForm",
 *       "edit" = "Drupal\cloudhooks\Form\CloudhookForm",
 *       "delete" = "Drupal\cloudhooks\Form\CloudhookDeleteForm",
 *     }
 *   },
 *   config_prefix = "cloudhooks",
 *   admin_permission = "administer cloudhooks",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "event",
 *     "plugin_id",
 *     "weight",
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/system/cloudhooks/{cloudhook}",
 *     "delete-form" = "/admin/config/system/cloudhooks/{cloudhook}/delete",
 *   }
 * )
 */
class Cloudhook extends ConfigEntityBase implements CloudhookInterface {

  use StringTranslationTrait;

  /**
   * The id of the cloudhook.
   *
   * @var string
   */
  protected $id;

  /**
   * The label of the cloudhook.
   *
   * @var string
   */
  protected $label;

  /**
   * The plugin id of the cloudhook.
   *
   * @var string
   */
  protected $plugin_id;

  /**
   * The event of the cloudhook.
   *
   * @var string
   */
  protected $event;

  /**
   * The weight of the cloudhook.
   *
   * @var int
   */
  protected $weight;

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->id;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginId() {
    return $this->plugin_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->weight;
  }

  /**
   * {@inheritdoc}
   */
  public function getEvent() {
    return $this->event;
  }

}
