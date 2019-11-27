<?php

// pagina enviar para o banco o delete da transportadora


	error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
 	include_once ('conexao.php');

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

    $idTransportadora = $_POST['idTransportadora'];

    $sql = "DELETE FROM transportadora WHERE idTransportadora = '$idTransportadora' AND idEmpresa = '$idEmpresa'";
    $executa   = $mysqli->query($sql);

		$_SESSION['mensagem'] = "Transportadora excluída com sucesso!";
		$_SESSION['notify'] = 1;


?>
