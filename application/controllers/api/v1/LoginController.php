<?php
/**
 * LoginMotorist API
 *
 *
 * @package  LoginMotorist
 * @author   Edson Costa
 * @version  v0.3
 * @access   public
 * @see      http://www.skypainel.com.br/
 */

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

class LoginController extends MY_Controller {

	private $requiredField = ['car_board', 'password_access'];

	public $fieldsTerms = [
		"id",
		"name_motorist",
		"car_board",
		"description_automobile",
		"type_automobile",
		"nivel",
		"situation",
		"token",
		"created_at"
	];

	private $columns;
	private $table = "motorists";

	public function __construct() {
		parent::__construct();
		$this->load->library('Encryption');
		$this->encryption->initialize(array('driver' => 'mcrypt'));

		$this->columns = implode(', ', $this->fieldsTerms);
	}

	/** CONSULTA UM REGISTRO OU TODOS */
	public function index_get($id = null) {
		$data = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRIPPED);

		// verifica se foi passado algum dados
		if(empty($data)){
			$this->response(['status' => 'erro',  'messagem' => 'Dados não informado corretamente'], 401);
			return null;
		}else{

			$placa_veiculo = (!empty($data['placa'])) ? trim(strip_tags($data['placa'])):null;
			$senha_motorista = (!empty($data['senha'])) ? trim(strip_tags($data['senha'])):null;

			//valida campos vazios
			if(empty($placa_veiculo) || empty($senha_motorista)){
				$this->response(['status' => 'erro',  'messagem' => 'Os campos placa/senha são obrigatório'], 401);
				return null;
			}
			try {
				//verifica se os dados passado já esta cadastrado
				$find = $this->crud->find('motorists', "car_board = '{$placa_veiculo}'")->row_array();
				//dados informado não cadastrado ainda no servidor.
				if(!$find){
					$this->response(['status' => 'erro',  'messagem' => 'Dados não encontrado'], 401);
					return null;
				}else{
					$senha_motorista_armazenada = $this->encryption->decrypt($find['password']);
					if($senha_motorista == $senha_motorista_armazenada){
						$token = genereteToken(64);
						$dataUpdate = ['token' => criptografaToken($token, md5($find['car_board'].$find['token']))];
						$data = $this->crud->update('motorists', $dataUpdate, 'id', $find['id'], $this->columns);
						$this->response(['status' => 'ok', 'data' => $data], 200);
					}else{
						$this->response(['status' => 'erro', 'messagem' => 'Ops, login e/ou senha incorretos.'], 401);
					}
					return null;
				}
			}catch (Exception $e){
				$this->response( $e, 500);
			}
		}
	}

	/** CRIA UM REGISTRO */
	public function index_post() {

		$data = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRIPPED);

		// verifica se foi passado algum dados
		if(empty($data)){
			$this->response(['status' => 'erro',  'messagem' => 'Dados não informado corretamente'], 401);
			return null;
		}else{

			$placa_veiculo = (!empty($data['placa'])) ? trim(strip_tags($data['placa'])):null;
			$senha_motorista = (!empty($data['senha'])) ? trim(strip_tags($data['senha'])):null;

			//valida campos vazios
			if(empty($placa_veiculo) || empty($senha_motorista)){
				$this->response(['status' => 'erro',  'messagem' => 'Os campos placa/senha são obrigatório'], 401);
				return null;
			}
			try {
				//verifica se os dados passado já esta cadastrado
				$find = $this->crud->find('motorists', "car_board = '{$placa_veiculo}'")->row_array();
				//dados informado não cadastrado ainda no servidor.
				if(!$find){
					$this->response(['status' => 'erro',  'messagem' => 'Dados não encontrado'], 401);
					return null;
				}else{
					$senha_motorista_armazenada = $this->encryption->decrypt($find['password']);
					if($senha_motorista == $senha_motorista_armazenada){
						$token = genereteToken(64);
						$dataUpdate = ['token' => criptografaToken($token, md5($find['car_board'].$find['token']))];
						$data = $this->crud->update('motorists', $dataUpdate, 'id', $find['id'], $this->columns);
						$this->response(['status' => 'ok', 'data' => $data], 200);
					}else{
						$this->response(['status' => 'erro', 'messagem' => 'Ops, login e/ou senha incorretos.'], 401);
					}
					return null;
				}
			}catch (Exception $e){
				$this->response( $e, 500);
			}
		}
	}

	/** ATUALIZA UM REGISTRO VIA PUT */
	public function index_put($id = null) {
		$this->response([
			STATUS_RETORNO_API => "Erro",
			MSG_ERRO => 'Method não suportado PUT',
			LABEL_CODIGO_ERRO => 99
		], 400);
		return null;
	}

	/** DELETA UM REGISTRO (ID)*/
	public function index_delete($id = null) {
		$this->response([
			STATUS_RETORNO_API => "Erro",
			MSG_ERRO => 'Method não suportado DELETE',
			LABEL_CODIGO_ERRO => 99
		], 400);
		return null;
	}
}
