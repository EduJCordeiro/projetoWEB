<?php
// pagina para nao permitir que tenha modelos repetidos

// FAZ A CONEXAO COM BANCO DE DADOS

 $conexao = mysqli_connect('localhost','root','','projetoweb');
if (!$conexao) {
  die('Could not connect: ' . mysqli_error($conexao));
}

mysqli_select_db($conexao,"ajax_demo");

session_start();
if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))
{
  unset($_SESSION['usuario']);
  unset($_SESSION['senha']);
  header('location:login');
}

$modelo = mysqli_real_escape_string($conexao, $_REQUEST['modelo']);

$array_apart1 = array();
// $array_apart_cheio = array();
$sql_exibe1 = "SELECT *	FROM modelo WHERE nome = '$modelo' AND idEmpresa = '1'";
$result_exibe1 = mysqli_query($conexao, $sql_exibe1);
$TotalMod = mysqli_num_rows($result_exibe1);
$array_apart1[] = array(
  'modelo'			=> $TotalMod,
);

echo json_encode($array_apart1);

?>
