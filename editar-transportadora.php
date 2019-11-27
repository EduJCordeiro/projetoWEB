<?php

// pagina enviar para o banco o editar transportadora

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


	date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil

	$id         = $_POST['idTransportadoraEditar'];
	$nome    	  = $_POST['nomeTransportadoraEditar'];
	$telefone   = $_POST['telefoneTransportadoraEditar'];

    // Inserção de valores
		$result = "UPDATE transportadora SET nomeTransportadora = '$nome', telefone = '$telefone' WHERE idTransportadora = '$id' AND idEmpresa = '$idEmpresa'";
		$res = $mysqli->query($result);

		if($res){
			 $_SESSION['notify'] = 1;
			 $_SESSION['mensagem'] = 'Transportadora editada com sucesso!';
		}
		else{
		  echo mysqli_error($mysqli);
		}

    mysqli_close($mysqli);

		// DANDO ERRO NO SCRIPT QUE VOLTA AUTOMATICAMENTE
		header('location:transportadora-status');

?>
