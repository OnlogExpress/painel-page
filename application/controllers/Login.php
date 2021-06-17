<?php

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

class Login extends CI_Controller {

	private $message = [];
	private $requiredField = ['email', 'codigo', 'new_pwd', 'conf_pwd'];
	private $cod;

	function __construct() {
		parent::__construct();
		$this->load->model('Login_models', 'mlogin');
		$this->data['titulo'] = 'Acesso Restrito';
		$this->load->library('Encryption');
		$this->encryption->initialize(array('driver' => 'mcrypt'));
		$this->load->helpers('Helpers');
		if (($this->session->userdata('logado'))) {
			redirect(base_url());
		}
	}

	public function index() {
		$this->data['returnUrl'] = null;
		if (isset($_GET) && ! empty($_GET)) {
			$url = $this->input->get('returnUrl');
			$this->data['returnUrl'] = $url;
		}

		$this->load->view('login/form', $this->data);
	}


	public function senha()
	{
		$senha = "6ab0e1b8a724284600593c5e7e70e6031ff2e908d2050c518d4930297ab4173f9831a478a757ceac01b4e2e0f404bdd7f2b3bde60adba0666eb47976fcd152ceIWIZ6tyW1harB7+nIs4Gc8HPR5WsHiVMTAwKum6X0iY=";
		$senha = $this->encryption->decrypt($senha);

		var_dump($senha);
	}

	public function autenticacao() {

		$returnUrl = base_url('login'); //URL PADRÃO

		//URL RETURN QUANDO HOUVE;
		if ( ! empty($this->input->post('returnUrl'))) {
			$url = $this->input->post('returnUrl');
			$returnUrl = base_url('login?returnUrl=') . $url;
		}

		if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != base_url() && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != $returnUrl):
			$json = array('result' => false, 'mensagem' => 'Origem da requisição não autorizada!');
			echo json_encode($json);
			exit();
		endif;

		header('Access-Control-Allow-Origin: ' . base_url());
		header('Access-Control-Allow-Methods: POST');
		header('Access-Control-Max-Age: 1000');
		header('Access-Control-Allow-Headers: Content-Type');


		$this->load->library('form_validation');
		$this->form_validation->set_rules('type_email', 'Login', 'valid_login|required|xss_clean|trim');
		$this->form_validation->set_rules('senha', 'Senha', 'required|xss_clean|trim');


		$type_email = $this->input->post('type_email');
		$password = $this->input->post('senha');
		$usuario = $this->mlogin->check_user($type_email);

		if ($usuario) {
			$password_stored = $this->encryption->decrypt($usuario->senha);
			if ($password == $password_stored) {
				$session_master = array(
					'id' => $usuario->id,
					'nome' => $usuario->nome,
					'nivel' => $usuario->nivel,
					'logado' => true,
					'attempts' => 0,
				);
				$this->session->set_userdata($session_master);
				$json = array('result' => true, 'redirect' => base_url('Painel'));
			} else {
				$json = array('result' => false, 'mensagem' => 'Acesso não autorizado. Por favor tente novamente.');
			}
		} else {
			$json = array('result' => false, 'mensagem' => 'E-mail não encontrado!');
		}
		echo json_encode($json);
	}

	public function resetSenhaViaEmail() {
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if ( ! $email) {
			echo json_encode(['erro' => 'Esse e-mail não é valido.']);
			return null;
		} else {

			$find_email = $this->crud->find('logins', "email = '{$email}'", 'email, nome, idlogin');
			if ( ! empty($find_email)) {
				$data_atual = date('Y-m-d H:i:s');//data atual
				$data_expira = date('Y-m-d H:i:s',
					strtotime('+2 hour', strtotime($data_atual))); //data que irá expirar o email.
				$codigo = md5(uniqueAlfa(17) . $find_email->idlogin);

				$this->data['userData'] = $find_email;
				$this->data['url'] = base_url("login/recuperar-senha/" . $codigo);
				$html = $this->load->view('emails/email_automatico_senha', $this->data, true);

				$subject = "Recuperação de Senha";
				$this->load->library('Email');
				$email_send = (new Email())->bootstrap(
					$subject,
					$html,
					$find_email->email,
					$find_email->nome
				);
				if ($email_send->send()) {
					$this->crud->edit('logins', ['codigo' => $codigo, 'data_expira' => $data_expira], 'idlogin',
						$find_email->idlogin);
					echo json_encode(['success' => 'Enviamos para seu e-mail um link para continuar o processo de redefinição de senha.']);
					return null;
				} else {
					echo json_encode(['erro' => 'erro ao enviar o email']);
					return null;
				}
			} else {
				echo json_encode(['erro' => 'Esse e-mail não existe em nosso banco de dados']);
				return null;
			}
		}
	}

	public function formNewPassword($param) {
		$data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);

		$data_atual = date(DATE_W3C);//data atual
		$find = $this->crud->find('logins', "codigo = '{$param}' AND data_expira >= '{$data_atual}'");

		if ( ! empty($data)) {
			$senha = trim($data['nsenha']);
			if (mb_strlen($senha) < 6) {
				echo json_encode(['erro' => 'A senha deve conter no minimo 6 caracteres']);
				return null;
			}
			$data_insert = [
				'senha' => $this->encryption->encrypt($senha),
				'codigo' => null,
				'data_expira' => null
			];
			if ( ! empty($find)) {
				if ($this->crud->edit('logins', $data_insert, 'idlogin', $find->idlogin)) {
					echo json_encode(['success' => 'Senha atualizada com sucesso']);
					return null;
				} else {
					echo json_encode(['erro' => 'Erro ao atualizar senha.']);
					return null;
				}
			} else {
				echo json_encode(['erro' => 'Erro ao atualizar senha.']);
				return null;
			}

		} else {
			if ( ! empty($find)) {
				$this->load->view('login/reseta-senha', $this->data);
			} else {
				$this->session->set_flashdata('error', 'Link expirou');
				redirect(base_url('login'));
			}
		}
	}

	public function savePassword() {
		$data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);

		if ( ! empty($data)) {
			if ( ! required($this->requiredField, $data)) {
				$this->message['erro'] = "Os campos [Email, Senhas] são obrigatórios";
				echo json_encode($this->message);
				return null;
			}
			if ($data['new_pwd'] <> $data['conf_pwd']) {
				$this->message['erro'] = "O Confirma senha deve ser igual ao campo senha.";
				echo json_encode($this->message);
				return null;
			}

			$findEmail = (array)$this->crud->find('logins', "email = '{$data['email']}' AND codigo = '{$data['codigo']}'");
			if (empty($findEmail)) {
				$this->message['erro'] = "Os dados não foram inserido corretamente";
				echo json_encode($this->message);
				return null;
			} else {
				$senhaNew = $this->encryption->encrypt($data['new_pwd']);
				$params = ['codigo' => $data['codigo'], 'email' => $data['email']];
				$dataDB = ['senha' => $senhaNew, 'codigo' => null, 'primeiro_acesso' => 2];
				if ($this->crud->editWhere('logins', $dataDB, $params) == true) {

					$session_master = array(
						'id' => $findEmail['idlogin'],
						'empresaid' => $findEmail['idempresa'],
						'nome' => $findEmail['nome'],
						'nivel' => $findEmail['nivel_id'],
						'logado' => true,
						'attempts' => 0,
					);
					$this->session->set_userdata($session_master);
					$this->message['success'] = "Dados atualizado com sucesso!";
					$this->message['urlDir'] = base_url('Painel');
				} else {
					$this->message['erro'] = "Erro ao atualizar, verifique os dados";
				}
				echo json_encode($this->message);
				return null;
			}
		} else {
			$this->session->set_flashdata('error', 'Acesso não permitido!');
			redirect(base_url('login'));
		}
	}

	public function cadastroSenha($codigo = null) {

		$mail = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRIPPED);

		if ( ! empty($mail) && $codigo != null && filter_var($mail, FILTER_VALIDATE_EMAIL) && isset($mail)) {
			$findEmail = (array)$this->crud->find('logins', "email = '{$mail}' AND codigo = '{$codigo}'");

			if (empty($findEmail)) {
				$this->session->set_flashdata('error', 'O link expirou, contate o suporte!');
				redirect(base_url('login'));
				return null;
			}
			$this->data['dados'] = $findEmail;
			$this->load->view('login/form-cadastrar-senha', $this->data);
		} else {
			$this->session->set_flashdata('error', 'O link expirou, contate o suporte!');
			redirect(base_url('login'));
		}
	}

	public function home() {
		$this->load->view('login/home', $this->data);
	}
}
