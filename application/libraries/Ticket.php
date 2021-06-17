<?php

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso n���o permitido</h1>');


class Ticket {
	protected $ticket;
	protected $dv;
	private $messagem_erro = [
		'erro' => false /* 'Ops!, Essa etiqueta não e valida, por favor verifica a etiqueta digitada.' */,
		'success' => true
	];

	public function __construct($ticket = null) {
		$this->ticket = $ticket;
	}

	public function validaEtiqueta() {
		if (mb_strlen($this->ticket) != 13) {
			return $this->messagem_erro['erro'];
		} else {
			if ($this->verificaSigla()) {
				return $this->messagem_erro['erro'];
			} else {
				if ($this->getDigitoAtual()) {
					return $this->messagem_erro['success'];
				} else {
					return $this->messagem_erro['erro'];
				}
			}
		}

	}


	private function getDigitoAtual() {
		$digitoAtual = mb_substr($this->ticket, 10, 1);

		if ($digitoAtual == $this->verificaDigitoVerificado()) {
			return true;
		} else {
			return false;
		}
	}


	private function verificaSigla() {

		//Sigla primaria, retorna primeiro e o segundo digito da etiqueta.
		$p1 = is_numeric(mb_substr($this->ticket, 0, 1)) ? true : false;
		$p2 = is_numeric(mb_substr($this->ticket, 1, 1)) ? true : false;

		//Sigla Segundaria, retorna penultimo e o último digito da etiqueta.
		$s1 = is_numeric(mb_substr($this->ticket, 11, 1)) ? true : false;
		$s2 = is_numeric(mb_substr($this->ticket, 12, 1)) ? true : false;

		if ($p1 || $p2 || $s2 || $s1) {
			return true;
		} else {
			return false;
		}
	}

	public function checkerDigit() {
		$numero = $this->ticket; //mb_substr($this->ticket, 2, 8);
		$fatoresDePonderacao = array(8, 6, 4, 2, 3, 5, 9, 7);
		$soma = 0;
		for ($i = 0; $i < 8; $i++) {
			$soma += ($numero[$i] * $fatoresDePonderacao[$i]);
		}
		$modulo = $soma % 11;
		if ($modulo == 0) {
			$this->dv = 5;
		} else {
			if ($modulo == 1) {
				$this->dv = 0;
			} else {
				$this->dv = 11 - $modulo;
			}
		}
		return $numero.$this->dv;
	}

	public function processarTikcet() {
		$this->load->library('Ticket');
		foreach (range('A', 'Z') as $letrai){
			foreach (range('A', 'Z') as $letraf){
				$s = "{$letrai}{$letraf}";
				$this->apiModel->insert('tickets', ['label_star' => $s, 'number' => '00000001', 'label_end' => 'BR', 'status' => 'aguardando']);
			}
		}
		echo  'fim';
		exit();

		//		if($tdata['ticket_end'] == '99999999'){
//			/** finalizamos o resitro atual */
//			$this->apiModel->update('tickets', ['status' => 'finalizado'], 'id', $id);
//
//			/** abrimos o proximo registro para processando */
//			$id = $id+1;
//			$this->apiModel->update('tickets', ['status' => 'processando'], 'id', $id);
//
//			/** proximo registro */
//			$id_proximo = $id+1;
//			$this->apiModel->update('tickets', ['status' => 'proximo'], 'id', $id_proximo);
//
//			$tdata = $this->apiModel->getData("SELECT * FROM tickets WHERE id = '{$id}' AND status  = 'processando' LIMIT 1")->row_array();
//		}else{
//			$tdata = $this->apiModel->getData("SELECT * FROM control_ticket_range WHERE id = '{$id}' AND status  = 'processando' LIMIT 1")->row_array();
//		}
	}
}
