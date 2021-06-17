<?php

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

require APPPATH . '/hooks/MY_Painel.php';

class PortalAgf extends \MY_Painel
{
	private $data = [];
	private $requiredField = [
		'name_motorist',
		'car_board',
		'password'
	];

	public function __construct()
	{
		parent::__construct();
		$this->load->helpers('Helpers');
		$this->load->library('Encryption');
		$this->encryption->initialize(array('driver' => 'mcrypt'));

		if ($this->nivel == 2) {
			redirect(base_url());
		}
	}

	public function index()
	{
		$this->data['menuHome'] = '';
		$tipo = (!empty($this->uri->segment(2))) ? $this->uri->segment(2):'';

		$pagina = filter_input(INPUT_GET, 'pagina',FILTER_SANITIZE_STRIPPED);
		$pagina_inicial = (!empty($pagina)) ? $pagina:1;
		$quantidade_por_pagina = 25;
		$inicio = ($pagina_inicial * $quantidade_por_pagina) - $quantidade_por_pagina;

		$this->data['totalDados'] = $this->crud->coletas('', 0, 0)->num_rows();
		$this->data['registro_por_pagina'] = $quantidade_por_pagina;
		$this->data['pagina_inicial'] = $pagina_inicial;

		$this->data['page'] = base_url('portal-agf');
		$this->data['tipo'] = $tipo;

		$this->data['coletas'] = $this->crud->coletas('', $inicio, $quantidade_por_pagina)->result();
		$this->load->view('painel-agf/lista-coletas', $this->data);
	}

	public function teste()
	{

	}

	public function carregaDadoLote($id)
	{
		$results = $this->crud->findAll('item_coleta', "id_coleta = '{$id}'")->result();

		$result = null;
		if ($results) {
			$result = $results;
		}

		$this->data['resultDados'] = $result;
		$dataView = $this->load->view('painel-agf/lista-item-coletas', $this->data, true);

		return $this->response->json(['data' => $dataView], 200);
	}

	/**
	 * PAGINA DE MOTORISTA
	 * LISTA, ADICIONAR, EDITA
	 */

	public function motoristas()
	{
		$this->data['dados_datatable'] = $this->crud->findAll('motorists', "situation != 2");
		$this->load->view('painel-cliente/lista_motorista', $this->data);
	}

	public function motoristasAdd()
	{
		$dataPost = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if (!empty($dataPost)) {
			$this->saveMotorist($dataPost, 'create');
		} else {
			$this->load->view('painel-cliente/form_mostorista', $this->data);
		}
	}

	public function motoristasEdit($id = null)
	{
		$dataPost = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if (!empty($dataPost)) {
			$this->saveMotorist($dataPost, 'update', $id);
		} else {
			if ($id == null) {
				redirect(base_url('motoristas'));
				exit();
			}
			$result = $this->crud->find('motorists', "id = '{$id}'")->row();

			if (!$result) {
				$this->session->set_flashdata('error', 'Esse registro foi excluido ou removido');
				redirect(base_url('motoristas'));
			} else {
				$this->data['dados_edit'] = $result;
			}
			$this->load->view('painel-cliente/form_mostorista', $this->data);
		}
	}

	private function saveMotorist($data = [], $request = 'create', $id = null)
	{
		if (!is_array($data)) {
			$this->message['erro'] = "Ops, parametros não passado corretamente!";
			echo json_encode($this->message);
			return null;
		}

		if (!required($this->requiredField, $data)) {
			$msg = implode(', ', $this->requiredField);
			$this->message['erro'] = "Os campos [{$msg}] são obrigatórios";
			echo json_encode($this->message);
			return null;
		}

		$board = (!empty($data['car_board'])) ? clear($data['car_board']) : null;
		$password = (!empty($data['password'])) ? clear($data['password']) : null;
		$sArray = null;

		if ($request == 'update') {

			if (!empty($password)) {
				$sArray = ['password' => $this->encryption->encrypt(trim($password))];
			}

			$datai = [
					'name_motorist' => $data['name_motorist'],
					'car_board' => $data['car_board'],
					'type_automobile' => $data['type_automobile'],
					'description_automobile' => $data['description_automobile']
				] + $sArray;

			$findBoard = $this->crud->find('motorists', "id != '{$id}' AND car_board = '{$board}'")->row();
			if (!empty($findBoard)) {
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
			if (!empty($findBoard)) {
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

	public function deleteMotorista($id)
	{
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
}
