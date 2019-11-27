<?php
// faz busca avançada das maquinas


	include('conexao.php'); // Pega a conexão do banco de dados

    session_start(); // inicia a sessão

		if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) // validação de usuario e senha
		{
			unset($_SESSION['usuario']);
			unset($_SESSION['senha']);
			header('location:login'); // redireciona para a tela de login
		}
  $usuario = ($_SESSION['usuario']);

	$_SESSION['modelo']	    = $_POST['modeloBusca']; // define variavel para do dado recebido pelo post
	$_SESSION['fabricante'] = $_POST['fabricanteBusca']; // define variavel para do dado recebido pelo post
    $_SESSION['apelido']	= $_POST['apelidoBusca']; // define variavel para do dado recebido pelo post
	$_SESSION['patrimonio'] = $_POST['patrimonioBusca']; // define variavel para do dado recebido pelo post
	$_SESSION['status']     = $_POST['statusBusca']; // define variavel para do dado recebido pelo post
	$_SESSION['dtInicio']   = $_POST['PeriodoInicio']; // define variavel para do dado recebido pelo post
	if($_SESSION['dtInicio']  == ""){
		$_SESSION['dtInicio'] = "00/00/0000";
	}
	$_SESSION['dtInicio'] = implode("-",array_reverse(explode("/",$_SESSION['dtInicio'])));
	$_SESSION['dtFim']      = $_POST['PeriodoFim']; // define variavel para do dado recebido pelo post
	if($_SESSION['dtFim']  == ""){
		$_SESSION['dtFim'] = "99/99/9999";
	}
	$_SESSION['dtFim'] = implode("-",array_reverse(explode("/",$_SESSION['dtFim']))); // converte a data de 00/00/0000 para 0000-00-00

	$_SESSION['busca'] = 1; // define a sessao busca igual a 1

?>
