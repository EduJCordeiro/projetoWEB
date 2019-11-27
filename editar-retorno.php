<?php
// pagina enviar para o banco o editar retorno da maquina

	include('conexao.php'); // Pega a conexão do banco de dados

    session_start(); // inicia a sessao


		if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) // validação de usuario e senha
		{
			unset($_SESSION['usuario']);
			unset($_SESSION['senha']);
			header('location:login'); // redireciona para a tela de login
		}

	$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'"); // select para consulta de dados 

	while($consult= mysqli_fetch_array($consulta)){// while para pegar os dados da consulta
			$idEmpresa   = $consult['idEmpresa'];
	}

	$id               = $_POST['codigoMaquina']; // define variavel para do dado recebido pelo post
	$data             = $_POST['dataRetorno']; // define variavel para do dado recebido pelo post
	$hora    	        = $_POST['horaRetorno']; // define variavel para do dado recebido pelo post

	$data = implode("-",array_reverse(explode("/",$data)));

    // Inserção de valores
		$result = "UPDATE retornomaquinas SET dataRetorno = '$data', horaRetorno = '$hora' WHERE idRetornoMaquinas = '$id' AND idEmpresa = '$idEmpresa'";
		$res = $mysqli->query($result); // executa a query com os valores

		if($res){
			 $_SESSION['notify'] = 1;
			 $_SESSION['mensagem'] = 'Retorno editado com sucesso!';
		}
		else{
		  echo mysqli_error($mysqli);
		}

    mysqli_close($mysqli); // fecha o mysql

  	header('location:controle-maquinas'); // redireciona para a pagina de controle-maquinas
?>
