<?php

function required($required, $arrSend): bool
{
    $data = (array)$arrSend;
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return false;
        }
    }
    return true;
}

/**
 * ####################
 * ###   BOTTOM     ###
 * ####################
 */

function bottom_view_table_icon_onclick(
    $url,
    $btn_options_class = 'btn btn-square btn-outline btn-secondary',
    $icon = '<i class="ti-help"></i>'
)
{
    $bottom = "<button class='table-action {$btn_options_class}' onclick=$url>";
    $bottom .= $icon;
    $bottom .= "</button>";
    return $bottom;
}

function buttomUnLock($type_bloqued, $id, $loadPage)
{
    if ($type_bloqued == 1) {
        $type = 'unlock';
        $btn_type = "btn-warning";
        $icon = "ti-unlock";
    } else {
        $type = 'lock';
        $btn_type = "btn-primary";
        $icon = "ti-lock";
    }
    return "<button class='table-action btn btn-square btn-outline {$btn_type}' onclick=postData('/lock-and-unlock/{$id}/{$type}')><i class='{$icon}'></i></button>";
}

/** funcão para resumir numero.
 * Exemplo 01 - 1.000 para 1k.
 * Exemplo 02 - 1.100 para 1.1k.
 * Exemplo 03 - 1.500 para 1.5k e etc...
 */
function resumeNumerico($v)
{
    $result = $v;
    $number = mb_strlen(clear($result));
    /** resultado para ate 4 casas */
    if ($number === 4) {
        $numberOne = ($number[0] == 0) ? $number : $number[0];
        $numberTwo = ($number[1] == 0) ? 0 : $number[1];

        $result = "{$numberOne}.{$numberTwo}K";
    }

    return $result;
}

function formatar_num_reduz($n, $prec = 1)
{
    $n = clear($n);
    if ($n < 999) {
        // 0 - 999
        $num = number_format($n, 0, ",", ".");
        $sufixo = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $num = number_format($n / 1000, $prec, ".", ",");
        $sufixo = 'K';
    } else if ($n < 900000000) {
        // 0.9mi-850mi
        $num = number_format($n / 1000000, $prec, ",", ".");
        $sufixo = 'M';
    } else if ($n < 900000000000) {
        // 0.9bi-850bi
        $num = number_format($n / 1000000000, $prec, ",", ".");
        $sufixo = 'B';
    } else {
        // 0.9tri+
        $num = number_format($n / 1000000000000, $prec, ",", ".");
        $sufixo = 'T';
    }
    // Remove zeros desnecessários após o decimal. "1.0" -> "1"; "1.00" -> "1"
    // Não afeta parciais, eg "1.50" -> "1.50"
    if ($prec > 0) {
        $dotzero = '.' . str_repeat('0', $prec);
        $num = str_replace($dotzero, '', $num);
    }

    return $num . $sufixo;
}

function iconeTracking($status)
{
    /**
     * 1 - fa-asterisk
     * 2 - fa fa-calendar-check-o
     * 3 - fa fa-cubes
     * 4 - fa fa-truck
     * 5 - fa fa-check-square
     */

    switch ($status):
        case 1:
            $r = "fa fa-certificate";
            break;
        case 2:
            $r = "fa fa-calendar-check-o";
            break;
        case 3:
            $r = "fa fa-cubes";
            break;
        case 4:
            $r = "fa fa-truck";
            break;
        case 5:
            $r = "fa fa-check-square";
            break;
        default:
            $r = "fa fa-warning";
            break;
    endswitch;

    return $r;
}

function colorTracking($status)
{
    /**
     * 1, 2, 3 e 4 -  info
     * 5 - success
     * 6 - danger
     */

    if($status == 1 || $status == 2 || $status == 3 || $status == 4){
        $r = 'info';
    }elseif($status == 5){
        $r = 'success';
    }else{
        $r = 'danger';
    }

    return $r;
}



/** formata cep */
function formata_cep($cep)
{
    if (!empty($cep)) {
        $formatado = substr($cep, 0, 5) . '-';
        $formatado .= substr($cep, 5, 3);
    } else {
        $formatado = $cep;
    }
    return $formatado;
}

function task_emissoes($id, $id_user, $status_id, $task, $date = null)
{
    $dat = date(DATE_W3C);
    if(!empty($date)){
        $dat = $date;
    }


    $ci = &get_instance();
    $ci->load->model('Audit_model');
    $ci->Audit_model->add('task_emissions',
        [
            'id_emission' => $id,
            'id_user' => $id_user,
            'id_status' => $status_id,
            'task' => $task,
            'created_at' => $dat
        ]
    );
}


function getGeocodeData($address)
{
    $address = urlencode($address);
    $googleMapUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyC3XuyeEEhOo8dKE91PUZpTG0HblRdk6Qw";
    $geocodeResponseData = file_get_contents($googleMapUrl);
    $responseData = json_decode($geocodeResponseData, true);
    if ($responseData['status'] == 'OK') {
        $latitude = isset($responseData['results'][0]['geometry']['location']['lat']) ? $responseData['results'][0]['geometry']['location']['lat'] : "";
        $longitude = isset($responseData['results'][0]['geometry']['location']['lng']) ? $responseData['results'][0]['geometry']['location']['lng'] : "";
        $formattedAddress = isset($responseData['results'][0]['formatted_address']) ? $responseData['results'][0]['formatted_address'] : "";
        if ($latitude && $longitude && $formattedAddress) {
            return [
                'address_formatted' => $formattedAddress,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];
        } else {
            return ['erro' => $responseData];
        }
    } else {
        return ['erro' => $responseData['status']];
    }
}

function selected_current($value_current, $value_received)
{
    if ($value_current == $value_received) {
        return "selected";
    }
    return "";
}

function genereteToken($lenght = 23)
{
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}


function cepFormat($cep)
{
    $formatado = substr($cep, 0, 5) . '-';
    $formatado .= substr($cep, 5, 3);
    return $formatado;
}

function formatDate($date)
{
    if (empty($date)) {
        return $date;
    } else {
        $date = explode('/', $date);
        $date = $date[2] . '-' . $date[1] . '-' . $date[0];
        return $date;
    }
}

function statusEntrega($val, $nameCurt = true)
{
    switch ($val):
        case 1:
            return 'Lançado';
            break;
        case 2:
            return "Coletado";
            break;
        case 3:
            return $nameCurt == false ? "Centro de Distribuição" : "Centro Dist.";
            break;
        case 4:
            return "Saiu para Entrega";
            break;
        case 5:
            return $nameCurt == false ? "Entregue ao Destinatário" : "Entregue";
            break;
        case 6:
            return "Outros";
            break;
        default:
            return "Lançado";
            break;
    endswitch;
}

function statusEntregaColor($val)
{
    switch ($val):
        case 1:
            return "<span class='badge badge-info'>Lançado</span>";
            break;
        case 2:
            return "<span class='badge badge-danger'>Coletado</span>";
            break;
        case 3:
            return "<span class='badge badge-dark'>Centro de Destribuição</span>";
            break;
        case 4:
            return "<span class='badge badge-primary'>Saiu para Entrega</span>";
            break;
        case 5:
            return "<span class='badge badge-success'>Entregue ao Destinatário</span>";
            break;
        case 6:
            return "<span class='badge badge-warning'>Outros</span>";
            break;
        default:
            return "<span class='badge badge-info'>Lançado</span>";
            break;
    endswitch;
}

function nomeFaturamento($val){
    switch ($val):
        case 1:
            return "<span class='badge badge-info'>Não Faturado</span>";
            break;
        case 2:
            return "<span class='badge badge-success'>Faturado</span>";
            break;
        default:
            return "<span class='badge badge-info'>ão Faturado</span>";
            break;
    endswitch;
}

function prazoDeEntrega($date){
    return date('d/m/Y', strtotime('+2 day', strtotime($date)));
}

function valida_variavel_null($var, $va2 = null){
    // $var - primeiro param uso obrigatorio
    // $var2 - segundo param uso opcional, use ele caso queria um valor diferente caso o $var for null ou vazio.
    return (!empty($var)) ? $var:$va2;
}

function modalidade_jadlog($qual_modalidade){
    switch ($qual_modalidade):
        case 0:
            return ["nome" => "Expresso", "codigo" => 0];
            break;
        case 6:
            return ["nome" => "Doc", "codigo" => 6];
            break;
        case 12:
            return ["nome" => "Cargo", "codigo" => 12];
            break;
        case 4:
            return ["nome" => "Rodoviario", "codigo" => 4];
            break;
        case 5:
            return ["nome" => "Econômico", "codigo" => 5];
            break;
        default:
            return ".....";
            break;
    endswitch;
}

function calcularPorcentagem($val, $porcentagem){
    return   $val + ($val / 100 * $porcentagem);
}

function calcularPrazo($prazoReal,  $prazoAcrescentado){
    return ($prazoReal + $prazoAcrescentado);
}
