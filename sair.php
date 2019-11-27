<?php
// pagina para sair da sessao
	include_once ('conexao.php');

	session_start();

	$_SESSION = array();
	session_destroy();
	header('location:login');
?>
