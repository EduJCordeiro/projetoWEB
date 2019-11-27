<?php
// pagina enviar para o banco o cadastro de transportadora


	include('conexao.php'); // Pega a conexão do banco de dados

    session_start();


		if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))
		{
			unset($_SESSION['usuario']);
			unset($_SESSION['senha']);
			header('location:login');
		}
  $usuario = ($_SESSION['usuario']);

	$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");

	while($consult= mysqli_fetch_array($consulta)){
			$idEmpresa   = $consult['idEmpresa'];
	}

	$nome    	     = $_POST['nomeTransportadora'];
	$telefone  	   = $_POST['telefone'];

    // Inserção de valores

		$result   = "INSERT INTO transportadora (nomeTransportadora, telefone, idEmpresa)
				VALUES ('$nome', '$telefone', '$idEmpresa')";

		$res = $mysqli->query($result);

		if($res){
			$_SESSION['notify'] = 1;
			$_SESSION['mensagem'] = 'Transportadora cadastrada com sucesso!';
		}
		else{
		  echo mysqli_error($mysqli);
		}

    mysqli_close($mysqli);

		header('location:transportadora-status');

?>
