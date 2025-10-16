<?php

namespace Drupal\pragmatica\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\pragmatica\Entity\LabelType;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for displaying label_type types publicly.
 */
class LabelTypePublicController extends ControllerBase {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Displays a single label_type type entity.
   */
  public function item(LabelType $pragmatica_label_type): array {


    $build['#theme'] = 'pragmatica_label_type_item';
    $build['#label_type'] = $pragmatica_label_type;
    $build['#attached'] = [
      'library' => [
        'pragmatica/pragmatica_styles',
      ],
    ];

    return $build;
  }

  public function itemTitle(LabelType $pragmatica_label_type) {
    return $pragmatica_label_type->label_type();
  }
}
