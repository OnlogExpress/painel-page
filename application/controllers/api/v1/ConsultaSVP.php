<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ConsultaSVP extends MY_Controller {

	private $etiqueta;
	private $idColeta;
	private $id;

	public function __construct() {
		parent::__construct();
		$this->load->library('SVPCorreios');
		$this->load->helper('cookie');
	}

	public function index_get() {
		$get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRIPPED);
		$this->idColeta = ( ! empty($get['id_operacao'])) ? trim(strip_tags($get['id_operacao'])) : null;
		$this->etiqueta = ( ! empty($get['objeto'])) ? trim(strip_tags($get['objeto'])) : null;
		$tamanho = strlen($this->etiqueta);
		$erro = false;

		$this->logs($this->idColeta, $this->etiqueta); // grava logs
		$data = null;

		$findAll = $this->crud->findAll('item_coleta', "id_coleta = '{$this->idColeta}'")->result_array();
		if ( ! empty($findAll)) {
			foreach ($findAll as $item) {
				$data[] = [
					'id' => str_pad($item['id'], 2, "0", STR_PAD_LEFT),
					'data_cadastro' => date('d.m.Y H:i', strtotime($item['data_cadastro'])),
					'objeto' => $item['objeto']
				];
			}
		}else{
			$data = [];
		}
		if (empty($this->etiqueta)) {
			$this->response(['data' => $data, 'status' => 'ok', 'messagem' => 'Lote iniciado...'], 200);
			return null;
		}

		if($tamanho < 13 || $tamanho > 13){
			$this->response(['data' => $data,'status' => 'erro', 'messagem' => 'Etiqueta não e valida.'],400);
			return null;
		}else{
			$sigla_br = strtoupper(mb_substr($this->etiqueta, 11, 2));
			if($sigla_br != "BR"){
				$this->response(['data' => $data,'status' => 'erro', 'messagem' => 'Etiqueta não e valida.'],400);
				return null;
			}
		}


		//consulta se o objeto ja existe na tabela [TMP]
		$findTemp = $this->crud->find('item_coleta_tmp', "objeto = '{$this->etiqueta}'")->row();
		if ( ! empty($findTemp)) {
			$this->insert($this->etiqueta, $this->idColeta);
		} else {
			$find = $this->crud->find('item_coleta', "objeto = '{$this->etiqueta}'")->row();
			$dataDB = $this->crud->findAll('item_coleta', "id_coleta = '{$this->idColeta}'")->result_array();
			if ( ! empty($find)) {
				$this->response(['data' => $data, 'status' => 'erro', 'messagem' => 'Este objeto ja foi coletado'],400);
				return null;
			} else {
				if ( ! empty($get)) {
					$obj_postais = [
						'id_coleta' => $this->idColeta,
						'objeto' => $this->etiqueta,
						'status_afericao' => 'coletado',
						'data_cadastro' => date(DATE_W3C)
					];
					if($this->crud->insert('item_coleta', $obj_postais) == true){
						$dataDB = $this->crud->findAll('item_coleta', "id_coleta = '{$this->idColeta}'")->result_array();
						$dat = [];
						if ( ! empty($dataDB)) {
							foreach ($dataDB as $item) {
								$dat[] = [
									'id' => str_pad($item['id'], 2, "0", STR_PAD_LEFT),
									'data_cadastro' => date('d.m.Y H:i', strtotime($item['data_cadastro'])),
									'objeto' => $item['objeto']
								];
							}
						}

						$this->response(['data' => $dat, 'status' => 'ok'], 200);
						return null;
					}else {
						$this->response(['status' => 'erro', 'messagem' => 'Erro ao inserir este objeto'], 400);
						return null;
					}
				} else {
					$this->response(['status' => 'erro', 'messagem' => 'preencha os dados corretamente'], 400);
					return null;
				}
			}
		}
	}

	private function insert($etiqueta, $coleta) {
		if ($this->crud->insert_table_tmp($etiqueta)) {
			$this->crud->update('item_coleta', ["id_coleta " => $coleta, 'data_cadastro' => date(DATE_W3C)], 'objeto', $etiqueta);
			$dataDB = $this->crud->findAll('item_coleta', "id_coleta = '{$coleta}'")->result_array();
			// deleta os dados da tabela temporaria
			$this->crud->delete('item_coleta_tmp', "objeto", $etiqueta);
			$this->response(['data' => $dataDB, 'status' => 'ok'], 200);
			return null;
		} else {
			$this->response(['status' => 'erro', 'messagem' => 'Erro ao inserir este objeto'], 400);
			return null;
		}
	}

	public function index_post() {
		$html_convert = new SVPCorreios();
		$dataConvert = $html_convert->response();
	}

	public function logs($id, $obj) {
		$this->crud->insert('logs', ['id_coleta' => $id, 'objeto' => $obj, 'data' => date(DATE_W3C)]);
	}
}
