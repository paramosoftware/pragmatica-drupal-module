<?php

namespace Drupal\pragmatica\Importer;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CSVResearchImportForm extends FormBase {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function getFormId() {
    return 'pragmatica_csv_research_import_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['csv_file'] = [
      '#type' => 'file',
      '#title' => $this->t('Arquivo CSV de dados de pesquisa (.csv)'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
      '#required' => FALSE,
    ];

    $form['reset'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Apagar todos os dados de pesquisa (informantes, respostas, seleções) antes de importar'),
      '#default_value' => FALSE,
    ];

    $form['column_mapping'] = [
      '#type' => 'details',
      '#title' => $this->t('Mapeamento de colunas'),
      '#open' => TRUE,
      '#description' => $this->t('Informe o nome da coluna no arquivo CSV para cada campo. Deixe em branco para ignorar.'),
    ];

    $defaults = [
      'id' => 'ID',
      'language' => 'Lingua',
      'age_interval' => 'Idade',
      'gender' => 'Genero',
      'residence' => 'Residencia',
      'education' => 'Escolaridade',
      'profession' => 'Profissao',
      'situation' => 'Situacao',
      'response' => 'pedido',
    ];

    $labels = [
      'id' => 'Código do informante (ID)',
      'language' => 'Língua (código numérico 1-13)',
      'age_interval' => 'Intervalo de idade (código numérico 1-6)',
      'gender' => 'Gênero (código numérico 1=F, 2=M, 3=O)',
      'residence' => 'Residência (nome da cidade)',
      'education' => 'Escolaridade (nome)',
      'profession' => 'Profissão (nome)',
      'situation' => 'Situação (código, ex: Sede, Garrafa)',
      'response' => 'Texto do pedido (com etiquetas XML)',
    ];

    foreach ($defaults as $key => $default) {
      $form['column_mapping'][$key] = [
        '#type' => 'textfield',
        '#title' => $this->t($labels[$key]),
        '#default_value' => $default,
        '#size' => 30,
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Importar dados de pesquisa'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $files = \Drupal::request()->files->get('files', []);
    if (empty($files['csv_file']) || !$files['csv_file']->isValid()) {
      $form_state->setErrorByName('csv_file', $this->t('Por favor, envie um arquivo CSV.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $reset = (bool) $form_state->getValue('reset');

    $column_mapping = [];
    foreach (['id', 'language', 'age_interval', 'gender', 'residence', 'education', 'profession', 'situation', 'response'] as $key) {
      $val = $form_state->getValue($key);
      if (!empty($val)) {
        $column_mapping[$key] = $val;
      }
    }

    $files = \Drupal::request()->files->get('files', []);
    $csv_path = $files['csv_file']->getPathname();

    try {
      $importer = new CSVImporter($this->entityTypeManager);
      $result = $importer->importResearchData($csv_path, $column_mapping, $reset);

      $this->messenger()->addStatus($this->t(
        'Importação concluída: @i informante(s), @r resposta(s), @s seleção(ões).',
        [
          '@i' => $result['informants'],
          '@r' => $result['responses'],
          '@s' => $result['selections'],
        ]
      ));
      foreach ($result['errors'] as $err) {
        $this->messenger()->addWarning($err);
      }
    }
    catch (\Exception $e) {
      $this->messenger()->addError($this->t('Erro na importação: @msg', ['@msg' => $e->getMessage()]));
    }
  }

}
