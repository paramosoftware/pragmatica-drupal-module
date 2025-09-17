<?php

namespace Drupal\pragmatica\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class PragmaticaPublicController extends ControllerBase {

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
   * @param  \Symfony\Component\HttpFoundation\Request  $request
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @todo: Highlight search results in the UI.
   * @todo: Include selections as results.
   */
  public function search(Request $request) {
    $query_term = $request->query->get('q');
    $results = [];

    $label_storage = $this->entityTypeManager()->getStorage('pragmatica_label');
    $label_query = $label_storage->getQuery();

    if (!empty($query_term)) {
      $query_term = trim($query_term);

      $label_or_condition = $label_query->orConditionGroup()
        ->condition('name', $query_term, 'CONTAINS')
        ->condition('description', $query_term, 'CONTAINS');

      $label_query->condition($label_or_condition);

      $label_ids = $label_query->execute();
      $label_results = $label_storage->loadMultiple($label_ids);

      if (!empty($label_results)) {
        $results['labels'] = [];
        foreach ($label_results as $label) {
          $results['labels'][] = [
            'name' => $label->label(),
            'url' => Url::fromRoute('pragmatica.label_public_item', ['pragmatica_label' => $label->id()])->toString(),
          ];
        }
      }
    }

    return [
      '#theme' => 'pragmatica_search_results',
      '#query' => $query_term,
      '#results' => $results,
      '#attached' => [
        'library' => [
          'pragmatica/pragmatica_styles',
        ],
      ],
    ];
  }
}
