<?php

namespace Drupal\institution_contact\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for the premium contact page.
 */
final class ContactPageController extends ControllerBase {

  /**
   * Builds the contact page.
   */
  public function page(): array {
    $request = \Drupal::request();

    $form = $this->formBuilder()->getForm('Drupal\institution_contact\Form\PremiumContactForm');

    return [
      '#theme' => 'institution_contact_page',
      '#form' => $form,
      '#sent' => $request->query->get('sent') === '1',
      '#error' => $request->query->get('error') === '1',
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}