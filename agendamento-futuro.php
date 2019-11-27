<?php

// pagina de enviar para o banco de dados o agendamento futuro

include('conexao.php'); // Pega a conexão do banco de dados

session_start(); // inicia a sessão


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

$idMaquina 			  = $_POST['idMaquina']; // define variavel para do dado recebido pelo post
$nomePatioMaquina = $_POST['nomeMaquina']; // define variavel para do dado recebido pelo post
$transportadora   = $_POST['nomeTransportadora']; // define variavel para do dado recebido pelo post
$dataSaida 			  = $_POST['dataSaida']; // define variavel para do dado recebido pelo post
$horaSaida        = $_POST['horaSaida']; // define variavel para do dado recebido pelo post
$observacoes      = $_POST['observacoes']; // define variavel para do dado recebido pelo post
$data 				    = implode("-",array_reverse(explode("/",$dataSaida))); // converte a data de 00/00/0000 para 0000-00-00

$result1   = "INSERT INTO saidamaquina (idMaquina, nomeMaquina, status, dataSaida, horaSaida, transportadora, idEmpresa, observacoes, futuro)
VALUES ('$idMaquina', '$nomePatioMaquina', '1', '$data', '$horaSaida', '$transportadora', '$idEmpresa', '$observacoes', '1')";// insere os valores no banco de dados com o insert

$res1 = $mysqli->query($result1);// executa a query com os valores



$result21 = "UPDATE maquinas SET futuro = '1' WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'"; // faz um update na tabela para informar se é futuro ou não

$res21 = $mysqli->query($result21); // executa a query com os valores


$_SESSION['notify'] = 1;
$_SESSION['mensagem'] = 'Saída agendada com sucesso!';

mysqli_close($mysqli); // fecha o mysql


header('location:controle-maquinas'); // redireciona para a pagina de controle-maquinas

?>
