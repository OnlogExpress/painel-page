<?php

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

class MY_Painel extends \CI_Controller {

    public $key_codigo = 0;
    public $idLogado;
    public $empresaid;
    public $nameEmpresaLogado;
    public $nivel = null;
    public $mobile;
    public $pastaMobile;
    public $pasta;
    public $nivelfrete;
    public $track;
    public $freight_cost;
    public $token;
    public $dataCompany;
    public $session_action;

 	public $response;

    function __construct() {
        parent::__construct();

        /*
          |----------------------------------
          | LOAD LIBRARY PADRÕES
          |----------------------------------
         */
		$this->load->model('MasterModel', 'crud');
        $this->load->library('Encryption');
        $this->encryption->initialize(array('driver' => 'mcrypt'));

        /*
          |-------------------------------------
          | VERIFICA SE A SESSION E VERDADEIRA
          |-------------------------------------
         */
        if (( ! session_id()) || ( ! $this->session->userdata('logado'))) {
            $this->session->set_flashdata('error', 'Você precisa esta logado para continuar.');
            redirect(base_url('login'));
        }

        /*
          |----------------------------------
          | LOAD VARIAVIES DO SISTEMA
          |----------------------------------
         */
        $this->idLogado = $this->session->userdata('id'); // usuario logado
        $this->nivel = $this->session->userdata('nivel');

        /*$loadConfigCliente = $this->getData->getID('company', array('id' => $this->empresaid));
        $this->dataCompany = $loadConfigCliente;
        $this->nameEmpresaLogado = $loadConfigCliente->company_fancy_name;
        $this->freight_cost = $loadConfigCliente->freight_cost;
        $this->token = $loadConfigCliente->token_api;

        $this->nivelfrete = $loadConfigCliente->table_prace_id; //aciona a tabela de frete do cliente logado*/

		$this->load->library('ResponseJson');
		$this->response = new ResponseJson();
    }

    /** consumi a api para cadastra e consulta valor e prazo de frete */
    public function consultaPostApi($data, $funcao, $method = 'GET') {

        $authorization = "Token: Bearer {$this->token}";
        if ( ! empty($data)) {
            $ch = curl_init($this->config->item('api_url') . '/' . $funcao); // inicia a consulta

            if ($method == 'PUT') {
                curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization, 'Content-Type: application/json')); // HEADER DA REQUISIÇÃO - TOKEN
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            } else {
                if ($method == 'POST') {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization)); // HEADER DA REQUISIÇÃO - TOKEN
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch); // execulta a requisição
            if ($output === false) {
                return curl_error($ch);
            }
            curl_close($ch);
            return json_decode($output);
        }
    }

    public function responseJSON($data){
        echo json_encode($data);
        return null;
    }

}
