<?php

namespace Drupal\pragmatica\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Source content entity.
 *
 * @ContentEntityType(
 *   id = "pragmatica_source",
 *   label = @Translation("Fonte"),
 *   label_plural = @Translation("Fontes"),
 *   base_table = "pragmatica_source",
 *   admin_permission = "pragmatica",
 *   handlers = {
 *     "list_builder" = "Drupal\pragmatica\ListBuilder\SourceListBuilder",
 *     "form" = {
 *       "add" = "Drupal\pragmatica\Form\SourceForm",
 *       "edit" = "Drupal\pragmatica\Form\SourceForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name"
 *   },
 *   links = {
 *     "canonical" = "/admin/pragmatica/source/{pragmatica_source}",
 *     "add-form" = "/admin/pragmatica/source/add",
 *     "edit-form" = "/admin/pragmatica/source/{pragmatica_source}/edit",
 *     "delete-form" = "/admin/pragmatica/source/{pragmatica_source}/delete",
 *     "collection" = "/admin/pragmatica/source"
 *   }
 * )
 */
class Source extends ContentEntityBase {

  use EntityChangedTrait;

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['guid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('GUID'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 36)
      ->setDescription(t('Código único global (GUID) de identificação.'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ]);

    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Tipo de Fonte'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'pragmatica_source_type')
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -4,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => -4,
      ]);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Nome'))
      ->setRequired(TRUE)
      ->setSettings(['max_length' => 255])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -3,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -3,
      ]);

    $fields['description'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Descrição'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => -2,
      ])->
      setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => -2,
      ]);

    $fields['plain_text'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Texto da fonte'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 3,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 3,
      ]);

    $fields['file'] = BaseFieldDefinition::create('file')
      ->setLabel(t('Arquivo da fonte em formato rich text'))
      ->setDescription(t('Carregue um arquivo DOCX contendo o texto da fonte.'))
      ->setSetting('file_extensions', 'docx')
      ->setDisplayOptions('form', [
        'type' => 'file_generic',
        'weight' => 4,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'file_default',
        'weight' => 4,
      ]);

    $fields['media'] = BaseFieldDefinition::create('file')
      ->setLabel(t('Arquivo de mídia'))
      ->setDescription(t('Carregue um arquivo de mídia relacionado à fonte: áudio, vídeo, imagem ou documento.'))
      ->setSetting('file_extensions', 'mp3,mp4,pdf,jpg,jpeg,png')
      ->setDisplayOptions('form', [
        'type' => 'file_generic',
        'weight' => 5,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'file_default',
        'weight' => 5,
      ]);

    $fields['url'] = BaseFieldDefinition::create('link')
      ->setLabel(t('URL'))
      ->setDescription(t('URL da fonte, se aplicável.'))
      ->setSetting('link_type', \Drupal\link\LinkItemInterface::LINK_EXTERNAL)
      ->setDisplayOptions('form', [
        'type' => 'link_default',
        'weight' => 6,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'link_default',
        'weight' => 6,
      ]);


    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Criado'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Modificado'));

    return $fields;
  }

}

