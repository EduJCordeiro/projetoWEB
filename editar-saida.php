<?php
// pagina enviar para o banco o editar saida de maquinas


	include('conexao.php'); // Pega a conexão do banco de dados

    session_start(); //inicia a sessao


		if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))// validação de usuario e senha
		{
			unset($_SESSION['usuario']);
			unset($_SESSION['senha']);
			header('location:login');// redireciona para a tela de login
		}

	$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");// select para consulta de dados 

	while($consult= mysqli_fetch_array($consulta)){// while para pegar os dados da consulta
			$idEmpresa   = $consult['idEmpresa'];
	}

	$id               = $_POST['codigoMaquinaEditar']; // define variavel para do dado recebido pelo post
	$data             = $_POST['dataSaidaEditar']; // define variavel para do dado recebido pelo post
	$hora    	        = $_POST['horaSaidaEditar']; // define variavel para do dado recebido pelo post
	$transportadora   = $_POST['nomeTransportadoraEditar']; // define variavel para do dado recebido pelo post
	$observacoes    	= $_POST['observacoesEditar']; // define variavel para do dado recebido pelo post

	$data = implode("-",array_reverse(explode("/",$data)));

    // Inserção de valores
		$result = "UPDATE saidamaquina SET dataSaida = '$data', horaSaida = '$hora', transportadora = '$transportadora', observacoes = '$observacoes' WHERE idSaidaMaquina = '$id' AND idEmpresa = '$idEmpresa'";
		$res = $mysqli->query($result); // executa a query com os valores e faz o update

		if($res){
			 $_SESSION['notify'] = 1;
			 $_SESSION['mensagem'] = 'Saída editada com sucesso!';
		}
		else{
		  echo mysqli_error($mysqli);
		}

    mysqli_close($mysqli); // fecha o mysql

  	header('location:controle-maquinas'); // redireciona para a pagina de controle-maquinas
?>
