<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ConsultaObjeto extends MY_Controller {

	private $etiqueta;
	private $idColeta;
	private $id;

	public function __construct() {
		parent::__construct();
		$this->load->library('ConverteDadosHtmlEmArray');
	}

	public function index_get() {
		$data_get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRIPPED);

		$this->idColeta = ( ! empty($data_get['id_operacao'])) ? trim(strip_tags($data_get['id_operacao'])) : null;
		$this->etiqueta = ( ! empty($data_get['objeto'])) ? trim(strip_tags($data_get['objeto'])) : null;

		if(empty($this->etiqueta)){
			$findAll = $this->crud->findAll('item_coleta', "id_coleta = '{$this->idColeta}'")->result_array();
			$data = null;
			if(!empty($findAll)){
				$data = $findAll;
			}
			$this->response(['data'=> $data,'status' => 'ok', 'messagem' => 'Lote iniciado...'], 200);
			return null;
		}else {
			if ( ! empty($data_get)) {
				$html_convert = new ConverteDadosHtmlEmArray($this->etiqueta);
				$dataConvert = $html_convert->toConverte();

				$find = $this->crud->find('item_coleta', "objeto = '{$this->etiqueta}' AND id_coleta != '{$this->idColeta}'")->row();

				if ( ! empty($find)) {
					$findAll = $this->crud->findAll('item_coleta', "id_coleta = '{$this->idColeta}'")->result_array();
					$this->response([
						'data' => $findAll,
						'status' => 'ok',
						'messagem' => 'Este objeto ja foi coletado'
					], 400);
					return null;
				}

				if ($dataConvert['status'] != 'erro') {
					$insert = [
							'id_coleta' => $this->idColeta,
							'status_afericao' => 'coletado',
							'data_cadastro' => date(DATE_W3C)
						] + $dataConvert;
					if ($data_retorno_db = $this->crud->insert_data('item_coleta', $insert, 'id_coleta', $this->idColeta)) {
						$this->response(['data' => $data_retorno_db->result_array(), 'status' => 'ok'], 200);
					} else {
						$this->response(['status' => 'erro', 'messagem' => 'Erro ao inserir este objeto'], 400);
					}
				} else {
					$this->response(['status' => 'erro', 'messagem' => $dataConvert['messagem']], 400);
				}
			} else {
				$this->response(['status' => 'erro', 'messagem' => 'preencha os dados corretamente'], 400);
			}
		}
	}
}
