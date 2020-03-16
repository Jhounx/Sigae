<?php 
	/* Dados do Banco de dados */
    $servidor = "92.249.44.132";
	$usuario = "";
	$senhaDB = "";
	$dbname = "u190238570_projeto";
	$conn = mysqli_connect($servidor, $usuario, $senhaDB, $dbname);
	if ($conn->connect_error) {
		die("Falha na conexão: " . $conn->connect_error);
	}