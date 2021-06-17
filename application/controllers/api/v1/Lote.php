<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lote extends MY_Controller {

	private $id;
	private $idMotorista;
	private $acao;
	private $status = 'aberto';

	public function __construct() {
		parent::__construct();
		$this->load->library('ConverteDadosHtmlEmArray');
	}

	public function index_get() {

		$data_get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRIPPED);
		$this->acao = ( ! empty($data_get['acao'])) ? trim(strip_tags($data_get['acao'])) : null;
		if ( ! empty($data_get)) {
			/** adicionar uma operação */
			if($this->acao == 'adicionar'){
				$this->idMotorista = ( ! empty($data_get['id_motorista'])) ? trim(strip_tags($data_get['id_motorista'])) : null;
				/** @var verifica se ja existe uma operação em aberto $find */
				$find = $this->crud->find('coletas', "id_motorista = '{$this->idMotorista}' AND status = '{$this->status}'")->row();
				if(!empty($find)){
					$this->response(
						[
							'status' => 'ok',
							'data' => $find,
							'messagem' => 'Esse lote já foi iniciado anteriormente'
						], 200);
					return null;
				}else{
					$insert = [
						'id_motorista' => $this->idMotorista,
						'status' => $this->status,
						'data_abertura'=> date(DATE_W3C)
					];
					if($data_retorno_db = $this->crud->insert_data('coletas', $insert, 'id', null)){
						$this->response(['data' => $data_retorno_db->row_array(), 'status' => 'ok'], 200);
						return null;
					}else{
						$this->response(['status' => 'erro', 'messagem' => 'Erro ao inserir este objeto'], 400);
						return null;
					}
				}
				return null;
			/** finaliza uma operacao */
			}else if($this->acao == 'finalizar'){
				$this->id = ( ! empty($data_get['id_operacao'])) ? trim($data_get['id_operacao']) : null;

				$findAllColetas = $this->crud->findAll('item_coleta', "id_coleta = '{$this->id}'")->result();
				if(empty($findAllColetas)){
					$this->response(['status' => 'ok', 'messagem' => 'Essa operação não pode ser finalizada, pois ainda nao contem nenhum registro.'], 200);
					return null;
				}else {
					/** verifica se a operação ja foi finalizada */
					$findColeta = $this->crud->find('coletas', "id = '{$this->id}'  AND status = '{$this->status}'")->row();
					if ( ! empty($findColeta)) {
						if ($this->crud->updateLite('coletas',
								[
									'status' => 'embarcado',
									'data_embarcacao' => date(DATE_W3C)
								], 'id', $this->id) == true) {
							$this->crud->updateLite('item_coleta', ['status_afericao' => 'coletado'], 'id_coleta', $this->id);
							$this->response(['status' => 'ok', 'messagem' => 'Operação finalizada com sucesso'], 200);
						} else {
							$this->response(['status' => 'erro', 'messagem' => 'Erro ao finalizar essa operação'], 400);
						}
						return null;
					} else {
						$this->response([
							'status' => 'erro',
							'messagem' => 'Essa operação não existe ou ja foi finalizada.'
						], 400);
						return null;
					}
					$this->response(['status' => 'ok', 'messagem' => $findColeta], 200);
					return null;
				}
			}else{
				$this->response(['status' => 'erro', 'messagem' => 'Ação nao definida'], 400);
				return null;
			}
		} else {
			$this->response(['status' => 'erro', 'messagem' => 'preencha os dados corretamente'], 400);
			return null;
		}
	}


	private function obterRegistro(){

	}

}
