<?php
defined('BASEPATH') or exit('No direct script access allowed');
function required($required, $arrSend): bool {
	$data = (array)$arrSend;
	foreach ($required as $field) {
		if (empty($data[$field])) {
			return false;
		}
	}
	return true;
}

function getAuthorizationHeader() {
	$headers = null;
	if (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER["Authorization"]);
	} else {
		if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
			$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
		} elseif (function_exists('apache_request_headers')) {
			$requestHeaders = apache_request_headers();
			// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
			$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
			//print_r($requestHeaders);
			if (isset($requestHeaders['Token'])) {
				$headers = trim($requestHeaders['Token']);
			}
		}
	}
	return $headers;
}

/**
 * get access token from header
 * */
function getBearerToken() {
	$headers = getAuthorizationHeader();
	// HEADER: Get the access token from the header
	if ( ! empty($headers)) {
		if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
			return $matches[1];
		}
	}
	return null;
}

function genereteToken($lenght = 23) {
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

function fields_name($value) {
	switch ($value):
		case "name_destination":
			return "Nome do Destinatário";
			break;
		case "zip_code";
			return "CEP";
			break;
		case "weight":
			return "Peso";
			break;
		case "document_destination":
			return "documento destinatário";
			break;
		case "address_number":
			return "Número do endereço";
			break;
		default:
			return $value;
			break;
	endswitch;
}

function task_emissoes($id, $id_user, $status_id, $task, $date = null)
{
	$dat = date(DATE_W3C);
	if(!empty($date)){
		$dat = $date;
	}
	$ci = &get_instance();
	$ci->load->model('MasterModel');
	$ci->MasterModel->insert('task_emissions',
		[
			'id_emission' => $id,
			'id_user' => $id_user,
			'id_status' => $status_id,
			'task' => $task,
			'created_at' => $dat
		]
	);
}

function getGeocodeData($address) {
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
			return false;
		}
	} else {
		echo "ERROR: {$responseData['status']}";
		return false;
	}
}

function clear_string($string) {
	$table = array(
		'/' => '',
		'(' => '',
		')' => '',
		'.' => '',
		' ' => '',
		'-' => ''
	);
	// Traduz os caracteres em $string, baseado no vetor $table
	$string = strtr($string, $table);
	$string = preg_replace('/[,.;:`´^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $string));
	$string = strtolower($string);
	$string = str_replace(" ", "-", $string);
	$string = str_replace("---", "-", $string);
	return trim($string);
}

function criptografaToken($token, $playId){
	$token_primeira_parte = mb_substr($token, 0, 32);
	$token_segunda_parte = mb_substr($token, 32, 32);

	return "{$token_primeira_parte}.{$playId}.{$token_segunda_parte}";
}
