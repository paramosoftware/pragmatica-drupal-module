<?php

namespace Drupal\pragmatica\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Situation content entity.
 *
 * @ContentEntityType(
 *   id = "pragmatica_situation",
 *   label = @Translation("Situação"),
 *   label_plural = @Translation("Situações"),
 *   base_table = "pragmatica_situation",
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
 *     "canonical" = "/admin/pragmatica/situation/{pragmatica_situation}",
 *     "add-form" = "/admin/pragmatica/situation/add",
 *     "edit-form" = "/admin/pragmatica/situation/{pragmatica_situation}/edit",
 *     "delete-form" = "/admin/pragmatica/situation/{pragmatica_situation}/delete",
 *     "collection" = "/admin/pragmatica/situation"
 *   }
 * )
 */
class Situation extends PragmaticaBaseEntity {

  public static function getFieldsIds(): array {
    return [
      'id',
      'guid',
      'name',
      'created',
      'changed',
      'short_name',
      'code_id'
    ];
  }

  public static function getFieldsToXmlMapping(): array {
    return parent::addFieldsToXmlMapping([], self::getFieldsIds());
  }

  public function getListHeaders(): array {
    $parent = parent::getListHeaders();
    $header['code_id'] = t('Código');
    return $this->addItemsAfterKeyInArray($header, $parent, 'id');
  }

  public function getCodeLabel(): string {
    if ($this->get('code_id')->entity) {
      return $this->get('code_id')->entity->label();
    }
    return '';
  }

  public function buildListRow(PragmaticaBaseEntity $entity): array {
    /** @var self $entity */
    $row = parent::buildListRow($entity);
    $row['code_id'] = $entity->getCodeLabel();
    return $row;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
      $fields['code_id'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Código'))
        ->setDescription(t('Código associado'))
        ->setSetting('target_type', 'pragmatica_code')
        ->setRequired(FALSE)
        ->setDisplayOptions('form', [
          'type' => 'entity_reference_autocomplete',
          'weight' => 0,
        ])
        ->setDisplayOptions('view', [
          'label' => 'above',
          'type' => 'entity_reference_label',
          'weight' => 0,
        ]);

    $fields['short_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Nome abreviado'))
      ->setSettings(['max_length' => 36])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 9,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 9,
      ]);

    return self::addBaseFieldDefinitions($fields, self::getFieldsIds());
  }
}
