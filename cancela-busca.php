<?php
// pagina que faz o cancelamento da busca do controle de maquinas

error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
include_once ('conexao.php'); // inclui a conexao do banco de dados
session_start(); // inicia a sessao

if((!isset ($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true))  // validação de usuario e senha
{
  unset($_SESSION['usuario']);
  unset($_SESSION['senha']);
  header('location:login'); // redireciona para a tela de login
}

$_SESSION['busca'] = 0; // define valor para a sessao
$_SESSION['modelo'] = ''; // define valor para a sessao
$_SESSION['fabricante'] = ''; // define valor para a sessao
$_SESSION['apelido'] = ''; // define valor para a sessao
$_SESSION['patrimonio'] = ''; // define valor para a sessao
$_SESSION['status'] = ''; // define valor para a sessao

header('location:controle-maquinas'); // redireciona para controle de maquinas

?>
