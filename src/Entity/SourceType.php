<?php

namespace Drupal\pragmatica\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the SourceType content entity.
 *
 * @ContentEntityType(
 *   id = "pragmatica_source_type",
 *   label = @Translation("Tipo de fonte"),
 *   label_plural = @Translation("Tipos de fontes"),
 *   base_table = "pragmatica_source_type",
 *   admin_permission = "pragmatica",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name"
 *   },
 *   handlers = {
 *     "list_builder" = "Drupal\pragmatica\ListBuilder\SourceTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\pragmatica\Form\SourceTypeForm",
 *       "edit" = "Drupal\pragmatica\Form\SourceTypeForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     }
 *   },
 *   links = {
 *      "canonical" = "/admin/pragmatica/source_type/{pragmatica_source_type}",
 *      "add-form" = "/admin/pragmatica/source_type/add",
 *      "edit-form" = "/admin/pragmatica/source_type/{pragmatica_source_type}/edit",
 *      "delete-form" = "/admin/pragmatica/source_type/{pragmatica_source_type}/delete",
 *      "collection" = "/admin/pragmatica/source_type"
 *   },
 * )
 */
class SourceType extends ContentEntityBase {
  use EntityChangedTrait;

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Nome'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayOptions('view', [
        'type' => 'string',
        'label' => 'above',
        'weight' => -5,
      ]);

    $fields['description'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Descrição'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 0,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Criado em'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Atualizado em'));

    return $fields;
  }
}

