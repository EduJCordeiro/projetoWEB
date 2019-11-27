<?php
// pagina enviar para o banco o cadastro da maquina

include('conexao.php'); // Pega a conexão do banco de dados

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

date_default_timezone_set('America/Sao_Paulo'); //define a data e hora Brasil

$modelo    	     = $_POST['modelo'];
$fabricante  	   = $_POST['fabricante'];
$apelido         = $_POST['apelido'];
$patrimonio      = $_POST['patrimonio'];
$status          = $_POST['status'];
$dataCadastro    = date("Y-m-d");

$nomeMaquina = $patrimonio." | ".$modelo." | ".$fabricante;

// Inserção de valores

$result   = "INSERT INTO maquinas (apelido, modelo, fabricante, patrimonio, dataCadastro, excluido, fase, idEmpresa)
VALUES ('$apelido', '$modelo', '$fabricante', '$patrimonio', '$dataCadastro', '0', '$status', '$idEmpresa')";
$res = $mysqli->query($result);

$consultaID =  $mysqli->query("SELECT * FROM maquinas WHERE apelido = '$apelido' AND modelo = '$modelo' AND fabricante = '$fabricante' AND patrimonio = '$patrimonio' AND dataCadastro = '$dataCadastro' AND idEmpresa = '$idEmpresa'");

while($reg_cadastro= mysqli_fetch_array($consultaID)){
	$id = $reg_cadastro['idMaquina'];
}

if($status == "2"){
	$result1 = "INSERT INTO patiomaquinas (idMaquina, nomeMaquina, status, disponivel, idEmpresa)
	VALUES ('$id', '$nomeMaquina', '$status', '1', '$idEmpresa')";
	$res1 = $mysqli->query($result1);
}else if($status == "4"){
	$result1 = "INSERT INTO patiomaquinas (idMaquina, nomeMaquina, status, disponivel, idEmpresa)
	VALUES ('$id', '$nomeMaquina', '$status', '0', '$idEmpresa')";
	$res1 = $mysqli->query($result1);
}


$_SESSION['notify'] = 1;
$_SESSION['mensagem'] = 'Máquina cadastrada com sucesso!';

mysqli_close($mysqli);

// DANDO ERRO NO SCRIPT QUE VOLTA AUTOMATICAMENTE
header('location:maquinas');

?>
