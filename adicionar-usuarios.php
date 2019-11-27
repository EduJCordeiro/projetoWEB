<?php
// pagina de adicionar usuarios no banco de dados

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

$usuario  = $_POST['usuarioAdiciona']; // define variavel para do dado recebido pelo post
$senha    = $_POST['senhaAdiciona']; // define variavel para do dado recebido pelo post
$area     = $_POST['areaAdiciona']; // define variavel para do dado recebido pelo post

// Inserção de valores

$result   = "INSERT INTO login (usuario, senha, area,  idEmpresa)
VALUES ('$usuario', '$senha', '$area', '$idEmpresa')"; // insere os valores no banco de dados com o insert

$res = $mysqli->query($result); // executa a query com os valores

if($res){ // se executar ele entra no if e mostra uma notificação na tela e redireciona
	$_SESSION['notify'] = 1;
	$_SESSION['mensagem'] = 'Usuário cadastrado com sucesso!';
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
}
else{ // senao ele mostra um erro
	echo mysqli_error($mysqli);
}

mysqli_close($mysqli); // fecha o mysql

?>
