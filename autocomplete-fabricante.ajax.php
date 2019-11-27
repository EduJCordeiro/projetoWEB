<?php
// completa o campo de fabricante automaticamente



 $conexao = mysqli_connect('localhost','root','','projetoweb');// FAZ A CONEXAO COM BANCO DE DADOS
if (!$conexao) { // verifica conexao
    die('Could not connect: ' . mysqli_error($conexao));
}

mysqli_select_db($conexao,"ajax_demo");

session_start(); // verifica sessao
if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))
{
  unset($_SESSION['usuario']);
  unset($_SESSION['senha']);
  header('location:login'); //redireciona para login
}


$modelo = mysqli_real_escape_string($conexao, $_REQUEST['modelo']); // busca o campo modelo

$array_apart = array();
// $array_apart_cheio = array();
$sql_exibe = "SELECT *	FROM modelo WHERE nome = '$modelo' AND idEmpresa = '1'"; // verifica no banco de dados
$result_exibe = mysqli_query($conexao, $sql_exibe);
while ($row = $result_exibe->fetch_assoc()) {
  $array_apart[] = array(
    'fabricante'			=> $row['fabricante'], // pega o fabricante
  );
}
echo json_encode($array_apart); // devolve o nome do fabricante

	?>
