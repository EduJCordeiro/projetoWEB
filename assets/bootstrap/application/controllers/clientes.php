<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

	/*
	 * A função abaixo recebe o id_cliente como parâmetro e retorna os dados
	 * do cliente em formato jSon para a função $.post() do jquery lá na view.
	 */
	public function dados_cliente(){
		
		//recebo o id_cliente da view para trazer os dados somente daquele cliente
		$id_cliente = $this->input->post("id_cliente");
		
		//carrego o model responsável pelos clientes
		$this->load->model("m_clientes", "clientes");
		
		//chamo a função clientes() dentro do model que me traz somente os dados de um cliente, pois estou passando o id_cliente como parãmetro
		$consulta = $this->clientes->clientes($id_cliente);
		
		//antes de continuar, verifico se alguma informação foi retornada, para não dar erro.
		if ($consulta->num_rows() == 0) {
			die("Cliente não encontrado");
		}
		
		//como eu vou retornar os dados para a view em formato jSon, aqui eu crio os índices para serem acessados dentro da função $.post()
		$array_clientes = array(
		
			"id_cliente" => $consulta->row()->id,
			"nome" => $consulta->row()->nome,
			"email" => $consulta->row()->email
			
		);
		
		/*
		 * Após os índices criados para o formato jSon, dou um echo no jsonEcode da array acima.
		 */
		echo json_encode($array_clientes);
		
	}

	/*
	 * Função que recebe os dados via post do formulário.
	 */
	
	public function salvar()
	{
		//Carrego o model clientes
		$this->load->model("m_clientes", "clientes");
		
		$id_cliente = $this->input->post("id_cliente");
		$nome= $this->input->post("nome");
		$email = $this->input->post("email");
		
		
		//Aqui eu seto cada campo da tabela com seu respectivo valor para o update no model.
		$dados_cliente = array(
		
			"nome" => $nome,
			"email" => $email
		
		);
		
		//Agora eu chamo a função salvar() dentro do model passando o id_cliente e os dados do cliente como parâmetro
		//Se tiver sucesso, então retorno com o código 1, pois recupero as informações via ajax na view.
		if ($this->clientes->salvar($id_cliente, $dados_cliente))
			echo 1;
		else
			echo 0;		
	}
}
