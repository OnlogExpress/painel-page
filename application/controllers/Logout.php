<?php

class Logout extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $this->session->set_flashdata('error', 'Você precisa esta logado para continuar.');
        $this->session->sess_destroy();
        redirect(base_url('login'));
    }

    public function logoutAdminToCustomer($id)
    {
        $this->session->sess_destroy();
        redirect(base_url('login-as-customer/'.$id));
    }

    public function logarAsCustomer($id)
    {
        $customer = $this->crud->find('logins', "md5(idempresa) = '{$id}'");
        $session_master = array(
            'id' => $customer->idlogin,
            'empresaid' => $customer->idempresa,
            'nome' => $customer->nome,
            'nivel' => $customer->nivel_id,
            'logado' => true,
            'attempts' => 0,
        );
        $this->session->set_userdata($session_master);
        redirect(base_url());
    }
}
