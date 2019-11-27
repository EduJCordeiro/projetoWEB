<?php

// pagina enviar para o banco o delete do modelo

	include('conexao.php'); // Pega a conexão do banco de dados

    session_start();


		if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))
		{
			unset($_SESSION['usuario']);
			unset($_SESSION['senha']);
			header('location:login');
		}

	$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");

	while($consult= mysqli_fetch_array($consulta)){
			$idEmpresa   = $consult['idEmpresa'];
	}

	$id  = $_POST['idModelo'];

    // Inserção de valores
		$delet = "DELETE FROM modelo WHERE idModelo = '$id' AND idEmpresa = '$idEmpresa'";
		$del = $mysqli->query($delet);

		$_SESSION['notify'] = 1;
		$_SESSION['mensagem'] = 'Modelo excluído com sucesso!';

    mysqli_close($mysqli);

?>
