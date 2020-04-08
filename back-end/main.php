<?php
	set_error_handler(function () {
		die('<h1>Falha na conexão com o banco de dados</h1>');
	});

    /* Host*/
    $host = $_SERVER['HTTP_HOST'];

    /* Dados do Banco de dados */
    $servidor = 'localhost';
    $usuario = 'root';
    $senhaDB = 'vertrigo';
    $dbname = 'temp';
	$conn = mysqli_connect($servidor, $usuario, $senhaDB, $dbname);
	restore_error_handler();
    if ($conn->connect_error) {
        die('<h1>Falha na conexão com o banco de dados</h1>');
    }
