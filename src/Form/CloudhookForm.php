<?php

namespace Drupal\cloudhooks\Form;

use Drupal\cloudhooks\CloudhookPluginManagerInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The entity form for the cloudhook configuration entity.
 */
class CloudhookForm extends EntityForm {

  /**
   * The cloudhook plugin manager service.
   *
   * @var \Drupal\cloudhooks\CloudhookPluginManagerInterface
   */
  protected $cloudhookPluginManager;

  /**
   * Constructs a cloudhook entity form.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\cloudhooks\CloudhookPluginManagerInterface $cloudhook_plugin_manager
   *   The cloudhook plugin manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, CloudhookPluginManagerInterface $cloudhook_plugin_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->cloudhookPluginManager = $cloudhook_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {

    /* @var $entity_type_manager \Drupal\Core\Entity\EntityTypeManagerInterface */
    $entity_type_manager = $container->get('entity_type.manager');

    /* @var $cloudhook_plugin_manager \Drupal\cloudhooks\CloudhookPluginManagerInterface */
    $cloudhook_plugin_manager = $container->get('plugin.manager.cloudhook');

    return new static(
      $entity_type_manager,
      $cloudhook_plugin_manager
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /* @var $entity \Drupal\cloudhooks\CloudhookInterface */
    $entity = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $entity->label() ?? '',
      '#description' => $this->t("Label for the cloudhook."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $entity->id() ?? '',
      '#machine_name' => [
        'exists' => [$this, 'exist'],
      ],
      '#disabled' => !$entity->isNew(),
    ];

    $form['event'] = [
      '#type' => 'select',
      '#title' => $this->t('Event'),
      '#default_value' => $entity->getEvent() ?? FALSE,
      '#options' => [
        'post-code-deploy' => new TranslatableMarkup('Post code deploy'),
        'post-code-update' => new TranslatableMarkup('Post code update'),
        'post-db-copy' => new TranslatableMarkup('Post database copy'),
        'post-files-copy' => new TranslatableMarkup('Post files copy'),
      ],
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::onEventSelection',
        'wrapper' => 'plugin-id',
        'progress' => [
          'type' => 'throbber',
          'message' => t('Loading new plugin options...'),
        ],
      ],
    ];

    $form['plugin_id'] = [
      '#type' => 'select',
      '#title' => 'Cloudhook plugin',
      '#default_value' => $form_state->getValue('plugin_id') ?? $entity->get('plugin_id') ?? FALSE,
      '#options' => $this->getPluginOptions($form_state->getValue('event')),
      '#required' => TRUE,
      '#prefix' => '<div id="plugin-id">',
      '#suffix' => '</div>',
    ];

    $form['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight'),
      '#default_value' => $entity->get('weight'),
      '#required' => TRUE,
    ];

    // You will need additional form elements for your custom properties.
    return $form;
  }

  protected function getPluginOptions($event) {

    $callback = function ($plugin) {
      return $plugin['label'];
    };

    $plugins = $this->cloudhookPluginManager->getDefinitionsForEvent($event);

    // Re-map the plugins array so that the value is just the label.
    return array_map($callback, $plugins);
  }

  /**
   * AJAX callback that fires when a user switches events.
   *
   * This method updates the available plugins based upon the new event.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The new plugin options.
   */
  public function onEventSelection(array &$form, FormStateInterface $form_state) {
    return $form['plugin_id'];
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *   This should never happen.
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = $this->entity->save();

    if ($status) {
      $this->messenger()->addMessage($this->t('Saved the %label configuration.', [
        '%label' => $this->entity->label(),
      ]));
    }
    else {
      $this->messenger()->addMessage($this->t('The %label configuration was not saved.', [
        '%label' => $this->entity->label(),
      ]), MessengerInterface::TYPE_ERROR);
    }

    $form_state->setRedirect('entity.cloudhook.collection');
  }

  /**
   * Helper function to check whether a configuration entity exists.
   *
   * @param int $id
   *   The id of the entity to check for.
   *
   * @return bool
   *   TRUE if a configuration with the provided id exists, otherwise FALSE.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   *   This should never happen.
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *   This should never happen.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('cloudhook')
      ->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $plugin_id = $form_state->getValue('plugin_id');
    $event = $form_state->getValue('event');

    // Users with javascript disabled may trigger this condition.
    if (!\array_key_exists($plugin_id, $this->cloudhookPluginManager->getDefinitionsForEvent($event))) {
      $form_state->setErrorByName('event', $this->t('Plugin type "@plugin_id" does not support the "@event" event.', [
        '@plugin_id' => $plugin_id,
        '@event' => $event,
      ]));
    }
  }
}
