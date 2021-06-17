var num_pagina = 25;
var num_inicial = 1;

function lista_dados_table(url, pagina_inicial, quantidade_pagina) {

	var data = {
		pagina_inicial: pagina_inicial,
		quantidade_pagina: quantidade_pagina,
		route: url,
	}

	var load = $(".ajax_load");
	return new Promise((resolve, reject) => {
		$.ajax({
			type: "POST",
			url: url,
			data: data,
			beforeSend: function () {
				load.fadeIn(200).css("display", "flex");
			},
			success: function (response) {
				$('#table-result').html(response);
				load.fadeOut(200);
			},
			error: function (e) {
				toast('error', e.responseJSON.message);
				load.fadeOut(200);
			}
		});
		return false;
	});
}

function limitaCaracteresInputTextarea(attr) {
    $('#' + attr).keyup(function () {
        var maxLength = parseInt($(this).attr('maxlength'));
        var length = $(this).val().length;
        var newLength = maxLength - length;
        var name = $(this).attr('name');
        $('small[name="' + name + '"]').text("Caracteres restantes " + newLength);
        if (length === maxLength) {
            swal({
                html: 'O maximo de caracteres no campo [<strong>' + name + '</strong>] foi atigindo.',
                showConfirmButton: false,
                focusConfirm: false,
                background: '#FFF'
            });
        }
    });
}

function modalLoad() {
    var htmlModalShow = '<div class="modal modal-center fade" id="modal-center" tabindex="-1" data-backdrop="static">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-body text-center">' +
        '<div class="h-100px center-vh">' +
        '<svg class="spinner-circle-material-svg" viewBox="0 0 50 50">' +
        '<circle class="circle" cx="25" cy="25" r="20">' +
        '</svg>' +
        '</div>' +
        '<span class="lead text-info fw-400">Aguarde, processando dados.</span>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';

    return htmlModalShow;

    $('#modal-center').modal('show');
}

function mostrarResultado(box, num_max, campospan) {
    var contagem_carac = box.length;
    if (contagem_carac != 0) {
        document.getElementById(campospan).innerHTML = contagem_carac + " caracteres digitados";
        if (contagem_carac == 1) {
            document.getElementById(campospan).innerHTML = contagem_carac + " caracter digitado";
        }
        if (contagem_carac >= num_max) {
            document.getElementById(campospan).innerHTML = "Limite de caracteres excedido!";
        }
    } else {
        document.getElementById(campospan).innerHTML = "Ainda não temos nada digitado..";
    }
}

function contarCaracteres(box, valor, campospan) {
    var conta = valor - box.length;
    document.getElementById(campospan).innerHTML = "Você ainda pode digitar " + conta + " caracteres";
    if (box.length >= valor) {
        document.getElementById(campospan).innerHTML = "Opss.. você não pode mais digitar..";
        document.getElementById("campo").value = document.getElementById("campo").value.substr(0, valor);
    }
}

function split(val) {
    return val.split(/;\s*/);
}

function extractLast(term) {
    return split(term).pop();
}

function show_hide_messagem(message, value = true) {

    if (value) {
        $(".msg-result").show();
        $(".msg-result").html('<div class="bg-success p-20">' + message + '</div>');
    } else if(value === false) {
        $(".msg-result").show();
        $(".msg-result").html('<div class="bg-danger p-20">' + message + '</div>');
    }else{
        $(".msg-result").show();
        $(".msg-result").html('<div class="bg-'+value+' p-20">' + message + '</div>');
    }
    // setTimeout(function () {
    //     $(".msg-result").hide();
    // }, 3000);
}

function returnMSG(data) {
    swal({
        text: data,
        showConfirmButton: false,
        focusConfirm: false,
        background: '#FFF',
        timer: 2000
    });
}

function checaInputValor(valor = "", tipo) {
    if (tipo == 'height') {
        if (valor == "") {
            $("#" + tipo).val('4');
        }
    } else if (tipo == 'width') {
        if (valor == "") {
            $("#" + tipo).val('11');
        }
    } else if (tipo == 'length') {
        if (valor == "") {
            $("#" + tipo).val('16');
        }
    }
}

function consultaFrete() {
   var data = [];
    $(".msg-result").hide();
    var cep = $('input[name="zip_code"]').val();
    var peso = $('input[name="weight"]').val();
    var price_declared = $('#price_declared').val();
    var altura = $('#height').val();
    var largura = $('#width').val();
    var comprimento = $('#length').val();

    if(!cep){
        returnMSG('Preencha o campo CEP por favor!');
        $(".cep").addClass("border-danger");
        return false;
    }else if (!peso){
        returnMSG('Preencha o campo peso por favor!');
        return false;
    }

    data = {
        length: comprimento,
        width: largura,
        height: altura,
        weight: peso,
        price_declared: price_declared,
        zip_code:cep
    };

    $.ajax({
        type: "POST",
        url: "/consulta/frete",
        data: data,
        dataType: 'JSON',
        beforeSend: function () {
            show_hide_card_loading(true);
        },
        success: function (resultado) {
            if(!resultado.erro){
                app.modaler({
                    html: resultado,
                    size: 'lg',
                    title: 'Valor e Prazo do Frete',
                    footerVisible: false,
                });
            }else{
                show_hide_messagem(resultado.erro, false);
            }
            spinner_circle_shadow(false);
            show_hide_card_loading(false);
            show_hide_card_loading(false, 'two');
        }
    });
}

function ajaxPost(peso_real = true, urlPost) {
    var data = [];

    $(".msg-result").hide();
    var valor_declarado = $('#vadl').val();
    if (peso_real) {

        var peso = $('#peso').val();
        var peso_cubico = $('#pesocunico').val();

        if (peso > 50000) {
            returnMSG('o peso ultrapassou o limite maximo de 50kg');
            $('#peso').val("");
            $('#peso').focus();
            show_hide_card_loading(false, 'two');
            return false;
        }
        data = {
            peso_real: peso,
            peso_cubico: peso_cubico,
            valor_declarado: valor_declarado
        };
    } else {

        var comprimento = $('#comprimento').val();
        var largura = $('#largura').val();
        var altura = $('#altura').val();
        var peso = $('#peso').val();

        if ((comprimento == '') || (largura == '') || (altura == '')) {
            returnMSG('preencha todos os campos: altura, largura e comprimento');
            show_hide_card_loading(false, 'two');
            return false;
        }

        data = {
            comprimento: comprimento,
            largura: largura,
            altura: altura,
            peso_real: peso,
            valor_declarado: valor_declarado
        };

        result = (comprimento * largura * altura) / 6;
        if (result.toFixed(0) > 50000) {
            returnMSG('o peso ultrapassou o limite maximo de 50kg');
            return false;
        }
        $('#pesocunico').val(result.toFixed(0));
    }
    $.ajax({
        type: "POST",
        url: urlPost,
        data: data,
        dataType: 'json',
        beforeSend: function () {
            show_hide_card_loading(true, 'two');
        },
        success: function (response) {
            if (response.data.return === true) {
                $("#vfrete").text('R$ ' + response.data.frete);
                $("#fretevalor").val(response.data.frete);
                $("#vfrete-hide").show();
            }
            show_hide_card_loading(false, 'two');
        }
    });
}

function spinner_circle_shadow(r = true) {
    if(r){
        $("#scs_load").show();
    }else{
        $("#scs_load").hide();
    }

}

function show_hide_card_loading(value = true, spinner = null) {
    if (spinner) {
        spinner = 'card-loading-' + spinner;
    } else {
        spinner = 'card-loading';
    }
    var loading = '<div class="card-loading reveal">';
    loading += '<svg class="spinner-circle-material-svg" viewBox="0 0 50 50">';
    loading += '<circle class="circle" cx="25" cy="25" r="20"></circle>';
    loading += '</svg></div>';
    $('#' + spinner).html(loading);
    if (value) {
        $("#" + spinner).show();
    } else {
        $("#" + spinner).hide();
    }
}

function ajaxConsultaEtiqueta(etiqueta, urlPost) {
    var data = {
        etiqueta: etiqueta,
    };

    $(".msg-result").hide();
    $.ajax({
        type: "POST",
        url: urlPost,
        data: data,
        dataType: 'json',
        beforeSend: function () {
            show_hide_card_loading(true);
        },
        success: function (response) {
            if (response.retorno == false) {
                if (response.sub_retorno == 1) {
                    returnMSG(response.msg.descricao);
                    $('#etiquetaMsg').html(
                        '<div class="alert alert-danger">' + response.msg.descricao + '</div>'
                    )
                    document.getElementById('etiq').style.borderColor = 'red';
                } else {
                    swal({
                        title: response.msg.titulo,
                        text: response.msg.descricao,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sim, Editar!',
                        cancelButtonText: 'Não, Cancele!',
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false
                    }).then(function () {
                        returnMSG('Aguarde 2 segundos que você será redirecionado para pagina de edição do produto');
                        setTimeout(function () {
                            window.location.href = response.returtUrl
                        }, 2000);
                        return false;
                    }, function(dismiss) {
                        document.getElementById('etiq').value = ("");
                    });
                }
                show_hide_card_loading(false);
                return false;
            } else {

                $('#etiquetaMsg').hide();
                $("#hide").show();
                $('#etiq').attr('readonly', true);
                show_hide_card_loading(false);
                return false
            }
            show_hide_card_loading(false);
            return false
        }
    });

    return false
}

function excluirDados(idValue, urlPost, loadPage) {

    $(".msg-result").hide();
    swal({
        title: 'Você tem certeza?',
        text: "Você não poderá reverter isso!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, exclua-o!',
        cancelButtonText: 'Não, Cancele!',
        confirmButtonClass: 'btn btn-primary',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false
    }).then(function () {

        $.ajax({
            type: "POST",
            url: urlPost,
            data: {
                id: idValue
            },
            dataType: 'json',
            beforeSend: function () {
                show_hide_card_loading(true, idValue);
            },
            success: function (data) {
                if (data.success) {
                    $("#atualizar").load(loadPage);
                    show_hide_messagem(data.success);
                } else {
                    $("#atualizar").load(loadPage);
                    show_hide_messagem(data.erro, false);
                }
                show_hide_card_loading(false, idValue);
                setTimeout(function (e) {
                    location.reload();
                }, 2000)
            }
        });
        return false;
    }, function (dismiss) {
    })
}

function finalizar(urlPost, text) {

    $(".msg-result").hide();
    swal({
        title: 'Você tem certeza?',
        text: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim!',
        cancelButtonText: 'Não!',
        confirmButtonClass: 'btn btn-primary',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false
    }).then(function () {

        $.ajax({
            type: "POST",
            url: urlPost,
			data:{
            action:'action'
			},
            dataType: 'JSON',
            beforeSend: function () {
                show_hide_card_loading(true);
            },
            success: function (data) {
                if (data.success) {
                    $('#atualizar').load(window.location.href+" #atualizar");
                    show_hide_messagem(data.success);
                } else {
                    $("#atualizar").load(window.location.href+" #atualizar");
                    show_hide_messagem(data.erro, false);
                }
                show_hide_card_loading(false);
            }
        });
        return false;
    }, function (dismiss) {
    })
}

function acusar_pedido(idValue, urlPost, loadPage) {

    $.ajax({
        type: "POST",
        url: urlPost,
        data: {
            id: idValue
        },
        dataType: 'json',
        beforeSend: function () {
            show_hide_card_loading(true);
        },
        success: function (data) {
            if (data.success) {
                show_hide_messagem(data.success);
            } else {
                show_hide_messagem(data.erro, false);
            }
            $("#atualizar").load(loadPage+' #atualizar');
            show_hide_card_loading(false);
        }
    });
    return false;
}

function isEmptyJsonPHP(obj) {
    for (var prop in obj){
        if (obj.hasOwnProperty(prop))
            return false;
    }
    return true;
}

function  myRandom(min, max, multiple) {
    return Math.round(Math.random() * (max - min) / multiple) * multiple + min;
}

function dec2hex (dec) {
    return ('0' + dec.toString(16)).substr(-2)
}

// generateId :: Integer -> String
function generateId (len) {
    var arr = new Uint8Array((len || 40) / 2)
    window.crypto.getRandomValues(arr)
    return Array.from(arr, dec2hex).join('')
}

function ajaxSearch(ticket, urlSearchParams) {
    var data = {
        ticket: ticket,
    };
    $(".msg-result").hide();
    $("#hide").hide();
    $.ajax({
        type: "POST",
        url: urlSearchParams,
        data: data,
        dataType: 'json',
        beforeSend: function () {
            show_hide_card_loading(true);
        },
        success: function (response) {
            $("#hide").show();
            if(response.erro){
                $("#table").hide();
                $("#hidde").hide();
                $("#load_messege").show();
                $("#load_messege").html(response.erro);
                $("#objeto").val('');
                document.getElementById('form-conferencia').reset();
            }else{
                $("#hidde").show();
                $("#table").show();
                $("#load_messege").hide();
                $("#load_data_table").html(response.data);
                $("#weight").focus();
            }
            show_hide_card_loading(false);
            return false
        }
    });

    return false
}

$('#status-view').on('change', function () {
    var value = $(this).val();
    if(value != ""){
        window.location.href = value;
        return false;
    }
    window.location.href = '/postagem';
})

$(document).ready(function () {
    setTimeout(function (e) {
        location.reload();
    }, 3600000*2)
});

function abrir(URL, w = 600, h = 800) {
    window.open(URL, '_black', 'height='+h+', width='+w+', left='+(window.innerWidth-w)/2+', top='+(window.innerHeight-h)/2);
}

$(document).on('keyup', 'input[name=document_recipient]', function() {
    var document = $(this).val().replace(/\D/g, '');
    var idcode = $('input[name=id_destination]').val();
    if (document.length === 11 || document.length === 14) {
        
    }
});

function  consultaDocument() {
    var idcode = $('input[name=id_destination]').val()
    $.ajax({
        type: "POST",
        url: "/modal/document",
        data: {document_recipient:idcode},
        dataType: 'json',
        beforeSend: function () {
            show_hide_card_loading(true);
        },
        success: function (response) {
            show_hide_card_loading(false);
            if(response.success){
                app.modaler({
                    size: 'lg',
                    title: 'Confirmar Dados do Cliente',
                    footerVisible: false,
                    url: '/modal/data/'+idcode
                });
                $('input[name="zip_code"]').val(response.success.zip_code);
                return false;
            }else{
                app.modaler({
                    size: 'lg',
                    title: 'Cadastrar Destinatario',
                    footerVisible: false,
                    url: '/modal/add'
                });
                $('input[name="zip_code"]').val("");
                return false;
            }
        }
    });
    return false;
}


function searchAddress(orige) {

    if(orige == 'via-cep'){
        url_request = "/Recipient/addressViaCep";
    }else if(orige == 'address'){
        url_request = "/Recipient/addressViaLogradouro";
    }
    $.ajax({
            type: "POST",
            url: url_request,
            data: {
                zip_code_modal: $('input[name="zip_code_modal"]').val(),
                address_number_recipient: $('input[name="address_number_recipient"]').val(),
                address_recipient: $('input[name="address_recipient"]').val()
             },
            dataType: 'JSON',
            success: function(resultado) {
                if (!resultado.erro) {
                    $('input[name="address_recipient"]').val(resultado.address_formatted);
                    $('input[name="latitude"]').val(resultado.latitude);
                    $('input[name="longitude"]').val(resultado.longitude);
                    $('input[name="zip_code_modal"]').val(resultado.zip);
                    $('input[name="address_number_recipient"]').val(resultado.number);
                } else {
                    show_hide_messagem(resultado.erro, false);
                }
                show_hide_card_loading(false);
            }
        });
        return false;
}

function viaCepCallback(conteudo) {
    if (!("erro" in conteudo)) {
        $('input[name="street"]').val(conteudo.logradouro);
        $('input[name="neighborhood"]').val(conteudo.bairro);
        $('input[name="city"]').val(conteudo.localidade);
        $('input[name="state"]').val(conteudo.uf);
        $('input[name="number"]').focus();
        show_hide_card_loading(false);
    } else {
        $('input[name="zip_code"]').val("");
        $("#show-local-origim").html("<small class='text-danger'>CEP não encontrado</small>");
        returnMSG('Formato de CEP inválido.');
        show_hide_card_loading(false);
    }
};

function consultaViaCep(zip_code) {
    //Nova variável "cep" somente com dígitos.
    var zip = zip_code.replace(/\D/g, '');
    //Verifica se campo cep possui valor informado.
    if (zip != "") {
        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;
        show_hide_card_loading();
        //Valida o formato do CEP.
        if (validacep.test(zip)) {
            //Cria um elemento javascript.
            var script = document.createElement('script');
            //Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/' + zip + '/json/?callback=viaCepCallback';
            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);
        } else {
            //cep é inválido.
            //document.getElementById(callback).value = ("");
            returnMSG('Formato de CEP inválido.');
            show_hide_card_loading(false);
        }
    } else {
        document.getElementById(callback).value = ("");
        returnMSG('Formato de CEP inválido.');
        show_hide_card_loading(false);
    }
};

function printQz(){
    var config = qz.configs.create("zebra");
    var data = [
        { type: 'raw', format: 'image', data: 'assets/img/image_sample_bw.png', options: { language: "ESCPOS", dotDensity: 'double' } },
        '\x1B' + '\x40',          // init
        '\x1B' + '\x61' + '\x31', // center align
        'Beverly Hills, CA  90210' + '\x0A',
        '\x0A',                   // line break
        'www.qz.io' + '\x0A',     // text and line break
        '\x0A',                   // line break
        '\x0A',                   // line break
        'May 18, 2016 10:30 AM' + '\x0A',
        '\x0A',                   // line break
        '\x0A',                   // line break
        '\x0A',
        'Transaction # 123456 Register: 3' + '\x0A',
        '\x0A',
        '\x0A',
        '\x0A',
        '\x1B' + '\x61' + '\x30', // left align
        'Baklava (Qty 4)       9.00' + '\x1B' + '\x74' + '\x13' + '\xAA', //print special char symbol after numeric
        '\x0A',
        'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX' + '\x0A',
        '\x1B' + '\x45' + '\x0D', // bold on
        'Here\'s some bold text!',
        '\x0A',
        '\x1B' + '\x45' + '\x0A', // bold off
        '\x1D' + '\x21' + '\x11', // double font size
        'Here\'s large text!',
        '\x0A',
        '\x1D' + '\x21' + '\x00', // standard font size
        '\x1B' + '\x61' + '\x32', // right align
        '\x1B' + '\x21' + '\x30', // em mode on
        'DRINK ME',
        '\x1B' + '\x21' + '\x0A' + '\x1B' + '\x45' + '\x0A', // em mode off
        '\x0A' + '\x0A',
        '\x1B' + '\x61' + '\x30', // left align
        '------------------------------------------' + '\x0A',
        '\x1B' + '\x4D' + '\x31', // small text
        'EAT ME' + '\x0A',
        '\x1B' + '\x4D' + '\x30', // normal text
        '------------------------------------------' + '\x0A',
        'normal text',
        '\x1B' + '\x61' + '\x30', // left align
        '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A',
        '\x1B' + '\x69',          // cut paper (old syntax)
// '\x1D' + '\x56'  + '\x00' // full cut (new syntax)
// '\x1D' + '\x56'  + '\x30' // full cut (new syntax)
// '\x1D' + '\x56'  + '\x01' // partial cut (new syntax)
// '\x1D' + '\x56'  + '\x31' // partial cut (new syntax)
        '\x10' + '\x14' + '\x01' + '\x00' + '\x05',  // Generate Pulse to kick-out cash drawer**
                                                     // **for legacy drawer cable CD-005A.  Research before using.
                                                     // see also http://keyhut.com/popopen4.htm
    ];

    qz.print(config, data).catch(function (e){cosole.error(e)});
}


jQuery.fn.preventDoubleSubmit = function() {
    jQuery(this).submit(function() {
        if (this.beenSubmitted)
            return false;
        else
            this.beenSubmitted = true;
    });
};
