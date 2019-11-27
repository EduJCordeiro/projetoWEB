<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/*
	 * Esta função carrega a tela inicial com os dados da tabela clientes.
	 * Carrego o model de clientes, depois chamo função clientes() dentro do model que me traz os dados do cliente
	 */
	public function index()
	{
		$this->load->model("m_clientes", "clientes");
		
		$dados['clientes'] = $this->clientes->clientes();
		
		$this->load->view('home', $dados);
	}
}
