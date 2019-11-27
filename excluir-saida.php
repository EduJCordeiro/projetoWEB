<?php
// pagina enviar para o banco o delete da saida de maquina

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
    $idMaquina 					= $_POST['idMaquinaEditar'];
		$idMaquinaSaida     = $_POST['codigoMaquinaEditar'];
		$nomeRetornoMaquina = $_POST['nomeMaquina'];
		$statusHist         = $_POST['statusHist'];

		$consultaStatus =  $mysqli->query("SELECT * FROM status WHERE idEmpresa = '$idEmpresa' AND  idStatus = '$status'");

		while($reg_cadastro= mysqli_fetch_array($consultaStatus)){

			$statusArea    = $reg_cadastro['area'];

		}

		if($status == 2 || $statusArea == "Comercial"){
			$disponivel = 1;
		}else{
			$disponivel = 0;
		}

    $sql = "UPDATE maquinas SET fase = '$statusHist', futuro = '0' WHERE idMaquina  = '$idMaquina' AND idEmpresa = '$idEmpresa'";
    $executa   = $mysqli->query($sql);
		$delet = "DELETE FROM saidamaquina WHERE idSaidaMaquina = '$idMaquinaSaida' AND idEmpresa = '$idEmpresa'";
		$del = $mysqli->query($delet);

		$_SESSION['mensagem'] = "Saída cancelada com sucesso!";
		$_SESSION['notify'] = 1;


		// DANDO ERRO NO SCRIPT QUE VOLTA AUTOMATICAMENTE
  	header('location:controle-maquinas');

?>
