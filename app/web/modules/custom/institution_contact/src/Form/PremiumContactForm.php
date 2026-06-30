<?php

namespace Drupal\institution_contact\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Premium contact form.
 */
final class PremiumContactForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'institution_contact_premium_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#attributes']['class'][] = 'contact-form';

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nom complet'),
      '#required' => TRUE,
      '#maxlength' => 120,
      '#attributes' => [
        'placeholder' => $this->t('Votre nom complet'),
        'autocomplete' => 'name',
      ],
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Adresse email'),
      '#required' => TRUE,
      '#maxlength' => 180,
      '#attributes' => [
        'placeholder' => $this->t('votre.email@example.be'),
        'autocomplete' => 'email',
      ],
    ];

    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Téléphone'),
      '#required' => FALSE,
      '#maxlength' => 40,
      '#attributes' => [
        'placeholder' => $this->t('+32 ...'),
        'autocomplete' => 'tel',
      ],
    ];

    $form['topic'] = [
      '#type' => 'select',
      '#title' => $this->t('Type de demande'),
      '#required' => TRUE,
      '#options' => [
        '' => $this->t('- Sélectionner -'),
        'general' => $this->t('Demande générale'),
        'publication' => $this->t('Publication ou documentation'),
        'event' => $this->t('Événement'),
        'partnership' => $this->t('Partenariat'),
        'press' => $this->t('Presse et communication'),
        'technical' => $this->t('Problème technique'),
      ],
    ];

    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sujet'),
      '#required' => TRUE,
      '#maxlength' => 160,
      '#attributes' => [
        'placeholder' => $this->t('Résumé de votre demande'),
      ],
    ];

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
      '#rows' => 7,
      '#attributes' => [
        'placeholder' => $this->t('Décrivez votre demande avec le plus de précision possible...'),
      ],
    ];

    $form['send_copy'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Recevoir une copie de mon message par email'),
      '#default_value' => 1,
    ];

    $form['consent'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('J’accepte que mes données soient utilisées uniquement pour répondre à ma demande.'),
      '#required' => TRUE,
    ];

    // Simple honeypot anti-spam without plugin.
    $form['company_website'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site web de l’entreprise'),
      '#required' => FALSE,
      '#attributes' => [
        'class' => ['contact-form__honeypot'],
        'tabindex' => '-1',
        'autocomplete' => 'off',
      ],
    ];

    $form['loaded_at'] = [
      '#type' => 'hidden',
      '#value' => time(),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Envoyer le message'),
      '#attributes' => [
        'class' => ['btn', 'btn-primary', 'contact-form__submit'],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $message = trim((string) $form_state->getValue('message'));
    $subject = trim((string) $form_state->getValue('subject'));
    $topic = (string) $form_state->getValue('topic');

    if ($topic === '') {
      $form_state->setErrorByName('topic', $this->t('Veuillez sélectionner un type de demande.'));
    }

    if (mb_strlen($subject) < 4) {
      $form_state->setErrorByName('subject', $this->t('Le sujet est trop court.'));
    }

    if (mb_strlen($message) < 20) {
      $form_state->setErrorByName('message', $this->t('Votre message doit contenir au moins 20 caractères.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Honeypot: if filled, pretend success but do not send or store.
    if (!empty($form_state->getValue('company_website'))) {
      $form_state->setRedirect('institution_contact.page', [], [
        'query' => ['sent' => '1'],
      ]);
      return;
    }

    // Anti-bot timing: if submitted too quickly, pretend success.
    $loaded_at = (int) $form_state->getValue('loaded_at');
    if ($loaded_at > 0 && (time() - $loaded_at) < 2) {
      $form_state->setRedirect('institution_contact.page', [], [
        'query' => ['sent' => '1'],
      ]);
      return;
    }

    $values = $form_state->getValues();

    $topic_options = $form['topic']['#options'];
    $topic_label = isset($topic_options[$values['topic']]) ? (string) $topic_options[$values['topic']] : $values['topic'];

    $site_config = $this->config('system.site');
    $site_mail = $site_config->get('mail') ?: 'no-reply@example.be';
    $site_name = $site_config->get('name') ?: 'Institution';

    $params = [
      'name' => trim((string) $values['name']),
      'email' => trim((string) $values['email']),
      'phone' => trim((string) $values['phone']),
      'topic' => (string) $values['topic'],
      'topic_label' => $topic_label,
      'subject' => trim((string) $values['subject']),
      'message' => trim((string) $values['message']),
      'created' => date('d/m/Y H:i'),
      'ip' => \Drupal::request()->getClientIp(),
      'subject_line' => '[Contact site] ' . trim((string) $values['subject']),
      'copy_subject' => 'Copie de votre message envoyé à ' . $site_name,
    ];

    $mail_sent = FALSE;

    $mail_manager = \Drupal::service('plugin.manager.mail');
    $langcode = $this->languageManager()->getDefaultLanguage()->getId();

    $result = $mail_manager->mail(
      'institution_contact',
      'contact_message',
      $site_mail,
      $langcode,
      $params,
      $params['email'],
      TRUE
    );

    if (($result['result'] ?? FALSE) === TRUE) {
      $mail_sent = TRUE;
    }

    if (!empty($values['send_copy'])) {
      $mail_manager->mail(
        'institution_contact',
        'contact_copy',
        $params['email'],
        $langcode,
        $params,
        $site_mail,
        TRUE
      );
    }

    // Store the submission in the database.
    $database = \Drupal::database();

    if ($database->schema()->tableExists('institution_contact_submission')) {
      $database->insert('institution_contact_submission')
        ->fields([
          'created' => time(),
          'name' => $params['name'],
          'email' => $params['email'],
          'phone' => $params['phone'],
          'topic' => $params['topic'],
          'topic_label' => $params['topic_label'],
          'subject' => $params['subject'],
          'message' => $params['message'],
          'ip' => $params['ip'],
          'mail_sent' => $mail_sent ? 1 : 0,
          'status' => $mail_sent ? 'new' : 'stored_mail_failed',
        ])
        ->execute();
    }

    $form_state->setRedirect('institution_contact.page', [], [
      'query' => ['sent' => '1'],
    ]);
  }

}