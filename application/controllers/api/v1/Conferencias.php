<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Conferencias extends MY_Controller {

	private $id;
	private $idMotorista;
	private $acao;
	private $status = 'aberto';

	private $etiqueta;
	private $idColeta;

	public function __construct() {
		parent::__construct();
		$this->load->library('ConverteDadosHtmlEmArray');
	}

	public function index_get() {

		$data_get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRIPPED);
		$this->acao = ( ! empty($data_get['acao'])) ? trim(strip_tags($data_get['acao'])) : null;
		$this->idColeta = ( ! empty($data_get['id_operacao'])) ? trim(strip_tags($data_get['id_operacao'])) : null;
		$this->etiqueta = ( ! empty($data_get['objeto'])) ? trim(strip_tags($data_get['objeto'])) : null;
		if (empty($this->acao)) {

			$query_mysql = "
			SELECT t.id as opID, t.id_motorista, t.data_abertura, t.status, m.id as idM, m.name_motorist
			FROM coletas t
			LEFT JOIN motorists m ON m.id = t.id_motorista
			WHERE status = 'embarcado'
			";
			$r = [];
			$findAll = $this->crud->query($query_mysql)->result();
			if ( ! empty($findAll)) {
				foreach ($findAll as $ope) {
					$total = $this->crud->query("SELECT * FROM item_coleta WHERE id_coleta = '{$ope->opID}'")->num_rows();
					$r[] = [
						'opID' => $ope->opID,
						'id_motorista' => $ope->id_motorista,
						'data_abertura' => date('d.m.Y H:i:s', strtotime($ope->data_abertura)),
						'status' => $ope->status,
						'idM' => $ope->idM,
						'name_motorist' => $ope->name_motorist,
						'total_item' => $total
					];
				}
				$this->response(['data' => $r, 'status' => 'ok'], 200);
			} else {
				$this->response(['data' => null, 'status' => 'ok'], 200);
			}
		} else {
			if ($this->acao == 'conferencia') {

				$find = $this->crud->find('item_coleta', "objeto = '{$this->etiqueta}' AND id_coleta = '{$this->idColeta}'")->row();
				if ( ! empty($find)) {

					$findC = $this->crud->find('item_coleta', "objeto = '{$this->etiqueta}' AND id_coleta = '{$this->idColeta}' AND status_afericao = 'entregue'")->row();
					if (empty($findC)) {
						if ($this->crud->updateLite('item_coleta', [
								'status_afericao' => 'entregue',
								'origem_baixa' => 'mobile',
								'data_baixa' => date(DATE_W3C),
								'responsavel_baixa' => 0
							], 'objeto',
								$this->etiqueta) == true) {
							$this->response([
								'data' => $this->obterRegistro($this->idColeta),
								'status' => 'ok',
								'messagem' => 'Objeto conferido com sucesso'
							], 200);
							return null;
						} else {
							$this->response(['status' => 'erro', 'messagem' => 'Erro na conferencia tente novamente.'],
								400);
							return null;
						}
					} else {
						$this->response([
							'data' => $this->obterRegistro($this->idColeta),
							'status' => 'erro',
							'messagem' => 'Esse objeto ja foi conferido'
						], 400);
						return null;
					}
				} else {
					$find = $this->crud->find('item_coleta', "objeto = '{$this->etiqueta}' AND id_coleta != '{$this->idColeta}'")->row();
					if ( ! empty($find)) {
						$this->response([
							'data' => $this->obterRegistro($this->idColeta),
							'status' => 'erro',
							'messagem' => "Esse objeto pertence ao lote: {$find->id_coleta}"
						], 400);
						return null;
					} else {
						$this->response([
							'data' => $this->obterRegistro($this->idColeta),
							'status' => 'erro',
							'messagem' => 'Objeto não encontrado'
						], 400);
						return null;
					}
				}
			} else {
				if ($this->acao == 'obter') {
					$this->response([
						'data' => $this->obterRegistro($this->idColeta),
						'status' => 'ok',
					], 200);
				} else {
					$this->response(['status' => 'erro', 'messagem' => 'Erro na conferencia tente novamente.'], 400);
					return null;
				}
			}
		}
	}


	private function obterRegistro($idColeta) {
		$findAll = $this->crud->findAll('item_coleta',
			"id_coleta = '{$idColeta}' AND status_afericao = 'coletado'")->result_array();
		$data = null;
		if ( ! empty($findAll)) {
			$data = $findAll;
		}
		return $data;
	}

}
