<?php

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

require APPPATH . '/hooks/MY_Painel.php';

class Pendencias extends \MY_Painel {
	private $data = [];

	public function __construct() {
		parent::__construct();
		$this->load->helpers('Helpers');
	}

	public function index() {
		$this->data['menuPendencias'] = '';
		$where = " WHERE status = 'aberto' OR status = 'embarcado'";
		$tipo = $this->uri->segment(2);
		if(!empty($tipo)){
			$where = " WHERE status != 'aberto' AND status != 'embarcado'";
		}

		$query_mysql = "
		SELECT t.id as opID, t.id_motorista, t.data_abertura, t.data_fechamento, t.data_embarcacao, t.status, m.*
		FROM coletas t
		LEFT JOIN motorists m ON m.id = t.id_motorista
		{$where}
		ORDER BY opID ASC
		";
		$this->data['tipo'] = $tipo;
		$this->data['coletas'] = $this->crud->query($query_mysql)->result();
		$this->load->view('painel-cliente/pendencias', $this->data);
	}

	public function baixa($id) {
		$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if(!empty($post) && $post['action'] == 'action'){
			$find = $this->crud->find('item_coleta', "id = '{$id}'")->row();
			if(!empty($find)){
				if($this->crud->update('item_coleta', [
					'status_afericao' => 'entregue',
					'origem_baixa' => 'desktop',
					'data_baixa' => date(DATE_W3C),
					'responsavel_baixa' => $this->idLogado
				], 'id', $id)){
					$this->message['success'] = "Ação registrada com sucesso!";
				}else {
					$this->message['erro'] = "Erro ao baixa registro!";
				}
				echo json_encode($this->message);
				return null;
			}else{
				$this->message['erro'] = "registro não encontrado ou nao foi coletado pelo sistema.";
				echo json_encode($this->message);
				return null;
			}
		}
	}

	public function finalizaLote($id) {
		$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if(!empty($post) && $post['action'] == 'action'){
			$find = $this->crud->find('coletas', "id = '{$id}' AND status = 'embarcado'")->row();

			if(!empty($find)){

				$findCount = $this->crud->query("SELECT * FROM item_coleta WHERE id_coleta = '{$find->id}' AND status_afericao != 'entregue'")->num_rows();

				if($findCount > 0){
					$this->message['erro'] = "Lote não pode ser finalizado, ainda contem item em aberto";
					echo json_encode($this->message);
					return null;
				}

				if($this->crud->update('coletas', [
					'status' => 'finalizado',
					'responsavel_baixa' => $this->idLogado,
					'data_fechamento' => date(DATE_W3C)
				], 'id', $id)){
					$this->message['success'] = "Ação registrada com sucesso!";
				}else {
					$this->message['erro'] = "Erro ao baixa registro!";
				}
				echo json_encode($this->message);
				return null;
			}else{
				$this->message['erro'] = "lote não encontrado, não embarcado ou contem registro";
				echo json_encode($this->message);
				return null;
			}
		}
	}
}
