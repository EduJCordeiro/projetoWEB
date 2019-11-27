<?php
// pagina que faz o cancelamento do retorno da maquina


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

$id        				= $_POST['codigoRetorno']; // define variavel para do dado recebido pelo post
$idRetornoMaquina = $_POST['idRetornoMaquina']; // define variavel para do dado recebido pelo post

$delet = "DELETE FROM retornomaquinas WHERE idRetornoMaquinas = '$id' AND idEmpresa = '$idEmpresa'"; // deleta os valores no banco de dados com o insert
$del = $mysqli->query($delet);// executa a query com os valores

$result1 = "UPDATE maquinas SET fase = '3' WHERE idMaquina = '$idRetornoMaquina' AND idEmpresa = '$idEmpresa'"; // faz um update na tabela para informar se é futuro ou não

$res1 = $mysqli->query($result1);// executa a query com os valores


$_SESSION['notify'] = 1;
$_SESSION['mensagem'] = 'Retorno cancelado com sucesso!';


mysqli_close($mysqli); // fecha o mysql


header('location:controle-maquinas'); // redireciona para a pagina de controle-maquinas

?>
