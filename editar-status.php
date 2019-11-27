<?php
// pagina enviar para o banco o editar status de maquinas

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

	$id         = $_POST['idStatusEditar'];
	$nome    	  = $_POST['nomeStatusEditar'];
	$area       = $_POST['areaStatusEditar'];

    // Inserção de valores
		$result = "UPDATE status SET nome = '$nome', area = '$area' WHERE idStatus = '$id' AND idEmpresa = '$idEmpresa'";
		$res = $mysqli->query($result);

		if($res){
			 $_SESSION['notify'] = 1;
			 $_SESSION['mensagem'] = 'Status editado com sucesso!';
		}
		else{
		  echo mysqli_error($mysqli);
		}

    mysqli_close($mysqli);

		// DANDO ERRO NO SCRIPT QUE VOLTA AUTOMATICAMENTE
  	header('location:transportadora-status');
?>
