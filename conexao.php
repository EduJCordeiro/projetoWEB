<?php
// faz conecao com o banco de dados
try
{
	$mysqli = new mysqli("localhost", "root", "", "projetoweb");

	$mysqli->query("SET NAMES 'utf8'");
	$mysqli->query('SET character_set_connection=utf8');
	$mysqli->query('SET character_set_client=utf8');
	$mysqli->query('SET character_set_results=utf8');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}



?>
