<?php

namespace Drupal\pragmatica\ListBuilder;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\pragmatica\Entity\SourceType;

class SourceTypeListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Nome');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var SourceType $entity */
    $row['id'] = $entity->id();
    $row['name'] = $entity->label();
    $default = parent::buildRow($entity);
    $row['operations'] = $default['operations'];

    return $row;
  }

}
