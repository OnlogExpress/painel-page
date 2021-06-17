var quantidade_resultado_pagina = 10;
var pagina_inicial = 1;

function lista_registro(url, pagina_inicial, quantidade_resultado_pagina) {
    var data = {
        pagina_inicial: pagina_inicial,
        quantidade_resultado_pagina: quantidade_resultado_pagina,
        route: url,
    }
    $.post(url, data, function (response) {
        $('#table-result').html(response);
    });
}
