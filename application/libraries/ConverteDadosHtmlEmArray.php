<?php


class ConverteDadosHtmlEmArray {

	private $response = [];
	private $file = '';

	public function __construct($file = null) {
		$this->file = $file;
	}

	public function toConverte() {
		$file_url = file('http://svp.correios.com.br/app/consultas/plp/pesquisa.php?plp_objeto='.$this->file);
		return (array)$this->htmlConvert($file_url);
	}

	/**
	 * @param $file
	 * linha 8 = numero da plp
	 * linha 17 = data de fechamento
	 * linha 26 = cartão do cliente
	 * linha 44 = nome do remetente
	 * linha 76 = objeto "ETIQUETA"
	 * linha 77 = serviço
	 * linha 80 = cep de destino
	 * linha 81 = embalagem
	 * linha 82 = peso
	 * linha 83 = altura
	 * linha 84 = largura
	 * linha 85 = comprimento
	 * linha 85 = diametro
	 * linha 87 = status
	 */
	private function htmlConvert($file) {
		foreach ($file as $linha_num => $linha) {
			/** @var $num_linha  numero da linha */
			$num_linha = (int)trim($linha_num);

			/** @var $valor limpa as tags html e tira qualquer espaçamento */
			$valor = trim(strip_tags($linha));

			switch ($num_linha) {
				case 8:
					$this->response['numero'] = $valor;
					break;
				case 17:
					$this->response['data_fechamento'] = $valor;
					break;
				case 26:
					$this->response['cartao'] = $valor;
					break;
				case 44:
					$this->response['remetente'] = $valor;
					break;
				case 76:
					$this->response['objeto'] = $valor;
					break;
				case 77:
					$this->response['servico'] = $valor;
					break;
				case 80:
					$this->response['cep_destino'] = $valor;
					break;
				case 81:
					$this->response['embalagem'] = $valor;
					break;
				case 82:
					$this->response['peso'] = $valor;
					break;
				case 83:
					$this->response['altura'] = $valor;
					break;
				case 84:
					$this->response['largura'] = $valor;
					break;
				case 85:
					$this->response['comprimento'] = $valor;
					break;
				case 86:
					$this->response['diamentro'] = $valor;
					break;
				case 87:
					$this->response['status'] = $valor;
					break;
			}
		}
		if(empty($this->response)){
			return (array) ['status' => 'erro', 'messagem' => $valor];
		}else{
			return (array) $this->response;
		}
	}
}
