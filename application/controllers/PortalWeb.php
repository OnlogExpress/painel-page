<?php

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

require APPPATH . '/hooks/MY_Painel.php';

class PortalWeb extends \MY_Painel
{
	public $scriptJS = '';
	private $data = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->helpers('Helpers');
		$this->load->library('Encryption');
		$this->encryption->initialize(array('driver' => 'mcrypt'));

		if ($this->nivel != 3) {
			redirect(base_url());
		}

		$this->scriptJS = $this->load->view("portal-web/javascript", $this->data, true);
	}

	public function index()
	{
		$this->data['menuHome'] = '';
		$this->load->view("portal-web/index", $this->data);
	}

	public function listaDados()
	{
		$option = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
		$pagina_inicial = (!empty($option['pagina_inicial'])) ? $option['pagina_inicial'] : null;
		$quantidade_pagina = (!empty($option['quantidade_pagina'])) ? $option['quantidade_pagina'] : null;
		$page = (!empty($option['route'])) ? $option['route'] : null;

		$terms = " SELECT
						t.id,
						t.lote_id,
						t.objeto,
						t.data_cadastro,
						t.status_id,
						p.descricao,
						p.data_postagem
					FROM
						tb_lote_item t
						LEFT JOIN tb_lotes l ON l.id = t.lote_id
						LEFT JOIN tb_status_postagem p ON p.id_item = t.id
						WHERE l.usuario_id = '{$this->idLogado}'";

		if (!empty($pagina_inicial) || !empty($quantidade_pagina)) {
			//calcula o inicio da visualização - paginas
			$inicio = ($pagina_inicial * $quantidade_pagina) - $quantidade_pagina;
			$this->data['data'] = $this->crud->query("{$terms} LIMIT {$inicio}, $quantidade_pagina")->result();
			$this->data['count'] = $this->crud->query("{$terms}")->num_rows();
			$this->data['quantidade_pagina'] = $quantidade_pagina;
			$this->data['pagina_inicial'] = $pagina_inicial;
			$this->data['page'] = $page;
			$this->load->view("portal-web/table", $this->data);
		} else {
			echo "Nenhum dados encontrados";
		}
	}


	public function soapSet()
	{

		//@var string - URL dos correios para obter dados
		$__wsdl = "http://webservice.correios.com.br/service/rastro/Rastro.wsdl";

		//@var array - a ser usado com parametro para 1 objeto
		$_buscaEventos = [
			'cli' => [
				'usuario' => 'LOONYCONFE',
				'senha' => 'J378:G7L@R',
				'tipo' => 'L',
				'resultado' => 'T',
				'lingua' => '101'
			]
		];
		$_buscaEventos['objetos'] = 'OO724055060BR';

		// criando objeto soap a partir da URL
		$client = new SoapClient($__wsdl);
		$r = $client->buscaEventos($_buscaEventos);

		var_dump($r);
	}

}
