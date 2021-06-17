<?php

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso n???o permitido</h1>');

require APPPATH . '/hooks/MY_Painel.php';

class ConferenciaWeb extends \MY_Painel
{
	private $data = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->helpers('Helpers');
	}

	public function index()
	{
		$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if (!empty($post)) {
			$this->confereciaWeb($post);
		} else {
			$this->load->view('painel-agf/conferencia-web', $this->data);
		}
	}

	private function confereciaWeb($post)
	{

		if (empty($post)) {
			$this->message['erro'] = "ops, algo deu de errado";
			echo json_encode($this->message);
			return null;
		} else {
			$objeto = (!empty($post['objeto'])) ? trim($post['objeto']) : null;
			$lote = (!empty($post['lote'])) ? trim($post['lote']) : null;

			//$find = $this->crud->find('item_coleta', "objeto = '{$objeto}' AND id_coleta = '{$lote}'")->row();
			$find = $this->crud->find('item_coleta', "objeto = '{$objeto}'")->row();
			if (!empty($find)) {
				//$findObjeto = $this->crud->find('item_coleta', "objeto = '{$objeto}'  AND id_coleta = '{$lote}' AND status_afericao = 'entregue'")->row();
				$findObjeto = $this->crud->find('item_coleta',
					"objeto = '{$objeto}'  AND status_afericao = 'entregue'")->row();
				if (empty($findObjeto)) {
					if ($this->crud->updateLite('item_coleta', [
							'status_afericao' => 'entregue',
							'origem_baixa' => 'mobile',
							'data_baixa' => date(DATE_W3C),
							'responsavel_baixa' => 0
						], 'objeto', $objeto) == true) {
						$this->message['success'] = "Objeto conferido com sucesso";
						$this->message['data'] = $this->obterRegistro($objeto);
					} else {
						$this->message['erro'] = "Erro na conferencia tente novamente.";
					}
					echo json_encode($this->message);
					return null;
				} else {
					$this->message['erro'] = "Esse objeto ja foi conferido";
					echo json_encode($this->message);
					return null;
				}
			} else {
				/*$find = $this->crud->find('item_coleta', "objeto = '{$objeto}' AND id_coleta != '{$lote}'")->row();
				if ( ! empty($find)) {
					$this->message['erro'] = "Esse objeto pertence ao lote: {$find->id_coleta}";
					$this->message['data'] = $this->obterRegistro($objeto);
				} else {
					$this->message['erro'] = "Objeto n???o encontrado";
				}*/
				$this->message['erro'] = "Objeto n???o encontrado";
				echo json_encode($this->message);
				return null;
			}
		}
	}

	public function pesquisa()
	{
		$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if (!empty($post)) {
			$this->search($post);
		} else {
			$this->load->view('painel-agf/pesquisa', $this->data);
		}
	}


	public function coletorWeb($idLote = null)
	{
		if($this->nivel == 0){
			$this->session->set_flashdata('error', 'Você precisa esta logado para continuar.');
			redirect(base_url());
		}

		$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if (!empty($post)) {
			$this->coletorWebInsert($post);
		} else {
			$loteAberto = $this->crud->query("SELECT * FROM tb_lotes WHERE usuario_id = '{$this->idLogado}' AND status = 0")->row();
			if ($loteAberto) {
				redirect(base_url("coletor/web/{$loteAberto->id}"));
			}
			$this->data['totalObjetos'] = 0000;
			$this->data['id'] = null;
			$this->load->view('painel-cliente/coletoWeb', $this->data);
		}

	}

	public function coletoWebLoteAberto($id)
	{

		if($this->nivel == 0){
			$this->session->set_flashdata('error', 'Você precisa esta logado para continuar.');
			redirect(base_url());
		}

		$loteAberto = $this->crud->query("SELECT * FROM tb_lotes WHERE id = '{$id}' AND usuario_id = '{$this->idLogado}' AND status = 1")->row();
		if ($loteAberto) {
			redirect(base_url("coletor/web"));
		}

		$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		if (!empty($post)) {
			$this->coletorWebInsert($post);
		} else {
			$this->data['totalObjetos'] = $this->objetoConsulta($id)->num_rows();
			$this->data['id'] = $this->objetoConsulta($id)->row()->lote_id;
			$this->load->view('painel-cliente/coletoWeb', $this->data);
		}
	}

	public function coletorWebInsert($post)
	{
		//dados nao enviado
		if (empty($post)) {
			return $this->response->json(['message' => 'Ops, algo deu de errado'], 400);
		}

		//objeto
		$objeto = (!empty($post['objeto'])) ? trim(strtoupper($post['objeto'])) : null;

		//valida etiqueta
		$this->load->library('Etiqueta');
		$validarEtiq = new Etiqueta($objeto);

		if ($validarEtiq->validaDigitoAtual() != true) {
			return $this->response->json(['message' => "Ops, objeto ({$objeto}) lido não é valido!"], 400);
		}

		//verifica se o usuario ja tem um lote em aberto
		$loteAberto = $this->crud->query("SELECT * FROM tb_lotes WHERE usuario_id = '{$this->idLogado}' AND status = 0")->row();

		//verifica se o objeto ja foi bipado anteriomente
		$objetoLido = $this->crud->query("SELECT * FROM tb_lote_item WHERE objeto = '{$objeto}'")->row();
		if ($objetoLido) {
			return $this->response->json(['message' => "Ops, Objeto (<b>{$objeto}</b>) já foi lido anteriomente, já foi inserido no lote: {$objetoLido->lote_id}"], 400);
		}

		$redirect = null;
		if (!empty($loteAberto)) {

			$id = $loteAberto->id;
			$this->crud->update("tb_lotes", [
				'total_objeto' => ($loteAberto->total_objeto + 1)
			], 'id', $id);

		} else {
			$nlote = strtotime(date('YmdHis'));
			$id = $this->crud->insert('tb_lotes', [
				'nlote' => $nlote,
				'usuario_id' => $this->idLogado,
				'status' => 0,
				'total_objeto' => 1,
			], true);

			$redirect = base_url("coletor/web/{$id}");
		}

		if ($this->crud->insert('tb_lote_item', [
			'lote_id' => $id,
			'objeto' => $objeto,
		], true)) {

			$this->data['result'] = $this->objetoConsulta($id)->result();
			$dataView = $this->load->view('painel-cliente/consutaweb-result', $this->data, true);

			return $this->response->json(
				[
					'message' => "Objeto (<b>{$objeto}</b>) inserido com sucesso",
					'data' => $dataView,
					'total' => str_pad($this->objetoConsulta($id)->num_rows(), 4, 0, STR_PAD_LEFT),
					'redirect' => $redirect
				], 200);
		}
		return $this->response->json(['message' => 'Ops, erro ao adicionar objeto no lote, por favor tente novamente...', 'data' => $dataView], 400);
	}

	public function objetoConsulta($idlote)
	{
		return $this->crud->query("SELECT * FROM tb_lote_item WHERE lote_id = '{$idlote}' ORDER BY id DESC LIMIT 8");
	}


	private function search($post)
	{

		if (empty($post)) {
			$this->message['erro'] = "ops, algo deu de errado";
			echo json_encode($this->message);
			return null;
		} else {
			$objeto = (!empty($post['objeto'])) ? trim($post['objeto']) : null;
			$find = $this->crud->find('item_coleta', "objeto = '{$objeto}'")->row();
			if (!empty($find)) {
				$this->data['result'] = $find;
				$this->message['success'] = "OK";
				$this->message['data'] = $this->load->view('painel-agf/consuta-result', $this->data, true);
				echo json_encode($this->message);
				return null;
			} else {
				$this->message['erro'] = "Erro";
				echo json_encode($this->message);
				return null;
			}
		}
	}

	public function fechaLote($id)
	{
		if($this->crud->update("tb_lotes", [
			'status' => 1
		], 'id', $id)){
			return $this->response->json(['message' => 'Lote finalizado com sucesso'], 200);
		}
		return $this->response->json(['message' => 'Ops, erro ao finalizar lote, tente novamente mais tarde por favor...'], 400);
	}

	private function obterRegistro($objeto)
	{
		$find = $this->crud->find('item_coleta', "objeto = '{$objeto}'")->row();
		$data = null;
		if (!empty($find)) {
			$this->data['result'] = $find;
			return $this->load->view('painel-agf/conferencia-dados-lote', $this->data, true);
		}
	}


}
