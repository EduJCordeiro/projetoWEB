<?php
// pagina enviar para o banco o definir status


include('conexao.php'); // Pega a conexão do banco de dados

session_start(); // inicia a sessão


if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) // validação de usuario e senha
{
	unset($_SESSION['usuario']);
	unset($_SESSION['senha']);
	header('location:login'); // redireciona para a tela de login
}

$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");  // select para consulta de dados 

while($consult= mysqli_fetch_array($consulta)){ // while para pegar os dados da consulta
	$idEmpresa   = $consult['idEmpresa'];
}

date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil
$dataAtual = date('Y-m-d');

$id               = $_POST['codigoPatioMaquinaDefinir']; // define variavel para do dado recebido pelo post
$status           = $_POST['statusDefinir']; // define variavel para do dado recebido pelo post
$idMaquina    	  = $_POST['idPatioMaquina']; // define variavel para do dado recebido pelo post
$nomePatioMaquina = $_POST['nomePatioMaquina']; // define variavel para do dado recebido pelo post
$patrimonio = explode(' ', $nomePatioMaquina);
$consultaStatus =  $mysqli->query("SELECT * FROM status WHERE idEmpresa = '$idEmpresa' AND  idStatus = '$status'"); // select para consulta de dados 

$consultaSaida = $mysqli->query("SELECT * FROM saidamaquina WHERE idEmpresa = '$idEmpresa' AND idMaquina = '$idMaquina'"); // select para consulta de dados 
$verificaSaida = mysqli_num_rows($consultaSaida);

if($verificaSaida == '1'){

}

while($reg_cadastro= mysqli_fetch_array($consultaStatus)){

	$statusArea    = $reg_cadastro['area'];  // define variavel para do dado recebido pela consulta

}
if($status != 'Sem status'){ // verifica o status do post e faz o update no banco
	if($verificaSaida == '1'){
		// Inserção de valores
		$result2 = "UPDATE saidamaquina SET statusHist = '$status' WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'";
		$res2 = $mysqli->query($result2); // executa a query com os valores
	}
if($status == '2'){ // verifica o status do post e faz o update no banco
	// Inserção de valores
	$result2 = "UPDATE patiomaquinas SET status = '$status', disponivel = '1', dtDisponivel = '$dataAtual' WHERE idPatioMaquinas = '$id' AND idEmpresa = '$idEmpresa'";
	$res2 = $mysqli->query($result2); // executa a query com os valores

	$consultaHist = $mysqli->query("SELECT * FROM historico WHERE idEmpresa = '$idEmpresa' AND patrimonio = '$patrimonio[0]' AND etapa = 'manutencao' AND finalizado = '0'");
	while($consultHist= mysqli_fetch_array($consultaHist)){
		$idHistorico   = $consultHist['idHistorico'];
	}

	$resultHist = "UPDATE historico SET dtSaida = '$dataAtual', finalizado = '1' WHERE patrimonio = '$patrimonio[0]' AND idEmpresa = '$idEmpresa' AND finalizado = '0' AND etapa = 'manutencao' AND idHistorico = '$idHistorico'";
	$resHist = $mysqli->query($resultHist); // executa a query com os valores

	$resultHist = "INSERT INTO historico (etapa, dtEntrada, dtSaida, patrimonio, idEmpresa)
	VALUES ('disponivel', '$dataAtual', '0000-00-00', '$patrimonio[0]', '$idEmpresa')";
	$resHist = $mysqli->query($resultHist); // executa a query com os valores

}else if($status != '1' && $statusArea == 'Comercial'){ // verifica o status do post e faz o update no banco
	// Inserção de valores
		$result21 = "UPDATE patiomaquinas SET status2 = '$status' WHERE idPatioMaquinas = '$id' AND idEmpresa = '$idEmpresa'";
		$res21 = $mysqli->query($result21); // executa a query com os valores
}else{ // verifica o status do post e faz o update no banco
	// Inserção de valores
	$result21 = "UPDATE patiomaquinas SET status = '$status', disponivel = '0'  WHERE idPatioMaquinas = '$id' AND idEmpresa = '$idEmpresa'";
	$res21 = $mysqli->query($result21); // executa a query com os valores

	$consultaHist = $mysqli->query("SELECT * FROM historico WHERE idEmpresa = '$idEmpresa' AND patrimonio = '$patrimonio[0]' AND etapa = 'disponivel' AND finalizado = '0'");
	while($consultHist= mysqli_fetch_array($consultaHist)){
		$idHistorico   = $consultHist['idHistorico'];
	}

	$resultHist = "UPDATE historico SET dtSaida = '$dataAtual', finalizado = '1' WHERE patrimonio = '$patrimonio[0]' AND idEmpresa = '$idEmpresa' AND finalizado = '0' AND etapa = 'disponivel' AND idHistorico = '$idHistorico'";
	$resHist = $mysqli->query($resultHist); // executa a query com os valores

	$resultHist = "INSERT INTO historico (etapa, dtEntrada, dtSaida, patrimonio, idEmpresa)
	VALUES ('manutencao', '$dataAtual', '0000-00-00', '$patrimonio[0]', '$idEmpresa')";
	$resHist = $mysqli->query($resultHist); // executa a query com os valores

}
	if($verificaSaida != '1'){
		// Inserção de valores
		$result21 = "UPDATE maquinas SET fase = '$status' WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'";
		$res21 = $mysqli->query($result21); // executa a query com os valores
	}

}else{
	$result21 = "UPDATE patiomaquinas SET status2 = '' WHERE idPatioMaquinas = '$id' AND idEmpresa = '$idEmpresa'";
	$res21 = $mysqli->query($result21); // executa a query com os valores
}
echo $result21;

$_SESSION['notify'] = 1;
$_SESSION['mensagem'] = 'Status da máquina alterado com sucesso!';

mysqli_close($mysqli); // fecha o mysql


header('location:controle-maquinas'); // redireciona para a pagina de controle-maquinas

?>
