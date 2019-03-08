<?php

namespace Drupal\Tests\cloudhooks\Unit\Form;

use Drupal\cloudhooks\Entity\Cloudhook;
use Drupal\cloudhooks\Form\CloudhookDeleteForm;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\Tests\UnitTestCase;

class CloudhookDeleteFormTest extends UnitTestCase {

  /**
   * The subject under test.
   *
   * @var \Drupal\cloudhooks\Form\CloudhookDeleteForm
   */
  protected $deleteForm;

  /**
   * The mocked entity to be deleted.
   *
   * @var \Drupal\cloudhooks\CloudhookInterface|\PHPUnit\Framework\MockObject\MockObject
   */
  protected $entity;

  /**
   * The mocked messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface|\PHPUnit\Framework\MockObject\MockObject
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $container = new ContainerBuilder();
    \Drupal::setContainer($container);
    $container->set('string_translation', self::getStringTranslationStub());


    $this->deleteForm = new CloudhookDeleteForm();

    $this->entity = $this->getMockBuilder(Cloudhook::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->entity->method('label')->willReturn('Test cloudhook');

    $this->messenger = $this->getMockBuilder(MessengerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->deleteForm->setEntity($this->entity);
    $this->deleteForm->setMessenger($this->messenger);
  }

  /**
   * @covers \Drupal\cloudhooks\Form\CloudhookDeleteForm::getQuestion
   */
  public function testGetQuestion() {
    $expected = $this->getStringTranslationStub()->translate('Are you sure you want to delete %name?', [
        '%name' => 'Test cloudhook'
    ]);

    $this->assertEquals((string)$expected, (string)$this->deleteForm->getQuestion());
  }

  /**
   * @covers \Drupal\cloudhooks\Form\CloudhookDeleteForm::getConfirmText
   */
  public function testGetConfirmText() {
    $expected = $this->getStringTranslationStub()->translate('Delete');

    $this->assertEquals((string)$expected, (string)$this->deleteForm->getConfirmText());
  }

  /**
   * @covers \Drupal\cloudhooks\Form\CloudhookDeleteForm::getCancelUrl
   */
  public function testGetCancelUrl() {
    $this->assertEquals('entity.cloudhook.collection', $this->deleteForm->getCancelUrl()->getRouteName());
  }

  /**
   * @covers \Drupal\cloudhooks\Form\CloudhookDeleteForm::submitForm
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function testSubmitForm() {
    $this->entity->expects($this->once())
      ->method('delete');

    $this->messenger->expects($this->once())
      ->method('addMessage')
      ->with((string)$this->getStringTranslationStub()->translate('Deleted the %label cloudhook configuration.', [
        '%label' => 'Test cloudhook',
      ]));

    $form_state = $this->getMockBuilder(FormStateInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $form = [];

    /* @var $form_state \Drupal\Core\Form\FormStateInterface|\PHPUnit\Framework\MockObject\MockObject */
    $form_state
      ->expects($this->once())
      ->method('setRedirectUrl')
      ->with(new Url('entity.cloudhook.collection'));

    $this->deleteForm->submitForm($form, $form_state);
  }

}
