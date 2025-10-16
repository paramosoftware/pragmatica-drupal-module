<?php


namespace Drupal\pragmatica\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\pragmatica\Entity\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for displaying responses publicly.
 */
class ResponsePublicController extends ControllerBase
{

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager)
  {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Displays a single response entity.
   */
  public function item(Response $pragmatica_response): array
  {


    $informant = $pragmatica_response->get('informant_id')->entity;
    $processed_informant = [
      'name' => $informant->label(),
      'id' => $informant->id(),
    ];

    // response selections
    $selection_storage = $this->entityTypeManager->getStorage('pragmatica_selection');
    $query = $selection_storage->getQuery();
    $query->condition('response_id', $pragmatica_response->id());
    $selection_ids = $query->execute();
    $selections = $selection_storage->loadMultiple($selection_ids);
    $processed_labels = [];

    foreach ($selections as $selection) {
      $processed_labels[] = [
        'name' => $selection->get('label_id')->entity->label(),
        'id' => $selection->get('label_id')->entity->id()
      ];

    }



    $build['#theme'] = 'pragmatica_response_item';
    $build['#response'] = $pragmatica_response;
    $build['#informant'] = $processed_informant;
    $build['#labels'] = $processed_labels;
    $build['#attached'] = [
      'library' => [
        'pragmatica/pragmatica_styles',
      ],
    ];

    return $build;
  }

  public function itemTitle(Response $pragmatica_response)
  {
    return $pragmatica_response->label();
  }
}
