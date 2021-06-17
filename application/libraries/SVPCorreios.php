<?php
class SVPCorreios
{

	private $objeto;
	private $urlSVP = 'https://svp.correios.com.br/api';
	private $user = '19115737802';
	private $password = 'Ab234567';
	private $response = [];
	private $arquivoCookie;

	public function __construct($objeto = null, $arquivoCookie = 'svpCookies')
	{
		$this->objeto = $objeto;
		$this->arquivoCookie = APPPATH . '/cache/'.$arquivoCookie.'.txt';
	}

	public function response()
	{
		return $this->autenticacao();
	}

	private function autenticacao()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://svp.correios.com.br/api/autentica.php?usuario=19115737802&senha=Ab123456",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_COOKIEJAR => $this->arquivoCookie,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
		));
		curl_exec($curl);
		if (curl_getinfo($curl)['http_code'] === 200) {
			return json_decode($this->consulta());
		} else {
			return false;
		}
	}

	private function consulta()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://svp.correios.com.br/api/plp.php/{$this->objeto}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_COOKIEFILE => $this->arquivoCookie,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET"
		));
		$response = curl_exec($curl);
		if (curl_getinfo($curl)['http_code'] === 200) {
			curl_close($curl);
			return $response;
		} else {
			return $response;
		}
	}
}
