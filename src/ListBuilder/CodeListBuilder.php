<?php

namespace Drupal\pragmatica\ListBuilder;

use Drupal;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Render\Markup;
use Drupal\pragmatica\Entity\Code;


class CodeListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Nome');
    $header['guid'] = $this->t('GUID');
    $header['color'] = $this->t('Cor');
    $header['parent'] = $this->t('Código superior');
    $header['is_codeble'] = $this->t('Pode ser usado?');
    $header['created'] = $this->t('Criado em');
    $header['changed'] = $this->t('Atualizado em');
    $header['operations'] = $this->t('Operações');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var Code $entity */
    $row['id'] = $entity->id();
    $row['name'] = $entity->label();
    $row['guid'] = $entity->get('guid')->value;
    $row['color'] = $this->getColorHTML($entity->get('color')->value);
    $parent = $entity->get('parent')->entity;
    $row['parent'] = $parent ? $parent->label() : '';
    $row['is_codeble'] = $entity->get('is_codeble')->value ? $this->t('Sim') : $this->t('Não');
    $row['created'] = $this->getFormatedDateTime($entity->get('created')->value);
    $row['changed'] = $this->getFormatedDateTime($entity->get('changed')->value);

    $default = parent::buildRow($entity);
    $row['operations'] = $default['operations'];

    return $row;
  }
  private function getColorHTML($color = '') {
    return Markup::create('<div style="width: 20px; height: 20px; background-color: ' . htmlspecialchars($color) . '; border: 1px solid #ccc;"></div>');
  }

  private function getFormatedDateTime($datetime='') {
    return Drupal::service('date.formatter')->format($datetime, 'short');
  }
}
