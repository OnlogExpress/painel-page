function autoCompleteForm(nome, id, url_api, botao_novo) {

    var $inputTypeaheadName = $('input[name=' + nome + ']');
    var $inputTypeaheadID = $('input[name=' + id + ']');

    var Data = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 5,
        remote: {
            url: url_api + '/%QUERY%',
            wildcard: '%QUERY%'
        }
    });

    $inputTypeaheadName.typeahead(null, {
        name: 'id',
        display: 'name',
        source: Data,
        templates: {
            suggestion: function (data) {
                return  '<div class="text-danger fw-500">' + data.name + '</div>';
            },
            empty: [
                '<div class="text-danger fw-500 p-20 text-center">',
                'Dados não encontrado<br>',
                  botao_novo,
                '</div>'
            ].join('\n')
        },
        autoSelect: true
    });

    $inputTypeaheadName.bind('typeahead:select', function (ev, data) {
        $inputTypeaheadID.val(data.id);
    });
}