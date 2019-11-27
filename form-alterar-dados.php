<?php
// pagina enviar para o banco a alteração de dados do usuario

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

	date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil

	$id        = $_POST['idUsuario'];
	$usuario   = $_POST['usuarioAltera'];
	$senha     = $_POST['senhaAltera'];
  $_SESSION['usuario'] = $usuario;
  $_SESSION['senha'] = $senha;

    // Inserção de valores
		$result = "UPDATE login SET usuario = '$usuario', senha = '$senha' WHERE idUsuario = '$id' AND idEmpresa = '$idEmpresa'";
		$res = $mysqli->query($result);

		if($res){
			 $_SESSION['notify'] = 1;
			 $_SESSION['mensagem'] = 'Dados editados com sucesso!';
		}
		else{
		  echo mysqli_error($mysqli);
		}

    mysqli_close($mysqli);

		$var = "<script>javascript:history.back(-1)</script>";
		echo $var;

?>
