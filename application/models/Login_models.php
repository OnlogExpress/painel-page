<?php

defined('BASEPATH') OR exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

class Login_models extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function check_user($type_email) {
        $this->db->where('email', $type_email);
        $this->db->where('status', 1);
        $this->db->limit(1);
        return $this->db->get('autenticacao')->row();
    }

    public function check_senha_membro($id) {
        $this->db->where('idm', $id);
        $this->db->limit(1);
        return $this->db->get('membro_igreja')->row();
    }
    
}
