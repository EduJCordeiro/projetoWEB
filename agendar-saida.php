<?php
// pagina de enviar para o banco de dados o agendamento de saida

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

$id        				= $_POST['codigoPatioMaquinaDefinir']; // define variavel para do dado recebido pelo post
$idMaquina 			  = $_POST['idPatioMaquinaDefinir']; // define variavel para do dado recebido pelo post
$nomePatioMaquina = $_POST['nomePatioMaquinaDefinir']; // define variavel para do dado recebido pelo post
$transportadora   = $_POST['nomeTransportadora']; // define variavel para do dado recebido pelo post
$dataSaida 			  = $_POST['dataSaida']; // define variavel para do dado recebido pelo post
$horaSaida        = $_POST['horaSaida']; // define variavel para do dado recebido pelo post
$observacoes      = $_POST['observacoes']; // define variavel para do dado recebido pelo post
$statusHist       = $_POST['statusHist']; // define variavel para do dado recebido pelo post
$data 				    = implode("-",array_reverse(explode("/",$dataSaida))); // converte a data de 00/00/0000 para 0000-00-00

$result12 = "UPDATE maquinas SET fase = '1' WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'";  // faz um update na tabela para informar se é futuro ou não
$res12 = $mysqli->query($result12); // executa a query com os valores

$result1   = "INSERT INTO saidamaquina (idMaquina, nomeMaquina, status, dataSaida, horaSaida, transportadora, idEmpresa, observacoes, statusHist)
VALUES ('$idMaquina', '$nomePatioMaquina', '1', '$data', '$horaSaida', '$transportadora', '$idEmpresa', '$observacoes', '$statusHist')";  // insere os valores no banco de dados com o insert
$res1 = $mysqli->query($result1); // executa a query com os valores

$_SESSION['notify'] = 1;
$_SESSION['mensagem'] = 'Saída agendada com sucesso!';

mysqli_close($mysqli); // fecha o mysql


header('location:controle-maquinas'); // redireciona para a pagina de controle-maquinas

?>
