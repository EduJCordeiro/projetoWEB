<?php
// pagina enviar para o banco o editar maquina

include('conexao.php'); // Pega a conexão do banco de dados

session_start(); // inicia a sessão

if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) // validação de usuario e senha
{
	unset($_SESSION['usuario']);
	unset($_SESSION['senha']);
	header('location:login'); // redireciona para a tela de login
}
$usuario = ($_SESSION['usuario']);

$consulta =  $mysqli->query("SELECT * FROM login WHERE usuario = '".$_SESSION['usuario']."'");  // select para consulta de dados 

while($consult= mysqli_fetch_array($consulta)){ // while para pegar os dados da consulta
	$idEmpresa   = $consult['idEmpresa'];
}

date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil

$id           = $_POST['idEditar']; // define variavel para do dado recebido pelo post
$modelo    	  = $_POST['modeloEditar']; // define variavel para do dado recebido pelo post
$fabricante   = $_POST['fabricanteEditar']; // define variavel para do dado recebido pelo post
$apelido      = $_POST['apelidoEditar']; // define variavel para do dado recebido pelo post
$patrimonio   = $_POST['patrimonioEditar']; // define variavel para do dado recebido pelo post
$status       = $_POST['statusEditar']; // define variavel para do dado recebido pelo post

$nomeMaquina = $patrimonio." | ".$modelo." | ".$fabricante; // define o "nome" da maquina

// Inserção de valores


if($status == "2"){ //verifica o status e faz as alterações no banco
	$result = "UPDATE maquinas SET apelido = '$apelido', modelo = '$modelo', fabricante = '$fabricante', patrimonio = '$patrimonio', fase = '$status', futuro = '0' WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$res = $mysqli->query($result); // executa a query com os valores

	$delet1 = "DELETE FROM patiomaquinas WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$del1 = $mysqli->query($delet1); // executa a query com os valores
	$delet2 = "DELETE FROM retornomaquinas WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$del2 = $mysqli->query($delet2); // executa a query com os valores
	$delet3 = "DELETE FROM saidamaquina WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$del3 = $mysqli->query($delet3); // executa a query com os valores

	$result1 = "INSERT INTO patiomaquinas (idMaquina, nomeMaquina, status, disponivel, idEmpresa)
	VALUES ('$id', '$nomeMaquina', '$status', '1', '$idEmpresa')";
	$res1 = $mysqli->query($result1); // executa a query com os valores
}else if($status == "3"){ //verifica o status e faz as alterações no banco
	$result = "UPDATE maquinas SET apelido = '$apelido', modelo = '$modelo', fabricante = '$fabricante', patrimonio = '$patrimonio', fase = '$status', futuro = '0' WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$res = $mysqli->query($result); // executa a query com os valores

	$delet1 = "DELETE FROM patiomaquinas WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$del1 = $mysqli->query($delet1); // executa a query com os valores
	$delet2 = "DELETE FROM retornomaquinas WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$del2 = $mysqli->query($delet2); // executa a query com os valores
	$delet3 = "DELETE FROM saidamaquina WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$del3 = $mysqli->query($delet3); // executa a query com os valores

}else if($status == "4"){ //verifica o status e faz as alterações no banco
	$result = "UPDATE maquinas SET apelido = '$apelido', modelo = '$modelo', fabricante = '$fabricante', patrimonio = '$patrimonio', fase = '$status', futuro = '0' WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$res = $mysqli->query($result); // executa a query com os valores

	$delet1 = "DELETE FROM patiomaquinas WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$del1 = $mysqli->query($delet1); // executa a query com os valores
	$delet2 = "DELETE FROM retornomaquinas WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$del2 = $mysqli->query($delet2); // executa a query com os valores
	$delet3 = "DELETE FROM saidamaquina WHERE idMaquina = '$id' AND idEmpresa = '$idEmpresa'";
	$del3 = $mysqli->query($delet3); // executa a query com os valores

	$result1 = "INSERT INTO patiomaquinas (idMaquina, nomeMaquina, status, disponivel, idEmpresa)
	VALUES ('$id', '$nomeMaquina', '$status', '0', '$idEmpresa')";
	$res1 = $mysqli->query($result1); // executa a query com os valores
}


$_SESSION['notify'] = 1;
$_SESSION['mensagem'] = 'Máquina editada com sucesso!';


mysqli_close($mysqli); // fecha o mysql

header('location:maquinas'); // redireciona para a pagina de controle-maquinas

?>
