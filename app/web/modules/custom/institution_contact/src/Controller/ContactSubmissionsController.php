<?php

namespace Drupal\institution_contact\Controller;

use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Admin controller for contact submissions.
 */
final class ContactSubmissionsController extends ControllerBase {

  /**
   * Lists contact submissions.
   */
  public function list(): array {
    $database = \Drupal::database();

    if (!$database->schema()->tableExists('institution_contact_submission')) {
      return [
        '#markup' => '<p>La table des messages de contact n’existe pas encore. Lance la commande Drush de mise à jour de la base de données.</p>',
      ];
    }

    $query = $database->select('institution_contact_submission', 's')
      ->fields('s')
      ->orderBy('created', 'DESC')
      ->range(0, 100);

    $rows = [];

    foreach ($query->execute() as $record) {
      $message = (string) $record->message;
      $message_preview = mb_substr($message, 0, 180);

      if (mb_strlen($message) > 180) {
        $message_preview .= '...';
      }

      $rows[] = [
        'id' => (int) $record->id,
        'created' => date('d/m/Y H:i', (int) $record->created),
        'name' => Html::escape($record->name),
        'email' => [
          'data' => [
            '#type' => 'link',
            '#title' => Html::escape($record->email),
            '#url' => Url::fromUri('mailto:' . $record->email),
          ],
        ],
        'phone' => Html::escape($record->phone ?: '-'),
        'topic' => Html::escape($record->topic_label),
        'subject' => Html::escape($record->subject),
        'message' => Html::escape($message_preview),
        'mail_sent' => ((int) $record->mail_sent === 1) ? 'Oui' : 'Non',
        'status' => Html::escape($record->status),
      ];
    }

    return [
      'intro' => [
        '#markup' => '<p>Voici les 100 derniers messages envoyés depuis le formulaire de contact.</p>',
      ],
      'table' => [
        '#type' => 'table',
        '#header' => [
          'ID',
          'Date',
          'Nom',
          'Email',
          'Téléphone',
          'Type',
          'Sujet',
          'Message',
          'Email envoyé',
          'Statut',
        ],
        '#rows' => $rows,
        '#empty' => 'Aucun message de contact pour le moment.',
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}