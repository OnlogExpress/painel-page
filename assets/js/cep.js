/*!
 * https://viacep.com.br
 * Licensed under the MIT license (http://viacep.com.br)
 */

function cep_origem(conteudo) {
    if (!("erro" in conteudo)) {
        $("#show-local-origim").html("<small class='text-success'>" + conteudo.localidade + "-" + conteudo.uf + "</small>");
        // $("#show-local-origim").html("<small class='text-danger'>CEP não encontrado</small>");
    } //end if.
    else {
        document.getElementById('cep_origem').value = ("");
        document.getElementById('cep_origem').focus();
        app.toast('Formato de CEP inválido.');
    }
}

function cep_destino(conteudo) {
    if (!("erro" in conteudo)) {
       // $("#show-local-destino").html("<small class='text-success fs-12'>" + conteudo.bairro + ' - ' + conteudo.localidade + "-" + conteudo.uf + "</small>");
        document.getElementById('rua').value = (conteudo.logradouro);
        document.getElementById('bairro').value = (conteudo.bairro);
        document.getElementById('cidade').value = (conteudo.localidade);
        document.getElementById('uf').value = (conteudo.uf);
        document.getElementById('number').focus();
        show_hide_card_loading(false);
    } else {
        document.getElementById('cep_destino').value = ("");
        $("#show-local-origim").html("<small class='text-danger'>CEP não encontrado</small>");
        returnMSG('Formato de CEP inválido.');
        show_hide_card_loading(false);
    }
}

function cepCallback(conteudo) {
    if (!("erro" in conteudo)){
        return true;
    }else{
        return false;
    }
}

function pesquisacep(valor, callback) {
    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');
    //Verifica se campo cep possui valor informado.
    if (cep != "") {
        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;
        show_hide_card_loading();
        //Valida o formato do CEP.
        if (validacep.test(cep)) {
            //Cria um elemento javascript.
            var script = document.createElement('script');
            //Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=' + callback + '';
            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);
        } else {
            //cep é inválido.
            document.getElementById(callback).value = ("");
            returnMSG('Formato de CEP inválido.');
            show_hide_card_loading(false);
        }
    } else {
        document.getElementById(callback).value = ("");
        returnMSG('Formato de CEP inválido.');
        show_hide_card_loading(false);
    }
};

function validarDocumento(valor, url) {
    //Nova variável "cep" somente com dígitos.

    var documento = valor.replace(/\D/g, '');
    var valida;
    //Verifica se campo cep possui valor informado.
    if (documento != "") {

        //Expressão regular para validar o CPF e CNPJ.
        if(documento.length == 11){
            valida = /^[0-9]{11}$/;
        }else if (documento.length == 14){
            valida = /^[0-9]{14}$/;
        }else{
            returnMSG('Formato de CPF ou CNPJ não e válido!');
            show_hide_card_loading(false);
            return false;
        }
        //Valida o formato do CEP.
        if (valida.test(documento)) {
            $.ajax({
                type: "POST",
                url: url,
                data: { documento: documento },
                dataType: 'json',
                beforeSend: function() {
                    show_hide_card_loading();
                },
                success: function(data) {
                    if (data.erro) {
                        document.getElementById('destinatario').focus();
                        show_hide_messagem(data.erro.message, false);
                    }
                    show_hide_card_loading(false);
                }
            });
        } else {
            returnMSG('Formato de CNPJ inválido.')
            show_hide_card_loading(false);
        }
    } //end if.
    else {
        document.getElementById('destinatario').value = ("");
    }
};