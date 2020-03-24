<?php 
	/* Host*/
	$host = $_SERVER['HTTP_HOST'];

	/* Dados do Banco de dados */
    $servidor = "92.249.44.132";
	$usuario = "u190238570_projeto";
	$senhaDB = "banco123";
	$dbname = "u190238570_projeto";
	$conn = mysqli_connect($servidor, $usuario, $senhaDB, $dbname);
	if ($conn->connect_error) {
		die("Falha na conexão: " . $conn->connect_error);
	}