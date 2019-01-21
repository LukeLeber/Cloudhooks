<?php

namespace Drupal\cloudhooks\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines cloudhook annotation object.
 *
 * @Annotation
 */
class Cloudhook extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

  /**
   * An array of events that this plugin responds to.
   *
   * @var array
   */
  public $events;

}
