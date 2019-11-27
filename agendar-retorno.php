<?php

// pagina de enviar para o banco de dados o agendamento de retorno

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

$dataRetorno    = $_POST['dataRetorno']; // define variavel para do dado recebido pelo post
$horaRetorno  	= $_POST['horaRetorno']; // define variavel para do dado recebido pelo post
$idMaquina			= $_POST['idMaquina']; // define variavel para do dado recebido pelo post
$nomeMaquina		= $_POST['nomeMaquina']; // define variavel para do dado recebido pelo post
$dataRetorno = implode("-",array_reverse(explode("/",$dataRetorno))); // converte a data de 00/00/0000 para 0000-00-00

// Inserção de valores

$result   = "INSERT INTO retornomaquinas (dataRetorno, horaRetorno, idMaquina, nomeMaquina, idEmpresa)
VALUES ('$dataRetorno', '$horaRetorno', '$idMaquina', '$nomeMaquina', '$idEmpresa')"; // insere os valores no banco de dados com o insert

$res = $mysqli->query($result); // executa a query com os valores

$result1 = "UPDATE maquinas SET fase = '5' WHERE idMaquina = '$idMaquina' AND idEmpresa = '$idEmpresa'";  // faz um update na tabela para informar se é futuro ou não

$res1 = $mysqli->query($result1); // executa a query com os valores

if($res && $res1){ // se res e res1 estiverem ok ele manda uma notificação
	$_SESSION['notify'] = 1;
	$_SESSION['mensagem'] = 'Retorno agendado com sucesso!';
}
else{
	echo mysqli_error($mysqli);
}

mysqli_close($mysqli); // fecha o mysql

header('location:controle-maquinas'); // redireciona para a pagina de controle-maquinas
?>
