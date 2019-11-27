<?php

// pagina enviar para o banco o cadastro de modelo

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

	$modelo    	     = $_POST['nomeModelo'];
	$fabricante  	   = $_POST['fabricanteModelo'];

    // Inserção de valores

		$result   = "INSERT INTO modelo (nome, fabricante, idEmpresa)
				VALUES ('$modelo', '$fabricante', '$idEmpresa')";

		$res = $mysqli->query($result);

		if($res){
			$_SESSION['notify'] = 1;
			$_SESSION['mensagem'] = 'Modelo e fabricante cadastrados com sucesso!';
		}
		else{
		  echo mysqli_error($mysqli);
		}

    mysqli_close($mysqli);

?>
