<?php

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

require APPPATH.'/hooks/MY_Painel.php';

class Autenticacao extends \MY_Painel {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		if($this->nivel == 1){
			redirect(base_url('portal-agf'));
		}elseif($this->nivel == 2) {
			redirect(base_url('portal-cliente'));
		}elseif ($this->nivel == 3){
			redirect(base_url('portal'));
		}else{
			$this->session->set_flashdata('error', 'Erro ao logar, tente novamente mais tarde...');
			redirect(base_url('logout'));
		}
	}
}
