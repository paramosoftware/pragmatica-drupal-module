<?php

namespace Drupal\pragmatica\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\pragmatica\Form\PragmaticaPublicSearchForm;

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
    $query_params = $request->request->all();
    $results = [];

    $form = new PragmaticaPublicSearchForm();
    $form->setFormValues($query_params);
    $response_storage = $this->entityTypeManager->getStorage('pragmatica_response');
    $query = $response_storage->getQuery();
    $query = $form->buildSearchQuery($query);
   
    $response_ids = $query->execute();

    if (!empty($response_ids)) {
      $responses = $response_storage->loadMultiple($response_ids);
      $results['responses'] = [];
      foreach ($responses as $response) {
        $results['responses'][] = [
          'name' => $response->label(),
          'url' => Url::fromRoute('pragmatica.public_response_item', ['pragmatica_response' => $response->id()])->toString(),
        ];
      }
    }

    $render_elements = [];

    $render_elements[] = [
      '#theme' => 'pragmatica_search_results',
      '#query' => '',
      '#results' => $results,
      '#filters' => $form->getFieldConfig(),
      '#attached' => [
        'library' => [
          'pragmatica/pragmatica'
        ],
      ],
    ];

    return $render_elements;
  }
}
