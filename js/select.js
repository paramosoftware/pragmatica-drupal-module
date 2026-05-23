(function (Drupal) {
  'use strict';
    $(function () {
        $('[data-multiplejs="true"]').multipleSelect({
            formatSelectAll: function () {
                return 'Selecionar todos';
            },
            formatAllSelected: function () {
                return 'Todos selecionados';
            },
            formatCountSelected: function (num, total) {
                return num + ' de ' + total + ' selecionado(s)';
            },
            formatNoMatchesFound: function () {
                return 'Nenhum resultado encontrado';
            },
            filter: true
        });
    })
})(Drupal);