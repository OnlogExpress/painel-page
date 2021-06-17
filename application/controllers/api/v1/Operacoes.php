<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Operacoes extends MY_Controller {

	private $etiqueta;
	private $idColeta;
	private $id;

	public function __construct() {
		parent::__construct();
	}

	public function index_get() {
		$get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRIPPED);
		if ( ! empty($get)) {
			$this->id = ( ! empty($get['id_motorista'])) ? trim(strip_tags($get['id_motorista'])) : null;
			$dataColeta = $this->crud->findAll('coletas', "id_motorista = '{$this->id}' ORDER BY id DESC LIMIT 10")->result_array();

			if(!empty($dataColeta)){
				foreach ($dataColeta as $coleta){
					$itens = $this->crud->findAll('item_coleta', "id_coleta = '{$coleta['id']}'")->num_rows();
					$response_data[] = [
						'id' => $coleta['id'],
						'aberto' => $this->dtFormat($coleta['data_abertura']),
						'embarcado' => $this->dtFormat($coleta['data_embarcacao']),
						'fechamento' => $this->dtFormat($coleta['data_fechamento']),
						'status' => $coleta['status'],
						'total_item' => $itens
					];
				}
			}else{
				$response_data = [];
			}
			$this->response(['data' => $response_data],200);
		} else {
			$this->response(['status' => 'erro', 'messagem' => 'preencha os dados corretamente'], 400);
			return null;
		}
	}

	public function dtFormat($date){
		if(empty($date)){
			return null;
		}else {
			return date('d.m.Y H:i', strtotime($date));
		}
	}
}
