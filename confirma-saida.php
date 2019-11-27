<?php
//pagina que confirma a saida de maquinas no controle de maquinas

	include('conexao.php'); // Pega a conexão do banco de dados

    session_start(); // inicia a sessao


		if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) // validação de usuario e senha
		{
			unset($_SESSION['usuario']);
			unset($_SESSION['senha']);
			header('location:login'); // redireciona para a tela de login
		}

	$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'"); // select para consulta de dados 

	while($consult= mysqli_fetch_array($consulta)){ // while para pegar os dados da consulta
			$idEmpresa   = $consult['idEmpresa'];
	}
	date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil
	$dataAtual = date('Y-m-d');
	
	$id          = $_POST['codigoMaquinaConfirma']; // define variavel para do dado recebido pelo post
	$idMaquina   = $_POST['idMaquinaConfirma']; // define variavel para do dado recebido pelo post
	$statusHist   = $_POST['statusHist']; // define variavel para do dado recebido pelo post
	$nomeMaquina = $_POST['nomeMaquina']; // define variavel para do dado recebido pelo post
	$patrimonio  = explode(' ', $nomeMaquina);

	// Inserção de valores
	$result = "UPDATE maquinas SET fase = '3', futuro = '0', dtLocacao = '$dataAtual' WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'"; // insere os valores no banco de dados com o insert
	$res = $mysqli->query($result); // executa a query com os valores
	$delet = "DELETE FROM saidamaquina WHERE idSaidaMaquina = '$id' AND idEmpresa = '$idEmpresa'"; // deleta os valores no banco de dados com o insert
	$del = $mysqli->query($delet); // executa a query com os valores
	$delet1 = "DELETE FROM patiomaquinas WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'"; // faz o update dos valores no banco de dados com o insert
	$del1 = $mysqli->query($delet1); // executa a query com os valores


if($statusHist == '2'){ // se o status for 2 ele faz o comando sql para buscar o historico
	$consultaHist = $mysqli->query("SELECT * FROM historico WHERE idEmpresa = '$idEmpresa' AND patrimonio = '$patrimonio[0]' AND etapa = 'disponivel' AND finalizado = '0'");
	while($consultHist= mysqli_fetch_array($consultaHist)){
		$idHistorico   = $consultHist['idHistorico'];// define variavel para do dado recebido pela consulta
	}

	$resultHist = "UPDATE historico SET dtSaida = '$dataAtual', finalizado = '1' WHERE patrimonio = '$patrimonio[0]' AND idEmpresa = '$idEmpresa' AND finalizado = '0' AND etapa = 'disponivel' AND idHistorico = '$idHistorico'";
	$resHist = $mysqli->query($resultHist); // executa a query com os valores do update

	$resultHist = "INSERT INTO historico (etapa, dtEntrada, dtSaida, patrimonio, idEmpresa, finalizado)
	VALUES ('locada', '$dataAtual', '0000-00-00', '$patrimonio[0]', '$idEmpresa', '0')";
	$resHist = $mysqli->query($resultHist);  // executa a query com os valores da inserção
}else{ // se o status não for 2 ele faz o comando sql para buscar o historico
	$consultaHist = $mysqli->query("SELECT * FROM historico WHERE idEmpresa = '$idEmpresa' AND patrimonio = '$patrimonio[0]' AND etapa = 'manutencao' AND finalizado = '0'");
	while($consultHist= mysqli_fetch_array($consultaHist)){
		$idHistorico   = $consultHist['idHistorico']; // define variavel para do dado recebido pela consulta
	}

	$resultHist = "UPDATE historico SET dtSaida = '$dataAtual', finalizado = '1' WHERE patrimonio = '$patrimonio[0]' AND idEmpresa = '$idEmpresa' AND finalizado = '0' AND etapa = 'manutencao' AND idHistorico = '$idHistorico'";
	$resHist = $mysqli->query($resultHist);  // executa a query com os valores do update

	$resultHist = "INSERT INTO historico (etapa, dtEntrada, dtSaida, patrimonio, idEmpresa, finalizado)
	VALUES ('locada', '$dataAtual', '0000-00-00', '$patrimonio[0]', '$idEmpresa', '0')";
	$resHist = $mysqli->query($resultHist);  // executa a query com os valores da inserção
}
	


	if($res && $del){ // se estiver tudo certo ele envia a notificação de confirmação
		$_SESSION['notify'] = 1;
		$_SESSION['mensagem'] = 'Saída confirmada com sucesso!';
	}
	else{
		echo mysqli_error($mysqli); // senao mostra um erro
	}

	mysqli_close($mysqli); // fecha o sql

	header('location:controle-maquinas'); // redireciona para controle de maquinas

?>
