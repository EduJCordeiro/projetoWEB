<?php


// pagina enviar para o banco o delete da maquina


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

    $idMaquina = $_POST['id'];

		$delet1 = "DELETE FROM maquinas WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'";
		$del1 = $mysqli->query($delet1);

		$delet2 = "DELETE FROM saidamaquina WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'";
		$del2 = $mysqli->query($delet2);

		$delet3 = "DELETE FROM retornomaquinas WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'";
		$del3 = $mysqli->query($delet3);

		$delet4 = "DELETE FROM patiomaquinas WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'";
		$del4 = $mysqli->query($delet4);


		$_SESSION['mensagem'] = "Máquina excluída com sucesso!";
		$_SESSION['notify'] = 1;


?>
