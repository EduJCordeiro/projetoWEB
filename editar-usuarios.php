<?php

// pagina enviar para o banco o editar usuarios


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

	$id         = $_POST['idUsuario'];
	$usuario    = $_POST['usuarioEdita'];
	$area       = $_POST['areaEdita'];
	$senha      = $_POST['senhaEdita'];

    // Inserção de valores
		$result = "UPDATE login SET usuario = '$usuario', senha = '$senha', area = '$area' WHERE idUsuario = '$id' AND idEmpresa = '$idEmpresa'";
		$res = $mysqli->query($result);

		if($res){
			 $_SESSION['notify'] = 1;
			 $_SESSION['mensagem'] = 'Usuário editado com sucesso!';
		}
		else{
		  echo mysqli_error($mysqli);
		}

    mysqli_close($mysqli);

		// DANDO ERRO NO SCRIPT QUE VOLTA AUTOMATICAMENTE
		$var = "<script>javascript:history.back(-1)</script>";
		echo $var;
?>
