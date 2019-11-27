<?php
// pagina que confirma o retorno da maquina


include('conexao.php'); // Pega a conexão do banco de dados

session_start(); //inicia a sessao


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
$id        				  = $_POST['codigoRetorno']; // define variavel para do dado recebido pelo post
$idRetornoMaquina   = $_POST['idRetornoMaquina']; // define variavel para do dado recebido pelo post
$nomeRetornoMaquina = $_POST['nomeRetornoMaquina']; // define variavel para do dado recebido pelo post

$result1 = "INSERT INTO patiomaquinas (idMaquina, nomeMaquina, status, disponivel, dtRetorno, idEmpresa)
VALUES ('$idRetornoMaquina', '$nomeRetornoMaquina', '4', '0', '$dataAtual', '$idEmpresa')"; // insere os valores no banco de dados com o insert
$res1 = $mysqli->query($result1); // executa a query com os valores

$delet = "DELETE FROM retornomaquinas WHERE idRetornoMaquinas = '$id' AND idEmpresa = '$idEmpresa'"; // deleta os valores no banco de dados com o insert
$del = $mysqli->query($delet); // executa a query com os valores

$result2 = "UPDATE maquinas SET fase = '4' WHERE idMaquina = '$idRetornoMaquina' AND idEmpresa = '$idEmpresa'"; // faz update dos valores no banco de dados com o insert
$res2 = $mysqli->query($result2); // executa a query com os valores

$patrimonio = explode(' ', $nomeRetornoMaquina);

$consultaHist = $mysqli->query("SELECT * FROM historico WHERE idEmpresa = '$idEmpresa' AND patrimonio = '$patrimonio[0]' AND etapa = 'locada' AND finalizado = '0'"); // select para consulta de dados 
while($consultHist= mysqli_fetch_array($consultaHist)){ // while para pegar os dados da consulta
	$idHistorico   = $consultHist['idHistorico'];
}

$resultHist = "UPDATE historico SET dtSaida = '$dataAtual', finalizado = '1' WHERE patrimonio = '$patrimonio[0]' AND idEmpresa = '$idEmpresa' AND finalizado = '0' AND etapa = 'locada' AND idHistorico = '$idHistorico'"; // faz update dos valores no banco de dados com o insert
$resHist = $mysqli->query($resultHist); // executa a query com os valores

$resultHist = "INSERT INTO historico (etapa, dtEntrada, dtSaida, patrimonio, idEmpresa)
VALUES ('manutencao', '$dataAtual', '0000-00-00', '$patrimonio[0]', '$idEmpresa')"; // insere os valores no banco de dados com o insert
$resHist = $mysqli->query($resultHist); // executa a query com os valores


$_SESSION['notify'] = 1;
$_SESSION['mensagem'] = 'Retorno da máquina realizado com sucesso!';

mysqli_close($mysqli); //fecha o sql

header('location:controle-maquinas'); // redireciona o controle de maquinas

?>
