<?php 
	/* Host*/
	$host = $_SERVER['HTTP_HOST'];

	/* Dados do Banco de dados */
    $servidor = "";
	$usuario = "";
	$senhaDB = "";
	$dbname = "";
	$conn = mysqli_connect($servidor, $usuario, $senhaDB, $dbname);
	if ($conn->connect_error) {
		die("Falha na conexão: " . $conn->connect_error);
	}