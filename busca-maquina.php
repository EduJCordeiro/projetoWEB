<?php
	include('conexao.php'); // Pega a conexão do banco de dados

    session_start(); // inicia a sessão


		if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))// validação de usuario e senha
		{
			unset($_SESSION['usuario']);
			unset($_SESSION['senha']);
			header('location:login');// redireciona para a tela de login
		}
  $usuario = ($_SESSION['usuario']); // pega o usuario da sessao

	$_SESSION['modelo']	     = $_POST['modeloBusca']; // define variavel para do dado recebido pelo post
	$_SESSION['fabricante']  = $_POST['fabricanteBusca']; // define variavel para do dado recebido pelo post
  $_SESSION['apelido']	   = $_POST['apelidoBusca']; // define variavel para do dado recebido pelo post
	$_SESSION['patrimonio']  = $_POST['patrimonioBusca']; // define variavel para do dado recebido pelo post
	$_SESSION['status']      = $_POST['statusBusca']; // define variavel para do dado recebido pelo post

	$_SESSION['busca'] = 1; // define a sessao busca igual a 1

?>
