<?php

namespace Drupal\pragmatica\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Code entity.
 *
 * @ContentEntityType(
 *   id = "pragmatica_code",
 *   label = @Translation("Código"),
 *   label_plural = @Translation("Códigos"),
 *   base_table = "pragmatica_code",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name"
 *   },
 *   handlers = {
 *     "list_builder" = "Drupal\pragmatica\ListBuilder\CodeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\pragmatica\Form\CodeForm",
 *       "edit" = "Drupal\pragmatica\Form\CodeForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     }
 *   },
 *   links = {
 *    "canonical" = "/admin/pragmatica/code/{pragmatica_code}",
 *    "add-form" = "/admin/pragmatica/code/add",
 *    "edit-form" = "/admin/pragmatica/code/{pragmatica_code}/edit",
 *    "delete-form" = "/admin/pragmatica/code/{pragmatica_code}/delete",
 *    "collection" = "/admin/pragmatica/code",
 *   },
 *   admin_permission = "pragmatica",
 * )
 */
class Code extends ContentEntityBase {
  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['guid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('GUID'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 36)
      ->setDescription(t('Código único global (GUID) para identificar o código.'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ]);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Nome'))
      ->setDescription(t('Nome do código.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ]);

    $fields['is_codeble'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Pode ser usado como código?'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -3,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => -3,
      ]);

    $fields['color'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Cor'))
      ->setSetting('max_length', 7)
      ->setDescription(t('Cor associada ao código, no formato hexadecimal (ex: #FF5733).'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -2,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -2,
      ]);

    $fields['description'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Descrição'))
      ->setDescription(t('Descrição detalhada do código.'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => -1,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => -1,
      ]);

    $fields['parent'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Código pai'))
      ->setDescription(t('Seleciona um código pai para este código.'))
      ->setSetting('target_type', 'pragmatica_code')
      ->setDisplayOptions('form', [
        'type' => 'options_select', // OU: entity_reference_autocomplete
        'weight' => -6,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => -6,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Criado em'))
      ->setDescription(t('Data e hora em que o código foi criado.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'datetime_default',
        'weight' => 1,
      ]);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Modificado em'))
      ->setDescription(t('Data e hora da última modificação do código.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'datetime_default',
        'weight' => 2,
      ]);

    return $fields;
  }

  function getCreatedTime() {
    return $this->get('created')->value;
  }
}
