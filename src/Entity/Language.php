<?php

namespace Drupal\pragmatica\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Language content entity.
 *
 * @ContentEntityType(
 *   id = "pragmatica_language",
 *   label = @Translation("Língua"),
 *   label_plural = @Translation("Línguas"),
 *   base_table = "pragmatica_language",
 *   admin_permission = "pragmatica",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name"
 *   },
 *   handlers = {
 *     "list_builder" = "Drupal\pragmatica\ListBuilder\PragmaticaBaseListBuilder",
 *     "form" = {
 *       "add" = "Drupal\pragmatica\Form\PragmaticaBaseForm",
 *       "edit" = "Drupal\pragmatica\Form\PragmaticaBaseForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     }
 *   },
 *   links = {
 *     "canonical" = "/admin/pragmatica/language/{pragmatica_language}",
 *     "add-form" = "/admin/pragmatica/language/add",
 *     "edit-form" = "/admin/pragmatica/language/{pragmatica_language}/edit",
 *     "delete-form" = "/admin/pragmatica/language/{pragmatica_language}/delete",
 *     "collection" = "/admin/pragmatica/language"
 *   }
 * )
 */
class Language extends PragmaticaBaseEntity {

  public static function getFieldsIds(): array {
    return ['id', 'code', 'name', 'short_name', 'created', 'changed'];
  }

  public static function getFieldsToXmlMapping(): array {
    return parent::addFieldsToXmlMapping([], self::getFieldsIds());
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields['short_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Código'))
      ->setDescription(t('Código abreviado da língua (ex: ALE, BRA).'))
      ->setSettings(['max_length' => 16])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 2,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 2,
      ]);
    return self::addBaseFieldDefinitions($fields, self::getFieldsIds());
  }

}
