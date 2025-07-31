<?php

namespace Drupal\pragmatica\ListBuilder;

use Drupal;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\pragmatica\Entity\Source;

class SourceListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Nome');
    $header['guid'] = $this->t('GUID');
    $header['type'] = $this->t('Tipo');
    $header['created'] = $this->t('Criado em');
    $header['changed'] = $this->t('Atualizado em');
    $header['operations'] = $this->t('Operações');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var Source $entity */
    $row['id'] = $entity->id();
    $row['name'] = $entity->label();
    $row['guid'] = $entity->get('guid')->value;
    $row['type'] = $entity->get('type')->entity ? $entity->get('type')->entity->label() : '';
    $row['created'] = Drupal::service('date.formatter')->format($entity->get('created')->value, 'short');
    $row['changed'] = Drupal::service('date.formatter')->format($entity->get('changed')->value, 'short');

    $default = parent::buildRow($entity);
    $row['operations'] = $default['operations'];

    return $row;
  }

}
