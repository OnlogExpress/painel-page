<?php

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

require APPPATH . '/hooks/MY_Painel.php';

class PortalCliente extends \MY_Painel {
	private $data = [];
	private $requiredField = [
		'name_motorist',
		'car_board',
		'password'
	];

	public function __construct() {
		parent::__construct();
		$this->load->helpers('Helpers');
		$this->load->library('Encryption');
		$this->encryption->initialize(array('driver' => 'mcrypt'));

		if($this->nivel == 1){
			redirect(base_url());
		}
	}

	public function index() {
		$this->data['menuHome'] = '';
		$where = " WHERE status = 'aberto' OR status = 'embarcado'";
		$tipo = $this->uri->segment(2);

		$page = 'index';

		if(!empty($tipo)){
			$where = " WHERE status != 'aberto' AND status != 'embarcado'";
			$page = 'lista';
		}

		$query_mysql = "
		SELECT t.id as opID, t.id_motorista, t.data_abertura, t.data_fechamento, t.data_embarcacao, t.status, m.*
		FROM coletas t
		LEFT JOIN motorists m ON m.id = t.id_motorista
		{$where} ORDER BY opID DESC
		";
		$this->data['tipo'] = $tipo;
		$this->data['coletas'] = $this->crud->query($query_mysql)->result();
		$this->load->view("painel-cliente/{$page}", $this->data);
	}

	/**
	 * PAGINA DE MOTORISTA
	 * LISTA, ADICIONAR, EDITA
	 */

	public function motoristas() {
		$this->data['menuMotorista'] = '';
		$this->data['dados_datatable'] = $this->crud->findAll('motorists', "situation != 2");
		$this->load->view('painel-cliente/lista_motorista', $this->data);
	}

	public function motoristasAdd() {

		$this->data['menuMotorista'] = '';
		$dataPost = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if ( ! empty($dataPost)) {
			$this->saveMotorist($dataPost, 'create');
		} else {
			$this->load->view('painel-cliente/form_mostorista', $this->data);
		}
	}

	public function motoristasEdit($id = null) {

		$this->data['menuMotorista'] = '';
		$dataPost = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if ( ! empty($dataPost)) {
			$this->saveMotorist($dataPost, 'update', $id);
		} else {
			if ($id == null) {
				redirect(base_url('motoristas'));
				exit();
			}
			$result = $this->crud->find('motorists', "id = '{$id}'")->row();

			if ( ! $result) {
				$this->session->set_flashdata('error', 'Esse registro foi excluido ou removido');
				redirect(base_url('motoristas'));
			} else {
				$this->data['dados_edit'] = $result;
			}
			$this->load->view('painel-cliente/form_mostorista', $this->data);
		}
	}


	private function saveMotorist($data = [], $request = 'create', $id = null) {
		if ( ! is_array($data)) {
			$this->message['erro'] = "Ops, parametros não passado corretamente!";
			echo json_encode($this->message);
			return null;
		}

		if ( ! required($this->requiredField, $data)) {
			$msg = implode(', ', $this->requiredField);
			$this->message['erro'] = "Os campos [{$msg}] são obrigatórios";
			echo json_encode($this->message);
			return null;
		}

		$board = ( ! empty($data['car_board'])) ? $data['car_board'] : null;
		$password = ( ! empty($data['password'])) ? $data['password'] : null;
		$sArray = null;

		if ($request == 'update') {

			if(!empty($password)){
				$sArray = [ 'password' => $this->encryption->encrypt(trim($password)) ];
			}

			$datai = [
				'name_motorist' => $data['name_motorist'],
				'car_board' => $data['car_board'],
				'type_automobile' => $data['type_automobile'],
				'description_automobile' => $data['description_automobile']
			]+$sArray;

			$findBoard = $this->crud->find('motorists', "id != '{$id}' AND car_board = '{$board}'")->row();
			if ( ! empty($findBoard)) {
				$this->message['erro'] = "A placa do veiculo informado ja tem um cadastro no sistema";
				echo json_encode($this->message);
				return null;
			}

			if ($this->crud->update('motorists', $datai, 'id', $id) == true) {
				$this->message['success'] = "Dados atualizado com sucesso!";
			} else {
				$this->message['erro'] = "Erro ao atualizar, verifique os dados";
			}
			echo json_encode($this->message);
			return null;

		}

		/** create cliente */
		if ($request == 'create') {

			$findBoard = $this->crud->find('motorists', "car_board = '{$board}'")->row();
			if ( ! empty($findBoard)) {
				$this->message['erro'] = "A placa do veiculo informado ja tem um cadastro no sistema";
				echo json_encode($this->message);
				return null;
			}
			$datai = [
				'name_motorist' => $data['name_motorist'],
				'car_board' => $data['car_board'],
				'password' => $this->encryption->encrypt(trim($password)),
				'type_automobile' => $data['type_automobile'],
				'situation' => 1,
				'description_automobile' => $data['description_automobile'],
				'created_at' => date(DATE_W3C)
			];

			if ($this->crud->insert('motorists', $datai) == true) {
				$this->message['success'] = "Dados cadastrado com sucesso!";
			} else {
				$this->message['erro'] = "Erro ao cadastrar, verifique os dados";
			}
			echo json_encode($this->message);
			return null;
		}
	}

	public function deleteMotorista($id) {
		if (empty($id)) {
			$this->message['erro'] = "Erro ao excluir, verifique os dados";
			echo json_encode($this->message);
			return null;
		}
		if ($this->crud->update('motorists', ['situation' => 2], 'id', $id) == true) {
			$this->message['success'] = "Registro excluido com sucesso";
		} else {
			$this->message['erro'] = "Erro ao excluir, verifique os dados";
		}
		echo json_encode($this->message);
	}

	public function operacoes($id = null) {

		if($this->nivel == 1){
			redirect(base_url());
			return null;
		}

		$this->data['menuHome'] = '';
		if ($id == null) {
			redirect(base_url());
			exit();
		}
		$result = $this->crud->find('coletas', "id = '{$id}'")->row();
		if ( ! $result) {
			$this->session->set_flashdata('error', 'Esse registro foi excluido ou removido');
			redirect(base_url());
		} else {
			$this->data['operacao'] = $result;
		}
		$this->load->view('painel-cliente/operacoes_acao', $this->data);
	}

	public function mudaStatusOperacao(){
		$data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);

		if(!empty($data)){
			$requiredStatus = ['liberar', 'cancelar', 'finalizar'];
			$id = ( ! empty($data['id'])) ? clear($data['id']) : null;
			$status = ( ! empty($data['status'])) ? clear($data['status']) : null;
			if(in_array($status, $requiredStatus)) {
				$stratus = null;
				switch ($status){
					case 'liberar':
						$stratus = 'aberto';
						break;
					case 'finalizar':
						$stratus = 'finalizado';
						break;
					case 'cancelar':
						$stratus = 'cancelado';
						break;
				}

				if ($this->crud->update('coletas', ['status' => $stratus], 'id', $id) == true) {
					$this->message['success'] = "registro {$status} com sucesso";
				} else {
					$this->message['erro'] = "Erro ao {$status}, verifique os dados";
				}
			}else{
				$this->message['erro'] = "Erro ao {$status}, verifique os dados";
			}
		}else{
			$this->message['erro'] = "Dados não informado corretamente";
		}
		echo json_encode($this->message);
	}

}
