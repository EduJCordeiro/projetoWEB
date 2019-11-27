<?php
//pagina enviar para o banco o editar o modelo da maquina

	include('conexao.php'); // Pega a conexão do banco de dados

    session_start(); // inicia a sessao


		if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) // validação de usuario e senha
		{
			unset($_SESSION['usuario']);
			unset($_SESSION['senha']);
			header('location:login'); // redireciona para a tela de login
		}

	$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");  // select para consulta de dados 

	while($consult= mysqli_fetch_array($consulta)){ // while para pegar os dados da consulta
			$idEmpresa   = $consult['idEmpresa'];
	}

	$id         = $_POST['idModeloEditar']; // define variavel para do dado recebido pelo post
	$nome       = $_POST['nomeModeloEditar']; // define variavel para do dado recebido pelo post
	$fabricante = $_POST['fabricanteModeloEditar']; // define variavel para do dado recebido pelo post


    // Inserção de valores
		$result = "UPDATE modelo SET nome = '$nome', fabricante = '$fabricante' WHERE idModelo = '$id' AND idEmpresa = '$idEmpresa'";
		$res = $mysqli->query($result); // executa a query com os valores

		if($res){
			 $_SESSION['notify'] = 1;
			 $_SESSION['mensagem'] = 'Modelo e fabricante editado com sucesso!';
		}
		else{
		  echo mysqli_error($mysqli);
		}

    mysqli_close($mysqli); // fecha o mysql

		header('location:maquinas'); // redireciona para a pagina de controle-maquinas

?>
