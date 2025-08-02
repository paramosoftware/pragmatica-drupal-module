<?php

namespace Drupal\pragmatica\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

class SelectionForm extends PragmaticaBaseForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $form['start_position']['#states'] = $form['end_position']['#states'] = [
      'visible' => [
        ':input[name="type"]' => ['value' => '1']
      ],
    ];

    $form['begin']['#states'] = $form['end']['#states'] = [
      'visible' => [
        ':input[name="type"]' => ['value' => '2']
      ],
    ];

    $form['from_sync_point']['#states'] = $form['to_sync_point']['#states'] = [
      'visible' => [
        ':input[name="type"]' => ['value' => '3']
      ],
    ];

    return $form;
  }
}
