# Pragmática - Módulo para Drupal 9

Módulo para Drupal 9 para importar, gerenciar e exibir dados de análises linguísticas resultantes pesquisa realizada pelo
[Grupo de Pesquisa - Pragmática (inter)linguística, intercultural e cross-cultural (GPP)](https://www.gppragmatica-usp.com/).
O módulo é desenvolvido especificamente para ser usado com a instalação do [Drupal da FFLCH-USP](https://github.com/fflch/drupal).

## Instalação

Para instalação do Drupal 9 com o tema da FFLCH, siga as instruções em [docs/instalacao-drupal-fflch.md](docs/instalacao-drupal-fflch.md).

Depois de instalar o Drupal, siga as instruções abaixo para instalar o módulo Pragmática:

1. Na raiz projeto do Drupal, clone o repositório do módulo Pragmática:

   ```bash
   git clone https://github.com/paramosoftware/pragmatica-drupal-module.git web/modules/custom/pragmatica
   ```

2. Instale o módulo usando Drush:
   ```bash
   ./vendor/bin/drush en pragmatica -y
   ```


## Comandos do Drush

O executável do Drush deve estar instaldo em `./vendor/bin/drush`. Para evitar problema com permissões, é recomendado
executar os comandos do Drush como o usuário do servidor web, como a seguir:

```bash
sudo -u www-data ./vendor/bin/drush <comando>
```

**Limpeza de cache:** `drush cr` ou `drush cache-rebuild`
**Atualização do banco de dados:** `drush updb` ou `drush updatedb`
**Verificar atualizações nas Entities:** `drush updbst` ou `drush updatedb:status`

## Problemas conhecidos

## Atualização do banco de dados
Por alguma razão, o schema do banco de dados não é atualizado automaticamente ao executar `drush updb`, apesar de alterações
serem listadas com comando `drush updbst`.

Normalmente, desinstalar e reinstalar o módulo Pragmática atualiza o schema do banco de dados, porém caso haja uma mudança
ainda não executada, ao tentar desinstalar o módulo ocorrerá um erro de tabela inexistente, como o seguinte:
`SQLSTATE[42S02]: Base table or view not found: 1146 Table 'pragmatica_code' doesn't exist`

Nesses casos, para atualizar o schema é necessário apagar as atualizações registradas na tabela
`key_value` com a query:

```sql
DELETE FROM key_value WHERE name like 'pragmatica%';
```

A query apaga todas as atualizações registradas para o módulo Pragmática. É possível ser mais específico e apagar apenas
as atualizações de uma unica `Entity`, com `name like '<entity_type>%'`, onde `<entity_type>` é o tipo de entidade,
por exemplo, `pragmatica_code`.

Depois disso, é necessário registrar manualmente as atualizações do banco de dados com o comando:

```php
sudo -u www-data ./vendor/bin/drush php:eval '
                              $module = "pragmatica";

                              $entity_manager = \Drupal::service("entity_type.manager");
                              $definition_update_manager = \Drupal::service("entity.definition_update_manager");
                              $definitions = $entity_manager->getDefinitions();

                              foreach ($definitions as $entity_type_id => $definition) {
                                  if ($definition->getProvider() === $module) {
                                      echo "Installing: $entity_type_id\n";
                                      try {
                                          $definition_update_manager->installEntityType($definition);
                                      } catch (\Exception $e) {
                                          echo "Error to install $entity_type_id: " . $e->getMessage() . "\n";
                                      }
                                  }
                              }
                              '
```

**Para futuras investigações:**
Aparentemente, o comando drush `updb` somente registra a informação abaixo na tabela `key_value`:

| collection    | name       | value   |
|---------------|------------|---------|
| system.schema | pragmatica | i:8000; |

Indicando que o módulo Pragmática foi atualizado para a versão/hook 8000 (?)
